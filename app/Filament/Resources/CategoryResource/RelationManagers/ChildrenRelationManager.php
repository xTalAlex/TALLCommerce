<?php

namespace App\Filament\Resources\CategoryResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Resources\RelationManagers\HasManyRelationManager;

class ChildrenRelationManager extends HasManyRelationManager
{
    protected static string $relationship = 'children';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $inverseRelationship = 'children';

    protected static bool $hasAssociateAction = true;
    
    protected static bool $hasDissociateAction = true;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),
                TextInput::make('description'),
                SpatieMediaLibraryFileUpload::make('hero')
                    ->collection('hero'),
                DateTimePicker::make('updated_at')
                    ->visibleOn(Pages\ViewCategory::class),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()
                    ->searchable(),
                TextColumn::make('parent.name')->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->wrap()
                    ->visibleFrom('md')
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ]);
    }
}
