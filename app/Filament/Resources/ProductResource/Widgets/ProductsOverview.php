<?php

namespace App\Filament\Resources\ProductResource\Widgets;

use App\Models\Product;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class ProductsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make( 
                trans('widgets.products.stats.ctr'), 
                Product::withoutGlobalScopes([ NotHidden::class ])->count() 
            ),

            Card::make( 
                trans('widgets.products.stats.inventory'), 
                Product::withoutGlobalScopes([ NotHidden::class ])->sum('quantity') 
            ),

            Card::make( 
                trans('widgets.products.stats.out_of_stock'), 
                Product::withoutGlobalScopes([ NotHidden::class ])->whereQuantity(0)->count()
            ),
        ];
    }
}
