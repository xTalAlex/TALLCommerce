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
use Filament\Tables\Filters\Layout;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\MultiSelect;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Forms\Components\DateTimePicker;
use Filament\Tables\Filters\MultiSelectFilter;
use App\Filament\Resources\ProductResource\Pages;
use Filament\Forms\Components\BelongsToManyMultiSelect;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use App\Filament\Resources\ProductResource\RelationManagers;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                    Card::make()
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->columnSpan([
                                    'sm' => 2,
                                ]),
                            TextInput::make('short_description')
                                ->maxLength(255)
                                ->columnSpan([
                                    'sm' => 3,
                                ]),
                            RichEditor::make('description')
                                ->columnSpan([
                                    'sm' => 3,
                                ]),
                            BelongsToManyMultiSelect::make('categories')
                                ->relationship('categories', 'name')
                                ->columnSpan([
                                    'sm' => 3,
                                ])
                                ->preload(true),
                            Fieldset::make('Pricing')
                                ->schema([
                                    TextInput::make('original_price')
                                        ->required()
                                        ->numeric()
                                        ->suffix('€')
                                        ->mask(fn (TextInput\Mask $mask) => $mask
                                            ->numeric()
                                            ->decimalPlaces(2)
                                            ->decimalSeparator('.')
                                            ->mapToDecimalSeparator([','])
                                            ->thousandsSeparator(',')
                                            ->normalizeZeros()
                                            ->padFractionalZeros()
                                            ->maxValue(999999)
                                        ),
                                    TextInput::make('selling_price')
                                        ->numeric()
                                        ->suffix('€')
                                        ->mask(fn (TextInput\Mask $mask) => $mask
                                            ->numeric()
                                            ->decimalPlaces(2)
                                            ->decimalSeparator('.')
                                            ->mapToDecimalSeparator([','])
                                            ->thousandsSeparator(',')
                                            ->normalizeZeros()
                                            ->padFractionalZeros()
                                            ->maxValue(999999)
                                        ),
                                    TextInput::make('tax')
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
                                ->required()
                                ->numeric()
                                ->default(0),
                            Fieldset::make('Settings')
                                ->schema([
                                    Toggle::make('featured'),
                                    Toggle::make('hidden'),
                                ]),
                            DateTimePicker::make('created_at')
                                ->visibleOn(Pages\ViewProduct::class),
                            DateTimePicker::make('updated_at')
                                ->visibleOn(Pages\ViewProduct::class),
                        ])
                        ->columns(3)
                        ->columnSpan(2),
                    Card::make()
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('gallery')
                            ->collection('gallery')
                            ->multiple()
                            ->enableReordering()
                            ->disk(config('media-library.disk_name')),
                        ])
                        ->columns(1)
                        ->columnSpan(1),
            ])->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()
                    ->searchable(),
                TextColumn::make('short_description')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('description')
                    ->searchable()
                    ->hidden(),
                SpatieMediaLibraryImageColumn::make('image'),
                TextColumn::make('orders_count')->label('Orders')
                    ->counts('orders'),
                TextColumn::make('price')->money('eur'),
                TextColumn::make('quantity'),
                BooleanColumn::make('featured')
                    ->trueColor('primary')
                    ->falseColor('secondary'),
                BooleanColumn::make('hidden')
                    ->trueColor('primary')
                    ->falseColor('secondary'),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
                
            ])
            ->filters([
                    MultiSelectFilter::make('categories')
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
                    ->query(fn (Builder $query): Builder => $query->where('featured', true)),
                    Filter::make('hidden')
                        ->query(fn (Builder $query): Builder => $query->where('hidden', true)),
                    Filter::make('discounted')
                        ->query(fn (Builder $query): Builder => $query->whereColumn('selling_price', '<', 'original_price')),
                    Filter::make('quantity')
                        ->form([
                            TextInput::make('quantity')
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
