<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Pages\Actions\Action;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\OrderResource;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getActions(): array
    {
        $actions =  [];
        array_push( $actions , $this->getViewAction() );
        if ($this->record->statusCanBecome('Paied')) 
        {
            $paied= Action::make('paied')
                ->label(__('Set as Paied'))
                ->action(function (array $data): void {
                    $this->record->status()->associate(\App\Models\OrderStatus::where('name', 'like','Paied')->first()->id);
                    $this->record->save();
                    $this->redirect(route('filament.resources.orders.view', $this->record));
                    $this->notify('success','Set as Paied');
                })
                ->form([
                    Select::make('payment_gateway')
                        ->label(__('Payment Gateway'))
                        ->options(config('custom.payment_gateways'))
                        ->default('stripe')
                        ->disablePlaceholderSelection()
                        ->required(),
                    TextInput::make('payment_id')
                        ->label(__('Payment ID'))
                        ->default($this->record->payment_id)
                        ->required(),
                ]);
            array_push($actions, $paied);
        }
        if($this->record->statusCanBecome('Preparing'))
        {
            $prearing= Action::make('preparing')
                ->label(__('Prepare for Shipping'))
                ->action(function (array $data): void {
                    $this->record->status()->associate(\App\Models\OrderStatus::where('name','like','Preparing')->first()->id);
                    $this->record->save();
                    $this->redirect(route('filament.resources.orders.view', $this->record));
                    $this->notify('success','Set as Preparing for Shipping');
                });
            array_push( $actions , $prearing);
        }
        if($this->record->statusCanBecome('Shipped'))
        {
            $shipped= Action::make('shipped')
                ->label(__('Set as Shipped'))
                ->action(function (array $data): void {
                    $this->record->status()->associate(\App\Models\OrderStatus::where('name','like','Shipped')->first()->id);
                    $this->record->tracking_number= $data['tracking_number'];
                    $this->record->save();
                    $this->redirect(route('filament.resources.orders.view', $this->record));
                    $this->notify('success','Set as Shipped');
                })
                ->form([
                    TextInput::make('tracking_number')
                        ->label(__('Tracking Number'))
                        ->default($this->record->tracking_number),
                ]);
            array_push( $actions , $shipped);
        }
        if($this->record->statusCanBecome('Refunded'))
        {
            $refunded= Action::make('refunded')
                ->label(__('Set as Refunded'))
                ->color('danger')
                ->action(function (array $data): void {
                    $this->record->status()->associate(\App\Models\OrderStatus::where('name','like','Refunded')->first()->id);
                    $this->record->save();
                    $this->redirect(route('filament.resources.orders.view', $this->record));
                    $this->notify('success','Set as Refunded');
                });
            array_push( $actions , $refunded);
        }

        return $actions;
    }
}
