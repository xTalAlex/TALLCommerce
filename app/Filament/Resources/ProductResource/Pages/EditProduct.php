<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Pages\Actions;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ProductResource;

class EditProduct extends EditRecord
{
    protected static string $resource = ProductResource::class;

    protected function getActions(): array
    {
        $actions = [];
        if($this->record->defaultVariant) {
            $default_variant = Actions\Action::make('default_variant')->label(__('Default Variant'))
                ->url(route('filament.resources.products.edit', $this->record->defaultVariant));
            array_push($actions,$default_variant);
        }
        if($this->record->defaultVariant){
        $set_default_variant = Actions\Action::make('set_default_variant')->label(__('Set As Default Variant'))
            ->action(function () { 
                $this->record->setAsDefaultVariant();
                $this->redirect(route('filament.resources.products.edit', $this->record));
                Filament::notify('success', __('Variants updated'));
            })
            ->requiresConfirmation();
        array_push($actions,$set_default_variant);
        }

        array_push($actions,
            Actions\RestoreAction::make(),
            Actions\DeleteAction::make(),
            Actions\ForceDeleteAction::make(),
        );

        return $actions;
    }
}
