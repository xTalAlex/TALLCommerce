<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Category;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\BelongsToSelect;
use Filament\Forms\Components\HasManyRepeater;
use App\Filament\Resources\CategoryResource\Pages;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\CategoryResource\RelationManagers;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required(),
                TextInput::make('description'),
                BelongsToSelect::make('parent_id')
                    ->relationship('parent', 'name'),
                SpatieMediaLibraryFileUpload::make('hero')
                    ->collection('hero'),
                DateTimePicker::make('updated_at')
                    ->visible(Pages\ViewCategory::class),
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
                    ->visibleFrom('lg')
                    ->searchable(),
                SpatieMediaLibraryImageColumn::make('hero')
                    ->visibleFrom('md'),
                TextColumn::make('updated_at')
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
