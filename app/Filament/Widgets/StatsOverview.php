<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Order;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\StatsOverviewWidget\Card;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;

    protected function getRevenueData() : array
    {
        $excluded_statuses = [ 'draft', 'payment_failed', 'refunded', 'cancelled' ];

        $data = Trend::query(Order::whereDoesntHave('status', fn($query) => $query->whereIn('name', $excluded_statuses) ))
            ->between(
                start: now()->subMonths(1),
                end: now(),
            )
            ->perDay()
            ->sum('total');

        $lastValue = $data->map(fn (TrendValue $value) => $value->aggregate)->last();

        return [
            'total' => Order::whereDoesntHave('status', fn($query) => $query->whereIn('name', $excluded_statuses) )->sum('total'),
            'chart' => $data->map(fn (TrendValue $value) => $value->aggregate)->toArray(),
            'description' => $lastValue ? '+'.$lastValue : '',
            'icon'  => $lastValue ? 'heroicon-s-trending-up' : '',
            'color' => $lastValue ? 'success' : 'secondary',
        ];
    }

    protected function getOrdersData() : array
    {
        $excluded_statuses = [ 'draft', 'payment_failed', 'refunded', 'cancelled' ];

        $data = Trend::query(Order::whereDoesntHave('status', fn($query) => $query->whereIn('name', $excluded_statuses) ))
            ->between(
                start: now()->subMonths(1),
                end: now(),
            )
            ->perDay()
            ->count();

        $lastValue = $data->map(fn (TrendValue $value) => $value->aggregate)->last();

        return [
            'total' => Order::whereDoesntHave('status', fn($query) => $query->whereIn('name', $excluded_statuses) )->count(),
            'chart' => $data->map(fn (TrendValue $value) => $value->aggregate)->toArray(),
            'description' => $lastValue ? '+'.$lastValue : '',
            'icon'  => $lastValue ? 'heroicon-s-trending-up' : '',
            'color' => $lastValue ? 'success' : 'secondary',
        ];
    }

    protected function getUsersData() : array
    {
        $data = Trend::model(User::class)
            ->between(
                start: now()->subMonths(1),
                end: now(),
            )
            ->perDay()
            ->count();
        
        $lastValue = $data->map(fn (TrendValue $value) => $value->aggregate)->last();

        return [
            'total' => User::count(),
            'chart' => $data->map(fn (TrendValue $value) => $value->aggregate)->toArray(),
            'description' => $lastValue ? '+'.$lastValue : '',
            'icon'  => $lastValue ? 'heroicon-s-trending-up' : '',
            'color' => $lastValue ? 'success' : 'secondary',
        ];
    }

    protected function getCards(): array
    {
        $revenueData = $this->getRevenueData();
        $ordersData = $this->getOrdersData();
        $usersData = $this->getUsersData();

        return [

            Card::make( trans('widgets.stats.revenue.label'), '€'.$revenueData['total'])
                ->chart( $revenueData['chart'] )
                ->description($revenueData['description'])
                ->descriptionIcon($revenueData['icon'])
                ->color($revenueData['color']),

            Card::make( trans('widgets.stats.orders.label'), $ordersData['total'])
                ->chart( $ordersData['chart'] )
                ->description($ordersData['description'])
                ->descriptionIcon($ordersData['icon'])
                ->color($ordersData['color']),

            Card::make( trans('widgets.stats.users.label'), $usersData['total'])
                ->chart( $usersData['chart'] )
                ->description($usersData['description'])
                ->descriptionIcon($usersData['icon'])
                ->color($usersData['color']),

        ];
    }
}
