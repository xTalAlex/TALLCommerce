<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Resources\Pages\ListRecords;
use App\Filament\Resources\ProductResource;
use App\Filament\Resources\ProductResource\Widgets;

class ListProducts extends ListRecords
{
    protected static string $resource = ProductResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            Widgets\ProductsOverview::class
        ];
    }
}
