<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Closure;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationManager;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    public static function getTitle(): string
    {
        return __('Products');
    }

    protected static ?string $recordTitleAttribute = 'name';

    protected function getTableQuery(): Builder
    {
        $query = parent::getTableQuery()
            ->select('products.*',
                    'order_product.product_id',
                    'order_product.order_id',
                    'order_product.price as pivot_price',
                    'order_product.quantity as pivot_quantity',
                    'order_product.discount as pivot_discount',
                    'order_product.total',
                );
                
        return $query;
    }

    protected function getTableRecordUrlUsing(): Closure
    {
        return fn (Model $record): string => route('filament.resources.products.edit', ['record' => $record]);
    }

    protected function canCreate(): bool { return false; }

    protected function canEdit(Model $record): bool { return false; }

    protected function canDelete(Model $record): bool { return false; }

    protected function canDetach(Model $record): bool { return false; }

    protected function canDeleteAny(): bool { return false; }

    protected function canAttach(): bool { return false; }

    protected function canDetachAny(): bool { return false; }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sku')->label(__('SKU'))
                    ->default('-')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('name')->label(__('Name')),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->label(__('Image'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('pivot_quantity')->label(__('Quantity')),
                Tables\Columns\TextColumn::make('pivot_price')->label(__('Price'))
                    ->money('eur')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('pivot_discount')->label(__('Discount'))
                    ->money('eur')
                    ->default(0)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('total')->label(__('Total'))
                    ->money('eur')
                    ->toggleable(),
            ])
            ->filters([
                //
            ]);
    }
}
