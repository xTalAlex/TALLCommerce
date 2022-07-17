<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Resources\Form;
use Filament\Resources\Table;
use App\Scopes\NotHiddenScope;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationGroup;
use App\Filament\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    public static function getLabel(): string
    {
        return __('Product');
    }

    public static function getPluralLabel(): string
    {
        return __('Products');
    }

    public static function getNavigationGroup(): string
    {
        return  __('Shop');
    }

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')->label(__('Name'))
                            ->required()
                            ->columnSpan('full'),   
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('SKU')->label(__('SKU')),
                                Forms\Components\Select::make('variant')->label(__('Variant Of'))
                                    ->relationship('defaultVariant','unique_name'),                            
                            ])
                            ->columns([
                                'md' => 2,
                            ])
                            ->columnSpan('full'),
                        Forms\Components\TextInput::make('short_description')->label(__('Short Description'))
                            ->maxLength(255)
                            ->columnSpan('full'),
                        Forms\Components\RichEditor::make('description')->label(__('Description'))
                            ->columnSpan('full'),
                        Forms\Components\TextInput::make('weight')->label(__('Weight'))
                            ->prefix('Kg')
                            ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask
                                ->numeric()
                                ->decimalPlaces(2)
                                ->decimalSeparator('.')
                                ->mapToDecimalSeparator([',','.'])
                                ->thousandsSeparator(',')
                                ->maxValue(999999)
                            ),
                        Forms\Components\TextInput::make('quantity')->label(__('Quantity'))
                            ->required()
                            ->numeric()
                            ->default(0),
                        Forms\Components\Fieldset::make('price')->label(__('Price'))
                            ->schema([
                            Forms\Components\TextInput::make('original_price')->label(__('Original Price'))
                                    ->required()
                                    ->prefix('€')
                                    ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask
                                        ->numeric()
                                        ->decimalPlaces(2)
                                        ->decimalSeparator('.')
                                        ->mapToDecimalSeparator([',','.'])
                                        ->thousandsSeparator(',')
                                        ->maxValue(999999)
                                    ),
                                Forms\Components\TextInput::make('selling_price')->label(__('Selling Price'))
                                    ->prefix('€')
                                    ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask
                                        ->numeric()
                                        ->decimalPlaces(2)
                                        ->decimalSeparator('.')
                                        ->mapToDecimalSeparator([',','.'])
                                        ->thousandsSeparator(',')
                                        ->maxValue(999999)
                                    ),
                            ]),
                        Forms\Components\MultiSelect::make('categories')->label(__('Categories'))
                            ->relationship('categories','name',
                                fn (Builder $query, callable $get) => 
                                    $query->whereNotIn('id', $get('categories'))
                                            ->where(fn ($query) =>
                                                $query->whereNull('parent_id')
                                                    ->orWhereIn('parent_id', $get('categories'))
                                            )
                            )
                            ->preload(true)
                            ->reactive()
                            ->afterStateUpdated(function(callable $get, callable $set){
                                $selectedCategories = \App\Models\Category::findMany($get('categories'));
                                $removed = false;
                                do{
                                    $removed = false;
                                    foreach ($selectedCategories as $category) {
                                        if ($category->parent && !$selectedCategories->contains($category->parent)) {
                                            $selectedCategories = $selectedCategories->filter(
                                                fn ($selectedCategory) =>
                                                $selectedCategory->id!=$category->id
                                            );
                                            $removed = true;
                                        }
                                    }
                                }while($removed);
                                $set('categories',$selectedCategories->pluck('id')->toArray());
                            })
                            ->columnSpan('full'),
                        
                    ])
                    ->columns([
                        'md' => 2,
                    ])
                    ->columnSpan(2),
                
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\SpatieMediaLibraryFileUpload::make('gallery')->label(__('Gallery'))
                                    ->collection('gallery')
                                    ->multiple()
                                    ->enableReordering()
                                    ->panelLayout('circular')
                                    ->panelAspectRatio('5:6'),
                            ]),
                        Forms\Components\Section::make(__('Settings'))
                            ->schema([
                                Forms\Components\TextInput::make('slug')->label(__('Slug'))
                                    ->unique(ignorable: fn (?Product $record): ?Product => $record), 
                                Forms\Components\Toggle::make('featured')->label(__('Featured')),
                                Forms\Components\Toggle::make('hidden')->label(__('Hidden')),
                                Forms\Components\TextInput::make('tax')->label(__('Tax'))
                                    ->placeholder(config('cart.tax'))
                                    ->numeric()
                                    ->suffix('%')
                                    ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask
                                        ->numeric()
                                        ->decimalPlaces(2)
                                        ->decimalSeparator('.')
                                        ->mapToDecimalSeparator([','])
                                        ->thousandsSeparator(',')
                                        ->maxValue(99)
                                    ),
                                Forms\Components\TextInput::make('low_stock_threshold')->label(__('Low Stock Threshold'))
                                    ->placeholder(config('custom.stock_threshold'))
                                    ->numeric(),
                            ]),
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')->label(__('Created at'))
                                    ->content(fn (?Product $record): string => $record ? $record->created_at->format(config('custom.datetime_format')) : '-'),
                                Forms\Components\Placeholder::make('updated_at')->label(__('Updated at'))
                                    ->content(fn (?Product $record): string => $record ? $record->updated_at->format(config('custom.datetime_format')) : '-'),
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
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->label(__('Image'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('orders_count')->label(__('Orders'))
                    ->counts('orders')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('price')->label(__('Price'))
                    ->money('eur')
                    ->sortable(['selling_price','original_price']),
                Tables\Columns\TextColumn::make('quantity')->label(__('Quantity'))
                    ->sortable(),
                Tables\Columns\BooleanColumn::make('featured')->label(__('Featured'))
                    ->trueColor('primary')
                    ->falseColor('secondary')
                    ->toggleable(),
                Tables\Columns\BooleanColumn::make('hidden')->label(__('Hidden'))
                    ->trueColor('primary')
                    ->falseColor('secondary')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')->label(__('Updated at'))
                    ->dateTime(config('custom.datetime_format'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                
            ])
            ->filters([
                    Tables\Filters\MultiSelectFilter::make('categories')->label(__('Categories'))
                        ->options(\App\Models\Category::all()->pluck('name','id'))
                        ->query(function (Builder $query, array $data): Builder {
                            return $query
                                ->when(
                                    $data['values'],
                                    fn (Builder $query, $values): Builder => 
                                        $query->whereHas('categories', fn($query) => $query->whereIn('categories.id', $values)),
                                );
                        }),
                    Filter::make('featured')->label(__('Featured'))
                        ->query(fn (Builder $query): Builder => $query->where('featured', true)),
                    Filter::make('hidden')->label(__('Hidden'))
                        ->query(fn (Builder $query): Builder => $query->where('hidden', true)),
                    Filter::make('discounted')->label(trans_choice('Discounted',2))
                        ->query(fn (Builder $query): Builder => $query->whereColumn('selling_price', '<', 'original_price')),
                    Filter::make('quantity')
                        ->form([
                            Forms\Components\TextInput::make('quantity')->label(__('Quantity'))
                                ->numeric()
                                ->suffix('or less'),
                        ])
                        ->query(function (Builder $query, array $data): Builder {
                            return $query
                                ->when(
                                    $data['quantity'],
                                    fn (Builder $query, $quantity): Builder => $query->where('quantity', '<=', $quantity),
                                );
                        }),
                    Tables\Filters\TrashedFilter::make(),
                ],
            )->actions([
                Tables\Actions\ReplicateAction::make()
                    ->excludeAttributes([
                        'unique_name',
                        'slug',
                        'sku',
                        'discount'
                    ]),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\RestoreBulkAction::make(),
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationGroup::make('', [
                RelationManagers\AttributeValuesRelationManager::class,
                RelationManagers\VariantsRelationManager::class,
                RelationManagers\ReviewsRelationManager::class,
            ]),
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'view' => Pages\ViewProduct::route('/{record}'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
                NotHiddenScope::class,
            ])
            ->with(['media','attributeValues','defaultVariant','variants','reviews']);
    }
}
