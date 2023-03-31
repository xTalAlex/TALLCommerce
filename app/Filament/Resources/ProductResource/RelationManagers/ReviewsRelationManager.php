<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Review;
use Filament\Resources\Form;
use Filament\Resources\Table;
use App\Models\Scopes\ApprovedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationManager;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    protected static ?string $inverseRelationship = 'product';

    public static function getTitle(): string
    {
        return trans_choice('Review',2);
    }
    
    public static function getRecordTitle(?Model $record): ?string
    {
        return trans_choice('Review',1);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('user')->label(__('User'))
                            ->content(fn (?Review $record): string => $record && $record->user ? $record->user->email : '-'),
                        Forms\Components\Placeholder::make('rating')->label(__('Rating'))
                            ->content(fn (?Review $record): string => $record && $record->rating ? $record->rating : '-')
                            ->disabled(),
                        Forms\Components\RichEditor::make('description')->label(__('Description'))
                            ->disabled()
                            ->columnSpan('full'),
                    ])
                    ->columns([
                        'sm' => 2,
                    ])
                    ->columnSpan([
                        'sm' => fn (?Review $record) => $record === null ? 3 : 2,
                    ]),
                Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Card::make()
                                ->schema([
                                    Forms\Components\Toggle::make('approved')->label(__('Approved')),
                                ]),
                            Forms\Components\Card::make()
                                ->schema([
                                    Forms\Components\Placeholder::make('created_at')->label(__('Created at'))
                                        ->content(fn (?Review $record): string => $record ? $record->created_at->format(config('custom.datetime_format')) : '-'),
                                    Forms\Components\Placeholder::make('updated_at')->label(__('Updated at'))
                                        ->content(fn (?Review $record): string => $record ? $record->updated_at->format(config('custom.datetime_format')) : '-'),
                                ]),
                        ])
                        ->columnSpan(1)
                        ->hidden(fn (?Review $record) => $record === null),
            ])
            ->columns([
                'sm' => 3,
                'lg' => null,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.email')->label(__('User'))
                    ->searchable(['name', 'email']),
                Tables\Columns\TextColumn::make('rating')->label(__('Rating'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')->label(__('Description'))
                    ->limit(100)
                    ->wrap(),
                Tables\Columns\IconColumn::make('approved')->label(__('Approved'))
                    ->trueColor('success')
                    ->falseColor('danger'),
                Tables\Columns\TextColumn::make('created_at')->label(__('Created at'))
                    ->dateTime(config('custom.datetime_format'))
                    ->sortable(),
            ])
            ->defaultSort('created_at','desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('approved')->label(__('Approvato'))
                    ->placeholder('-')
                    ->trueLabel(__('Yes'))
                    ->falseLabel(__('No'))
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
               //
            ]);
    }  
    
    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->withoutGlobalScopes([
                ApprovedScope::class
            ]);
    }
}
