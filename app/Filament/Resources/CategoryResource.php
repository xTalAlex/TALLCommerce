<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Category;
use Filament\Resources\Form;
use Filament\Resources\Table;
use App\Models\Scopes\NotHiddenScope;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Layout;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CategoryResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CategoryResource\RelationManagers;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getLabel(): string
    {
        return __('Category');
    }

    public static function getPluralLabel(): string
    {
        return __('Categories');
    }

    public static function getNavigationGroup(): string
    {
        return  __('Settings');
    }

    protected static ?int $navigationSort = 21;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')->label(__('Name'))
                            ->unique(ignorable: fn (?Category $record): ?Category => $record) 
                            ->required(),
                        Forms\Components\Select::make('parent_id')->label(__('Parent'))
                            ->relationship('parent', 'name', fn(?Category $record, $query) => 
                                $query->when($record, fn($query) => $query->whereNot('id', $record->id))
                            )
                            ->placeholder('-'),
                        Forms\Components\Textarea::make('description')->label(__('Description'))
                            ->rows(3)
                            ->maxLength(255)
                            ->autosize(true)
                            ->columnSpan([
                                'md' => 2,
                            ]),
                    ])
                    ->columns([
                        'md' => 2,
                    ])
                    ->columnSpan(2),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                        ->schema([
                            Forms\Components\SpatieMediaLibraryFileUpload::make('hero')->label(__('Hero'))
                                ->collection('hero')
                                ->panelLayout('circular')
                                //->imageCropAspectRatio('16:9')
                                ->panelAspectRatio('16:9')
                                ->enableDownload()
                                ->maxSize(config('media-library.max_file_size')/1024),
                        ]),
                        Forms\Components\Section::make(__('Settings'))
                            ->schema([
                                Forms\Components\Toggle::make('featured')->label(__('Featured')),
                            ]),
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')->label(__('Created at'))
                                    ->content(fn (?Category $record): string => $record ? $record->created_at->format(config('custom.datetime_format')) : '-'),
                                Forms\Components\Placeholder::make('updated_at')->label(__('Updated at'))
                                    ->content(fn (?Category $record): string => $record ? $record->updated_at->format(config('custom.datetime_format')) : '-'),
                            ]),
                    ])
                    ->columnSpan(1),
            ])
            ->columns([
                'md' => 3,
                'lg' => null,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('Name'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('parent.name')->label(__('Parent'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('products_count')->label(__('Products Count'))
                    ->sortable(),
                Tables\Columns\IconColumn::make('featured')->label(__('Featured'))
                    ->trueColor('primary')
                    ->falseColor('secondary')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('description')->label(__('Description'))
                    ->limit(100)
                    ->wrap()
                    ->visibleFrom('lg')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('hero')
                    ->label(__('Hero'))
                    ->visibleFrom('md')
                    ->toggleable(),
            ])
            ->defaultSort('name')
            ->filters([
                Tables\Filters\Filter::make('featured')->label(__('Featured'))
                    ->query(fn (Builder $query): Builder => $query->where('featured', true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\ChildrenRelationManager::class,
            RelationManagers\ProductsRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
                            ->withCount(['products' => function ($query) {
                                return $query->withoutGlobalScopes([
                                    SoftDeletingScope::class,
                                    NotHiddenScope::class,
                                ]);
                            }]);
    }
}
