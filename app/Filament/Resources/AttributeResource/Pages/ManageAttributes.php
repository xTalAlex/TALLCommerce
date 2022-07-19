<?php

namespace App\Filament\Resources\AttributeResource\Pages;

use App\Filament\Resources\AttributeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageAttributes extends ManageRecords
{
    protected static string $resource = AttributeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
