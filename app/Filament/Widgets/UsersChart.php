<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Filament\Widgets\LineChartWidget;

class UsersChart extends LineChartWidget
{
    protected static ?int $sort = 2;

    public ?string $filter = 'month';
    
    protected function getHeading(): string
    {
        return trans('widgets.users.label');
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

        switch($activeFilter)
        {
            case('today'):
                $data = Trend::model(User::class)
                    ->between(
                        start: now()->subDay(1),
                        end: now(),
                    )
                    ->perHour()
                    ->count();
                $labels = $data->map(fn (TrendValue $value) => \Carbon\Carbon::parse($value->date)->format('H:i') );
                break;
            case('week'):
                $data = Trend::model(User::class)
                    ->between(
                        start: now()->subWeek(1),
                        end: now(),
                    )
                    ->perDay()
                    ->count();
                $labels = $data->map(fn (TrendValue $value) => \Carbon\Carbon::parse($value->date)->format(config('custom.datemonth_format')) );
                break;
            case('month'):
                $data = Trend::model(User::class)
                    ->between(
                        start: now()->subMonths(1),
                        end: now(),
                    )
                    ->perDay()
                    ->count();
                $labels = $data->map(fn (TrendValue $value) => \Carbon\Carbon::parse($value->date)->format(config('custom.datemonth_format')) );
                break;
            default:
                $data = Trend::model(User::class)
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
                    'label' => __('Users'),
                    'data' => $data->map(fn (TrendValue $value) => $value->aggregate),
                ],
            ],
            'labels' => $labels,
        ];
    }
}
