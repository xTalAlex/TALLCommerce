<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Models\Order;
use Filament\Pages\Actions;
use Filament\Facades\Filament;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\OrderResource;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getActions(): array
    {
        $actions = [];
        if ($this->record->canBeInvoiced()) {
            array_push(
                $actions,
                Actions\Action::make(__('Invoice'))
                    ->url(route('invoice.show', ['order' => $this->record]))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-document-download')
            );
        }
        array_push(
            $actions,
            Actions\Action::make('Email')
                ->color('success')
                ->icon('heroicon-o-mail')
                ->action(function (Order $record, array $data): void {
                    $record->notify(new \App\Notifications\AdminMessage($data['subject'], $data['message']));
                    Filament::notify('success', __('Email sent'));
                })
                ->form([
                    \Filament\Forms\Components\TextInput::make('subject')
                        ->label(__('Subject'))
                        ->default(fn (Order $record) => __('Order Update') . " " . $record->number)
                        ->required(),
                    \Filament\Forms\Components\RichEditor::make('message')
                        ->label(__('Message'))
                        ->disableToolbarButtons([
                            'attachFiles',
                        ])
                        ->required(),
                ])
        );
        array_push($actions,Actions\EditAction::make());

        return $actions;
    }
}
