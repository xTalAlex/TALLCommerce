<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Forms\Components\KeyValue;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Resources\RelationManagers\MorphManyRelationManager;


class MediaRelationManager extends MorphManyRelationManager
{
    protected static string $relationship = 'media';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $inverseRelationship = 'product';

    protected static ?string $title = 'Gallery';

    protected static bool $shouldPreloadAssociateFormRecordSelectOptions = true;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name'),
                KeyValue::make('custom_properties'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                ImageColumn::make('original_url'), // this needs a custom media model
                TextColumn::make('custom_properties'),
            ])
            ->filters([
                //
            ]);
    }
}
