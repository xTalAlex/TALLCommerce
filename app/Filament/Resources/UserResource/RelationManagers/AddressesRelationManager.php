<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\Model;

class AddressesRelationManager extends RelationManager
{
    protected static string $relationship = 'addresses';

    protected static ?string $recordTitleAttribute = 'id';

    public static function getTitle(): string
    {
        return __('Addresses');
    }
    protected function canCreate(): bool { return false; }

    protected function canEdit(Model $record): bool { return false; }

    protected function canDelete(Model $record): bool { return false; }

    protected function canDeleteAny(): bool { return false; }

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
                Tables\Columns\TextColumn::make('label')->label(__('Etichetta'))
                    ->html(),
                Tables\Columns\BadgeColumn::make('billing')->label(__('Type'))
                    ->enum([
                        0 => __('Shipping'),
                        1 => __('Billing'),
                    ])
                    ->colors([
                        'primary' => 0,
                        'secondary' => 1,
                    ]),
                Tables\Columns\IconColumn::make('default')->label(__('Default Address'))
                    ->trueColor('primary')
                    ->falseColor('secondary')
                    ->sortable(),
                
            ])
            ->filters([
                //
            ]);
    }
}
