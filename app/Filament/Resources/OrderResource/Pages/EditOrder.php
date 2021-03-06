<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Pages\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Pages\Actions\ViewAction;
use Filament\Pages\Actions\ActionGroup;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\OrderResource;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getActions(): array
    {
        $actions =  [];
        $grouped_actions = [];
        
        if($this->record->statusCanBecome('Preparing'))
        {
            $prearing= Action::make('preparing')->label(__('Prepare for Shipping'))
                ->action(function (array $data): void {
                    $status_id = \App\Models\OrderStatus::where('name', 'like','preparing')->first()->id;
                    $this->record->status()->associate($status_id);
                    $this->record->save();
                    $this->record->history()->create([
                        'order_status_id' => $status_id,
                    ]);
                    $this->redirect(route('filament.resources.orders.view', $this->record));
                    $this->notify('success',__('general.order_statuses.changes.preparing'));
                });
            array_push( $actions , $prearing);
        }
        if($this->record->statusCanBecome('Shipped'))
        {
            $shipped= Action::make('shipped')->label(__('Set as Shipped'))
                ->action(function (array $data): void {
                    $status_id = \App\Models\OrderStatus::where('name', 'like','shipped')->first()->id;
                    $this->record->status()->associate($status_id);
                    $this->record->tracking_number= $data['tracking_number'];
                    $this->record->save();
                    $this->record->history()->create([
                        'order_status_id' => $status_id,
                    ]);
                    $this->redirect(route('filament.resources.orders.view', $this->record));
                    $this->notify('success',__('general.order_statuses.changes.shipped'));
                })
                ->form([
                    TextInput::make('tracking_number')->label(__('Tracking Number'))
                        ->default($this->record->tracking_number),
                ]);
            array_push( $actions , $shipped);
        }
        array_push($actions, ViewAction::make());

        if ($this->record->statusCanBecome('Paied')) 
        {
            $paied= Action::make('paied')->label(__('Set as Paied'))
                ->action(function (array $data): void {
                    $status_id = \App\Models\OrderStatus::where('name', 'like','paied')->first()->id;
                    $this->record->status()->associate($status_id);
                    $this->record->save();
                    $this->record->history()->create([
                        'order_status_id' => $status_id,
                    ]);
                    $this->redirect(route('filament.resources.orders.view', $this->record));
                    $this->notify('success',__('general.order_statuses.changes.paied'));
                })
                ->form([
                    Select::make('payment_gateway')->label(__('Payment Gateway'))
                        ->options(config('custom.payment_gateways'))
                        ->default($this->record->payment_gateway)
                        ->disablePlaceholderSelection()
                        ->required(),
                    TextInput::make('payment_id')->label(__('Payment ID'))
                        ->default($this->record->payment_id)
                        ->required(),
                ]);
            array_push($grouped_actions, $paied);
        }
        if($this->record->statusCanBecome('Refunded'))
        {
            $refunded= Action::make('refunded')->label(__('Set as Refunded'))
                ->color('danger')
                ->action(function (array $data): void {
                    $status_id = \App\Models\OrderStatus::where('name', 'like','refunded')->first()->id;
                    $this->record->status()->associate($status_id);
                    $this->record->save();
                    $this->record->history()->create([
                        'order_status_id' => $status_id,
                    ]);
                    $this->record->restock();
                    $this->redirect(route('filament.resources.orders.view', $this->record));
                    $this->notify('success',__('general.order_statuses.changes.refunded'));
                })
                ->requiresConfirmation();
            array_push( $grouped_actions , $refunded);
        }    

        array_push($actions, ActionGroup::make($grouped_actions));

        return  $actions;
    }
}
