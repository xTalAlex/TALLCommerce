<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\LineChartWidget;

class OrdersChart extends LineChartWidget
{

    protected static ?int $sort = 2;
    
    public ?string $filter = 'week';
    
    protected function getHeading(): string
    {
        return trans('widgets.orders.label');
    }

    protected function getFilters(): ?array
    {
        return [
            'today' => trans('widgets.time_filters.today'),
            'week' => trans('widgets.time_filters.week'),
            'month' => trans('widgets.time_filters.month'),
            'year' => trans('widgets.time_filters.year'),
        ];
    }
 
    protected function getData(): array
    {
        $activeFilter = $this->filter;

        $excluded_statuses = [ 'draft', 'payment_failed', 'refunded', 'cancelled' ];

        switch($activeFilter)
        {
            case('today'):
                $data = Trend::query(Order::whereDoesntHave('status', fn($query) => $query->whereIn('name', $excluded_statuses) ))
                    ->between(
                        start: now()->subDay(1),
                        end: now(),
                    )
                    ->perHour()
                    ->count();
                $labels = $data->map(fn (TrendValue $value) => \Carbon\Carbon::parse($value->date)->format('H:i') );
                break;
            case('week'):
                $data = Trend::query(Order::whereDoesntHave('status', fn($query) => $query->whereIn('name', $excluded_statuses) ))
                    ->between(
                        start: now()->subWeek(1),
                        end: now(),
                    )
                    ->perDay()
                    ->count();
                $labels = $data->map(fn (TrendValue $value) => \Carbon\Carbon::parse($value->date)->format(config('custom.datemonth_format')) );
                break;
            case('month'):
                $data = Trend::query(Order::whereDoesntHave('status', fn($query) => $query->whereIn('name', $excluded_statuses) ))
                    ->between(
                        start: now()->subMonths(1),
                        end: now(),
                    )
                    ->perDay()
                    ->count();
                $labels = $data->map(fn (TrendValue $value) => \Carbon\Carbon::parse($value->date)->format(config('custom.datemonth_format')) );
                break;
            default:
                $data = Trend::query(Order::whereDoesntHave('status', fn($query) => $query->whereIn('name', $excluded_statuses) ))
                    ->between(
                        start: now()->subYears(1),
                        end: now(),
                    )
                    ->perMonth()
                    ->count();
                $labels = $data->map(fn (TrendValue $value) => \Carbon\Carbon::parse($value->date)->translatedFormat('F') );
                break;

        }

        return [
            'datasets' => [
                [
                    'label' => __('Orders'),
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $labels,
        ];
    }
}
