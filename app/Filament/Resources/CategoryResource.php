<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Category;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Layout;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CategoryResource\Pages;
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

    public static function getNavigationGroup(): string
    {
        return  __('Settings');
    }

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')->label(__('Name'))
                            ->required(),
                        Forms\Components\Select::make('parent_id')->label(__('Parent'))
                            ->relationship('parent', 'name', fn(?Category $record, $query) => 
                                $query->when($record, fn($query) => $query->whereNot('id', $record->id))
                            )
                            ->placeholder('-'),
                        Forms\Components\Toggle::make('featured')->label(__('Featured')),
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
                                ->imageCropAspectRatio('16:9')
                                ->panelAspectRatio('16:9'),
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
                    ->counts('products')
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')->label(__('Description'))
                    ->wrap()
                    ->visibleFrom('lg')
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('hero')
                    ->label(__('Hero'))
                    ->visibleFrom('md')
                    ->toggleable(),
            ])
            ->filters([
                    //
                ],
                layout: Layout::AboveContent,
            )
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                //
            ])
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
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    protected static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()
                            ->with(['parent','children'])
                            ->withCount(['products']);
    }
}
