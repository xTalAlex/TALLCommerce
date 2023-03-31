<?php

namespace App\Filament\Resources\OrderResource\Widgets;

use App\Models\Order;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Collection;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class OrdersOverview extends BaseWidget
{
    protected function getCtrData() : Collection
    {
        $data = Trend::model(Order::class)
            ->between(
                start: now()->subMonths(1),
                end: now(),
            )
            ->perDay()
            ->count();

        return $data;
    }

    protected function getCards(): array
    {
        $included_statuses = [ 'paid', 'preparing', 'shipped' ];

        $ctrData = $this->getCtrData();

        return [
            Card::make( 
                trans('widgets.orders.stats.ctr'), 
                Order::placed()->count()
            )
            ->chart( $ctrData->map(fn (TrendValue $value) => $value->aggregate)->toArray() )
            ->color( 'primary' ),

            Card::make( 
                trans('widgets.orders.stats.open'), 
                Order::whereHas('status', fn($query) => $query->whereIn('name', $included_statuses) )->count()
            ),

            Card::make( 
                trans('widgets.orders.stats.avg_total'), 
                '€' . number_format( Order::placed()->avg('total') , 2)
            ),
        ];
    }
}
