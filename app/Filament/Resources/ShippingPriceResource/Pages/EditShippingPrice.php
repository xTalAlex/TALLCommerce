<?php

namespace App\Filament\Resources\ShippingPriceResource\Pages;

use App\Filament\Resources\ShippingPriceResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditShippingPrice extends EditRecord
{
    protected static string $resource = ShippingPriceResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
            Actions\RestoreAction::make(),
        ];
    }
}
