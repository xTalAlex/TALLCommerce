<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use App\Models\Category;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Card;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Filters\MultiSelectFilter;
use App\Filament\Resources\ProductResource\Pages;
use Filament\Forms\Components\BelongsToManyMultiSelect;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Scopes\NotHiddenScope;

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

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([NotHiddenScope::class]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Card::make()
                        ->schema([
                            TextInput::make('name')
                                ->label(__('Name'))
                                ->required()
                                ->columnSpan([
                                    'sm' => 2,
                                ]),
                            TextInput::make('short_description')
                                ->label(__('Short Description'))
                                ->maxLength(255)
                                ->columnSpan([
                                    'sm' => 3,
                                ]),
                            RichEditor::make('description')
                                ->label(__('Description'))
                                ->columnSpan([
                                    'sm' => 3,
                                ]),
                            BelongsToManyMultiSelect::make('categories')
                                ->label(__('Categories'))
                                ->relationship('categories', 'name')
                                ->columnSpan([
                                    'sm' => 3,
                                ])
                                ->preload(true),
                            Fieldset::make('Pricing')
                                ->label(__('Pricing'))
                                ->schema([
                                    TextInput::make('original_price')
                                        ->label(__('OriginalPrice'))
                                        ->required()
                                        ->prefix('€')
                                        ->mask(fn (TextInput\Mask $mask) => $mask
                                            ->numeric()
                                            ->decimalPlaces(2)
                                            ->decimalSeparator('.')
                                            ->mapToDecimalSeparator([',','.'])
                                            ->thousandsSeparator(',')
                                            ->maxValue(999999)
                                        ),
                                    TextInput::make('selling_price')
                                        ->label(__('Selling Price'))
                                        ->prefix('€')
                                        ->mask(fn (TextInput\Mask $mask) => $mask
                                            ->numeric()
                                            ->decimalPlaces(2)
                                            ->decimalSeparator('.')
                                            ->mapToDecimalSeparator([',','.'])
                                            ->thousandsSeparator(',')
                                            ->maxValue(999999)
                                        ),
                                    TextInput::make('tax')
                                        ->label(__('Tax'))
                                        ->numeric()
                                        ->suffix('%')
                                        ->mask(fn (TextInput\Mask $mask) => $mask
                                            ->numeric()
                                            ->decimalPlaces(2)
                                            ->decimalSeparator('.')
                                            ->mapToDecimalSeparator([','])
                                            ->thousandsSeparator(',')
                                            ->maxValue(99)
                                        ),
                                ]),
                            TextInput::make('quantity')
                                ->label(__('Quantity'))
                                ->required()
                                ->numeric()
                                ->default(0),
                            Fieldset::make('Settings')
                                ->label(__('Settings'))
                                ->schema([
                                    Toggle::make('featured'),
                                    Toggle::make('hidden'),
                                ]),
                            DateTimePicker::make('created_at')
                                ->label(__('Created at'))
                                ->visibleOn(Pages\ViewProduct::class),
                            DateTimePicker::make('updated_at')
                                ->label(__('Updated at'))
                                ->visibleOn(Pages\ViewProduct::class),
                        ])
                        ->columns(3)
                        ->columnSpan(2),
                    Card::make()
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('gallery')
                                ->label(__('Gallery'))
                                ->collection('gallery')
                                ->multiple()
                                ->enableReordering(),
                        ])
                        ->columns(1)
                        ->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('short_description')
                    ->label(__('Short Description'))
                    ->searchable()
                    ->hidden(),
                TextColumn::make('description')
                    ->label(__('Description'))
                    ->searchable()
                    ->hidden(),
                SpatieMediaLibraryImageColumn::make('image')
                    ->label(__('Image')),
                TextColumn::make('orders_count')
                    ->label(__('Orders'))
                    ->counts('orders'),
                TextColumn::make('price')
                    ->label(__('Price'))
                    ->money('eur'),
                TextColumn::make('quantity')
                    ->label(__('Quantity')),
                BooleanColumn::make('featured')
                    ->label(__('Featured'))
                    ->trueColor('primary')
                    ->falseColor('secondary'),
                BooleanColumn::make('hidden')
                    ->label(__('Hidden'))
                    ->trueColor('primary')
                    ->falseColor('secondary'),
                TextColumn::make('updated_at')
                    ->label(__('Updated at'))
                    ->dateTime()
                    ->sortable(),
                
            ])
            ->filters([
                    MultiSelectFilter::make('categories')
                        ->label(__('Categories'))
                        ->options(Category::all()->pluck('name','id'))
                        ->query(function (Builder $query, array $data): Builder {
                            return $query
                                ->when(
                                    $data['values'],
                                    fn (Builder $query, $values): Builder => 
                                        $query->whereHas('categories', fn($query) => $query->whereIn('categories.id', $values)),
                                );
                        }),
                    Filter::make('featured')
                        ->label(__('Featured'))
                        ->query(fn (Builder $query): Builder => $query->where('featured', true)),
                    Filter::make('hidden')
                        ->label(__('Hidden'))
                        ->query(fn (Builder $query): Builder => $query->where('hidden', true)),
                    Filter::make('discounted')
                        ->label(trans_choice('Discounted',2))
                        ->query(fn (Builder $query): Builder => $query->whereColumn('selling_price', '<', 'original_price')),
                    Filter::make('quantity')
                        ->form([
                            TextInput::make('quantity')
                                ->label(__('Quantity'))
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
                ],
            );
    }
    
    public static function getRelations(): array
    {
        return [
            //RelationManagers\MediaRelationManager::class,
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
}
