<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\RelationManagers\RelationManager;

class HistoryRelationManager extends RelationManager
{
    protected static string $relationship = 'history';

    public static function getTitle(): string
    {
        return __('History');
    }

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
                Tables\Columns\TextColumn::make('status.label')->label(__('Status')),
                Tables\Columns\TextColumn::make('description')->label(__('Description'))
                    ->wrap(),
                Tables\Columns\TextColumn::make('created_at')->label(__('Date'))
                    ->dateTime(config('custom.datetime_format'))
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
