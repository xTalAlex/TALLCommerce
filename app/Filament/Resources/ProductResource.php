<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Forms\Components\DateTimePicker;
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
                TextInput::make('name')
                    ->required(),
                TextInput::make('short_description')
                    ->maxLength(255),
                TextInput::make('description'),
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
                TextInput::make('quantity')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('featured'),
                Toggle::make('hidden'),
                SpatieMediaLibraryFileUpload::make('gallery')
                    ->collection('gallery')
                    ->multiple()
                    ->enableReordering(),
                BelongsToManyMultiSelect::make('categories')
                    ->relationship('categories', 'name'),
                DateTimePicker::make('created_at')
                    ->visibleOn(Pages\ViewCategory::class),
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
                SpatieMediaLibraryImageColumn::make('image'),
                TextColumn::make('orders_count')
                    ->counts('orders'),
                TextColumn::make('price')->money('eur'),
                TextColumn::make('quantity'),
                BooleanColumn::make('featured'),
                BooleanColumn::make('hidden'),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(),
                
            ])
            ->filters([
                //
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            //
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
