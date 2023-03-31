<?php

namespace App\Filament\Widgets;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestOrders extends BaseWidget
{
    protected static ?int $sort = 3;

    protected int | string | array $columnSpan = 'full';

    protected function getTableHeading(): string
    {
        return trans('widgets.orders.latest.label');
    }

    protected function getTableQuery(): Builder
    {
        return Order::placed()->latest('updated_at')->limit(50);
    }

    protected function getTableRecordUrlUsing(): Closure
    {
        return fn (Order $record): string => route('filament.resources.orders.view', ['record' => $record]);
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('number')->label(__('Number'))
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('status.label')->label(__('Status'))
                    ->colors([
                        'secondary',
                        'primary' => fn ($state): bool => 
                                        $state === __('general.order_statuses.shipped') || 
                                        $state === __('general.order_statuses.preparing'),
                        'success' => __('general.order_statuses.completed'),
                        'warning' => __('general.order_statuses.paid'),
                        'danger' => __('general.order_statuses.cancelled'),
                    ]),
                Tables\Columns\TextColumn::make('user.name')->label(__('Name'))
                    ->searchable()
                    ->url(fn (Order $record): string => 
                        $record->user ?
                            route('filament.resources.users.view', $record->user->id )
                            : route('filament.resources.users.index')
                    )
                    ->toggleable(),
                Tables\Columns\TextColumn::make('email')->label(__('Email'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('total')->label(__('Total'))
                    ->money('eur')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('invoice_serial_number')->label(__('Invoice Number'))
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->label(__('Created at'))
                    ->dateTime(config('custom.datetime_format'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')->label(__('Updated at'))
                    ->dateTime(config('custom.datetime_format'))
                    ->toggleable(),
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            Tables\Filters\Filter::make('fast_shipping')->label(__('Fast Shipping'))
                ->query(fn (Builder $query): Builder => 
                    $query->whereHas('shippingPrice', fn($query) => $query->where('max_days','!=',null)->where('max_days','<=',2))
                ),
            Tables\Filters\SelectFilter::make('status')
                ->label(__('Status'))
                ->relationship('status', 'name')
                ->options(fn() => \App\Models\OrderStatus::all()->pluck('label','id')),
            Tables\Filters\Filter::make('total')
                ->form([
                    Forms\Components\TextInput::make('total')
                        ->label(__('Total'))
                        ->numeric()
                        ->suffix(__('or more')),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query
                        ->when(
                            $data['total'],
                            fn (Builder $query, $total): Builder => $query->where('total', '>=', $total),
                        );
                }),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\Action::make('Email')
                ->color('success')
                ->icon('heroicon-o-mail')
                ->action(function (Order $record, array $data): void {
                    $record->notify(new \App\Notifications\AdminMessage($data['subject'], $data['message']));
                    Filament::notify('success', 'Email sent');
                })
                ->form([
                    Forms\Components\TextInput::make('subject')
                        ->label(__('Subject'))
                        ->default(fn(Order $record) => __('Order Update'). " " . $record->number )
                        ->required(),
                    Forms\Components\RichEditor::make('message')
                        ->label(__('Message'))
                        ->disableToolbarButtons([
                            'attachFiles',
                        ])
                        ->required(),
                ]),
            Tables\Actions\EditAction::make()
                ->url(fn (Order $record): string => route('filament.resources.orders.edit', ['record' => $record]))
        ];
    }
}
