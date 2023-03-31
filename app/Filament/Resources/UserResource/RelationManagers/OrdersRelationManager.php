<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Facades\Filament;
use Filament\Tables\Actions\Action;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    protected static ?string $recordTitleAttribute = 'number';

    public static function getTitle(): string
    {
        return __('Orders');
    }

    protected function getTableRecordUrlUsing(): Closure
    {
        return fn (Order $record): string => route('filament.resources.orders.view', ['record' => $record]);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')->label(__('Number'))
                    ->searchable()
                    ->sortable(['id']),
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
                Tables\Columns\TextColumn::make('tracking_number')->label(__('Tracking Number'))
                    ->searchable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('total')->label(__('Total'))
                    ->money('eur')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->label(__('Created at'))
                    ->dateTime(config('custom.datetime_format'))
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')->label(__('Updated at'))
                    ->dateTime(config('custom.datetime_format'))
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at','desc')
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->label(__('Status'))
                    ->relationship('status', 'name')
                    ->options(fn() => \App\Models\OrderStatus::all()->pluck('label','id')),
            ])
            ->headerActions([
                //
            ])
            ->actions([
                Action::make('Email')
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
                    ->url(fn (Order $record): string => route('filament.resources.orders.edit', ['record' => $record])),
            ])
            ->bulkActions([
                //
            ]);
    }
    
    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery();
    }
}
