<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Category;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use App\Filament\Resources\CategoryResource\Pages;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\CategoryResource\RelationManagers;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    public static function getLabel(): string
    {
        return __('Category');
    }

    public static function getPluralLabel(): string
    {
        return __('Categories');
    }


    protected static ?int $navigationSort = 3;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required(),
                TextInput::make('description')
                    ->label(__('Description')),
                Select::make('parent_id')
                    ->label(__('Parent'))
                    ->relationship('parent', 'name')
                    ->placeholder('-'),
                SpatieMediaLibraryFileUpload::make('hero')
                    ->label(__('Hero'))
                    ->collection('hero'),
                DateTimePicker::make('updated_at')
                    ->label(__('Updated at'))
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
                TextColumn::make('parent.name')
                    ->label(__('Parent'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('description')
                    ->label(__('Description'))
                    ->wrap()
                    ->visibleFrom('lg')
                    ->searchable(),
                SpatieMediaLibraryImageColumn::make('hero')
                    ->label(__('Hero'))
                    ->visibleFrom('md'),
                TextColumn::make('updated_at')
                    ->label(__('Updated at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                    //
                ],
                layout: Layout::AboveContent,
            )
            ->defaultSort('name');
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\ChildrenRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'view' => Pages\ViewCategory::route('/{record}'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }
}
