<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class HistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'history';

    protected static ?string $recordTitleAttribute = 'id';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('status.name')
                    ->label(__('Status')),
                TextColumn::make('description')->label(__('Description'))
                    ->wrap(),
                TextColumn::make('created_at')
                    ->label(__('Created at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at','desc')
            ->filters([
                //
            ])
            ->headerActions([
                //
            ])
            ->actions([
               //
            ])
            ->bulkActions([
                //
            ]);
    }    
}
