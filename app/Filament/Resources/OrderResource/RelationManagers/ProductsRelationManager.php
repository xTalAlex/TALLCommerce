<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Closure;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Resources\RelationManagers\BelongsToManyRelationManager;

class ProductsRelationManager extends BelongsToManyRelationManager
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
        return fn (Model $record): string => route('filament.resources.products.view', ['record' => $record]);
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
                TextColumn::make('name')->label(__('Name')),
                TextColumn::make('short_description')->label(__('Short Description')),
                SpatieMediaLibraryImageColumn::make('image')->label(__('Image')),
                TextColumn::make('pivot_quantity')->label(__('Quantity')),
                TextColumn::make('pivot_price')->label(__('Price'))->money('eur'),
                TextColumn::make('pivot_discount')->label(__('Discount'))->money('eur'),
                TextColumn::make('total')->label(__('Total')),
            ])
            ->filters([
                //
            ]);
    }
}
