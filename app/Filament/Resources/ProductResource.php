<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Models\Scopes\NotHiddenScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\Widgets;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationGroup;
use App\Filament\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getGlobalSearchResultDetails(Model $record): array
    {
        return [
            'Slug' => $record->slug,
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['slug','tags.name'];
    }

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

    protected static ?int $navigationSort = 11;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Card::make()
                        ->schema([
                            Forms\Components\Group::make()
                                ->schema([
                                    Forms\Components\TextInput::make('name')->label(__('Name'))
                                        ->required()
                                        ->columnSpan([
                                            'md' => 2,
                                        ]), 
                                    Forms\Components\Select::make('brand')->label(__('Brand'))
                                        ->relationship('brand','name'), 
                                ])
                                ->columns([
                                    'md' => 3,    
                                ]),
                            Forms\Components\TextInput::make('short_description')->label(__('Short Description'))
                                ->maxLength(255),
                            Forms\Components\RichEditor::make('description')->label(__('Description'))
                                ->disableToolbarButtons([
                                    'attachFiles',
                                ]),
                            Forms\Components\MultiSelect::make('categories')->label(__('Categories'))
                                ->relationship('categories','name',
                                    fn (Builder $query, callable $get) => 
                                        $query->where(fn ($query) =>
                                                    $query->whereNull('parent_id')
                                                        ->orWhereIn('parent_id', $get('categories'))
                                                )
                                )
                                ->preload(true)
                                ->reactive()
                                ->afterStateUpdated(function(callable $get, callable $set){
                                    $selectedCategories = \App\Models\Category::findMany($get('categories'));
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
                            Forms\Components\MultiSelect::make('tags')->label(__('Tags'))
                                ->relationship('tags', 'name')
                                ->preload(true),

                        ])
                        ->columnSpan('full'),

                    Forms\Components\Card::make()
                        ->schema([
                            Forms\Components\Fieldset::make('pricing')->label(__('Pricing'))
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
                                        ->lte('original_price')
                                        ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask
                                            ->numeric()
                                            ->decimalPlaces(2)
                                            ->decimalSeparator('.')
                                            ->mapToDecimalSeparator([',','.'])
                                            ->thousandsSeparator(',')
                                            ->maxValue(999999)
                                        ),
                            ]),
                        ])
                        ->columnSpan('full'),

                    Forms\Components\Card::make()
                        ->schema([
                            Forms\Components\TextInput::make('SKU')->label(__('SKU')),   
                            Forms\Components\Select::make('variant')->label(__('Variant Of'))
                                ->relationship('defaultVariant', 'slug', fn(?Product $record, $query) => 
                                    $query->withoutGlobalScopes([SoftDeletingScope::class,NotHiddenScope::class])->whereNull('variant_id')
                                        ->when($record, fn($query) => $query->whereNot('id', $record->id))
                                )
                                ->disabled(fn(?Product $record) => $record ? $record->variants()->exists() : false ),
                            Forms\Components\TextInput::make('quantity')->label(__('Quantity'))
                                ->required()
                                ->numeric()
                                ->default(0),    
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
                        ])
                        ->columns([
                            'md' => 2,
                        ])
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
                            Forms\Components\Toggle::make('hidden')->label(__('Hidden'))
                                ->default(true),
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
            ->defaultSort('created_at','desc')
            ->filters([
                    Tables\Filters\TrashedFilter::make(),
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
                    Tables\Filters\MultiSelectFilter::make('tags')->label(__('Tags'))
                        ->options(\App\Models\Tag::all()->pluck('name','id'))
                        ->query(function (Builder $query, array $data): Builder {
                            return $query
                                ->when(
                                    $data['values'],
                                    fn (Builder $query, $values): Builder => 
                                        $query->whereHas('tags', fn($query) => $query->whereIn('tags.id', $values)),
                                );
                        }),
                    Tables\Filters\MultiSelectFilter::make('attributes')->label(__('Attributes'))
                        ->options(\App\Models\AttributeValue::all()->sortBy('attribute_id')->pluck('label','id'))
                        ->query(function (Builder $query, array $data): Builder {
                            $values = $data['values'];
                            if($values){
                                $query->whereHas('attributeValues', function($query) use ($values) {
                                    foreach($values as $value)
                                        $query->where('attribute_values.id', $value);
                                    return $query;
                                });
                            }
                            return $query;
                        }),
                    Tables\Filters\Filter::make('quantity')
                        ->form([
                            Forms\Components\TextInput::make('quantity')->label(__('Quantity'))
                                ->numeric()
                                ->suffix(__('or less')),
                        ])
                        ->query(function (Builder $query, array $data): Builder {
                            return $query
                                ->when(
                                    $data['quantity'],
                                    fn (Builder $query, $quantity): Builder => $query->where('quantity', '<=', $quantity),
                                );
                        }),
                    Tables\Filters\Filter::make('discounted')->label(trans_choice('Discounted',1))
                        ->query(fn (Builder $query): Builder => $query->whereColumn('selling_price', '<', 'original_price')),
                    Tables\Filters\Filter::make('featured')->label(__('Featured'))
                        ->query(fn (Builder $query): Builder => $query->where('featured', true)),
                    Tables\Filters\Filter::make('hidden')->label(__('Hidden'))
                        ->query(fn (Builder $query): Builder => $query->where('hidden', true)),
            
                ],
                //Tables\Filters\Layout::AboveContent
            )->actions([
                Tables\Actions\ReplicateAction::make()
                    ->excludeAttributes([
                        'slug',
                        'sku',
                        'discount'
                    ])
                    ->beforeReplicaSaved(function (Product $replica, array $data): void {
                        $data['hidden'] = true;
                        $replica->fill($data);
                    }),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkAction::make('collection')->label(__('Add to Collection'))
                    ->icon('heroicon-o-database')
                    ->action(function ( \Illuminate\Database\Eloquent\Collection $records, array $data): void {
                        foreach ($records as $record) {
                            $record->collections()->syncWithoutDetaching($data['collectionId']);
                            $record->save();
                        }
                        \Filament\Facades\Filament::notify('success', __('filament-support::actions/attach.multiple.messages.attached') );
                    })
                    ->form([
                        Forms\Components\Select::make('collectionId')->label(__('Collection'))
                            ->options(\App\Models\Collection::query()->withoutGlobalScopes([ NotHiddenScope::class ])->pluck('name', 'id'))
                            ->required(),
                    ]),
                Tables\Actions\RestoreBulkAction::make(),
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationGroup::make(__('Details'), [
                RelationManagers\AttributeValuesRelationManager::class,
                RelationManagers\VariantsRelationManager::class,
            ]),
            RelationManagers\ReviewsRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            Widgets\ProductsOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
                NotHiddenScope::class,
            ]);
    }
}
