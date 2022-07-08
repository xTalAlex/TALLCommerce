<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
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

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('user')
                    ->relationship('user', 'name')
                    ->label(__('User')),
                TextInput::make('rating')
                    ->label(__('Rating'))
                    ->numeric(),
                TextInput::make('description')
                    ->label(__('Description')),
                DateTimePicker::make('created_at')
                    ->label(__('Created at')),
                DateTimePicker::make('updated_at')
                    ->label(__('Updated at')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label(__('User')),
                TextColumn::make('rating')->label(__('Rating')),
                TextColumn::make('description')->label(__('Description'))
                    ->wrap(),
                TextColumn::make('updated_at')
                    ->label(__('Updated at'))
                    ->dateTime()
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
