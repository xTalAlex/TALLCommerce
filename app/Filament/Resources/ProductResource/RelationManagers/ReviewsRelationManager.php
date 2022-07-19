<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Review;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\RelationManagers\RelationManager;

class ReviewsRelationManager extends RelationManager
{
    protected static string $relationship = 'reviews';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $inverseRelationship = 'product';

    public static function getTitle(): string
    {
        return __('Reviews');
    }
    
    public static function getRecordTitle(?Model $record): ?string
    {
        return __('Review');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Select::make('user')->label(__('User'))
                            ->relationship('user', 'email'),
                        Forms\Components\Placeholder::make('rating')->label(__('Rating'))
                            ->content(fn (?Review $record): string => $record ? $record->rating : '-'),
                        Forms\Components\RichEditor::make('description')->label(__('Description'))
                            ->columnSpan('full'),
                    ])
                    ->columns([
                        'sm' => 2,
                    ])
                    ->columnSpan([
                        'sm' => fn (?Review $record) => $record === null ? 3 : 2,
                    ]),
                Forms\Components\Card::make()
                        ->schema([
                            Forms\Components\Placeholder::make('created_at')->label(__('Created at'))
                                ->content(fn (?Review $record): string => $record ? $record->created_at->format(config('custom.datetime_format')) : '-'),
                            Forms\Components\Placeholder::make('updated_at')->label(__('Updated at'))
                                ->content(fn (?Review $record): string => $record ? $record->updated_at->format(config('custom.datetime_format')) : '-'),
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
                Tables\Columns\TextColumn::make('user.email')->label(__('User')),
                Tables\Columns\TextColumn::make('rating')->label(__('Rating')),
                Tables\Columns\TextColumn::make('description')->label(__('Description'))
                    ->wrap(),
                Tables\Columns\TextColumn::make('created_at')->label(__('Created at'))
                    ->dateTime(config('custom.datetime_format'))
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
               //
            ]);
    }    
}
