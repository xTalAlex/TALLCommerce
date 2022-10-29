<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Closure;
use App\Models\Order;
use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\OrderResource\Widgets;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

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
