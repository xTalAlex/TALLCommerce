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

    public static function getTitle(): string
    {
        return __('Children Categories');
    }

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $inverseRelationship = 'children';

    protected static bool $hasAssociateAction = true;
    
    protected static bool $hasDissociateAction = true;

    protected static bool $shouldPreloadAssociateFormRecordSelectOptions = true;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required(),
                TextInput::make('description')
                    ->label(__('Description')),
                // BelongsToSelect::make('parent_id')
                //     ->relationship('parent', 'name'),
                SpatieMediaLibraryFileUpload::make('hero')
                    ->label(__('Hero'))
                    ->collection('hero'),
                DateTimePicker::make('updated_at')
                    ->label(__('Update at')) 
                    ->visibleOn(Pages\ViewCategory::class),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                ->label(__('Name'))
                    ->sortable()
                    ->searchable(),
                // TextColumn::make('parent.name')->sortable()
                //     ->searchable(),
                TextColumn::make('description')
                    ->label(__('Description'))
                    ->wrap()
                    ->visibleFrom('md')
                    ->searchable(),
                TextColumn::make('updated_at')
                    ->label(__('Updated at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->bulkActions([]);
    }
}
