<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Closure;
use Filament\Forms;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Exports\OrdersExport;
use Filament\Pages\Actions\Action;
use Maatwebsite\Excel\Facades\Excel;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\OrderResource\Widgets;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getActions(): array
    {
        
        return [
            Action::make('export_daily_orders')->label(__('Export'))
                ->icon('heroicon-o-document-text')
                ->form([
                    Forms\Components\Grid::make()
                        ->schema([
                            Forms\Components\DateTimePicker::make('from_date')->label(__('From Date'))
                                ->default(yesterday()->startOfDay()),
                            Forms\Components\DateTimePicker::make('to_date')->label(__('To Date'))
                                ->gte('from_date')
                                ->default(today()->startOfDay()),
                            Forms\Components\Select::make('statuses')->label(__('Statuses'))
                                ->multiple()
                                ->options(OrderStatus::all()->pluck('label','id'))
                                ->default([OrderStatus::where('name','like','paid')->first()->id])
                        ])
                ])
                ->action(function (array $data) {
                    $file_name = 'daily_orders-'.(
                        \Carbon\Carbon::parse($data['from_date'])->format('dmyHi')
                        . '-' .
                        \Carbon\Carbon::parse($data['to_date'])->format('dmyHi')
                    ).'.csv';
                    // Excel::store(new OrdersExport($data['from_date'],$data['to_date'],$data['statuses']), 'data/export/'.$file_name , config('filesystems.default') , \Maatwebsite\Excel\Excel::CSV, [
                    //     'visibility' => 'private',
                    // ]);
                    return Excel::download(new OrdersExport($data['from_date'],$data['to_date'],$data['statuses']), $file_name .'.csv');
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            Widgets\OrdersOverview::class
        ];
    }

    protected function getTableRecordUrlUsing() : Closure
    {
        return fn (Order $record) => route('filament.resources.orders.view', ['record' => $record]);
    }
}
