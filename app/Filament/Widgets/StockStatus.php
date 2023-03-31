<?php

namespace App\Filament\Widgets;

use Closure;
use Filament\Tables;
use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;

class StockStatus extends BaseWidget
{
    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 'full';

    protected function getTableHeading(): string
    {
        return trans('widgets.products.stock.label');
    }

    protected function getTableRecordUrlUsing(): Closure
    {
        return fn (Product $record): string => route('filament.resources.products.edit', ['record' => $record]);
    }

    protected function getTableRecordClassesUsing(): ?Closure
    {
        return fn (Product $record) => $record->quantity < $record->quantity_sum ? 'text-danger-500 bg-danger-500/10' : null;
    }

    protected function getTableQuery(): Builder
    {
        return Product::whereHas('orders', fn($query) =>
                $query->whereHas('status', fn($query) => $query->where('name','paid'))
                    ->orWhereHas('history.status', fn($query) => 
                        $query->whereIn('name',['processing','shipped','completed'])
                            ->where('created_at','>=','today')
                    )
            )
            ->selectRaw('products.id,products.name,products.quantity,products.original_price,products.selling_price,sum(order_product.quantity) as quantity_sum')
            ->rightJoin('order_product', 'products.id', '=', 'order_product.product_id')
            ->groupBy('products.id','products.name','products.quantity','products.original_price','products.selling_price');
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('name')->label(__('Name')),
            Tables\Columns\TextColumn::make('quantity_sum')->label(__('Demand')),
            Tables\Columns\TextColumn::make('quantity')->label(__('Avaiability')),
        ];
    }
}
