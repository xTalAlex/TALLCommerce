<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Review;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Illuminate\Support\HtmlString;
use App\Models\Scopes\ApprovedScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationManager;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    protected static ?string $inverseRelationship = 'user';

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
                        Forms\Components\Placeholder::make('rating')->label(__('Rating'))
                            ->content(fn (?Review $record): string => $record && $record->rating ? $record->rating : '-')
                            ->disabled(),
                        Forms\Components\RichEditor::make('description')->label(__('Description'))
                            ->disabled(),
                    ])
                    ->columnSpan([
                        'sm' => fn (?Review $record) => $record === null ? 3 : 2,
                    ]),
                Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Card::make()
                                ->schema([
                                    Forms\Components\Placeholder::make('product')->label(__('Product'))
                                        ->content(fn (?Review $record) => $record && $record->product ? $record->product->name : '-'),
                                    Forms\Components\Placeholder::make('product.image')->label(__('Image'))
                                        ->disableLabel()
                                        ->content(fn (?Review $record) => $record && $record->product ? new HtmlString("<img class='h-48 mx-auto' src='{$record->product->image}'/>") : '-'),
                                ]),
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
                Tables\Columns\TextColumn::make('product.name')->label(__('Product'))
                    ->url( fn (Review $record): string => route('filament.resources.products.edit', ['record' => $record->product]) )
                    ->searchable()
                    ->sortable(),
                Tables\Columns\ImageColumn::make('product.image')->label(__('Image'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('rating')->label(__('Rating'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')->label(__('Description'))
                    ->limit(100)
                    ->wrap(),
                Tables\Columns\IconColumn::make('approved')->label(__('Approved'))
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),
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
