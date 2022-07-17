<?php

namespace App\Filament\Resources\ProductResource\Pages;

use App\Filament\Resources\ProductResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Pages\Actions;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getActions(): array
    {
        return [
            Actions\RestoreAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
        ];
    }
}
