<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Tables\Filters\Layout;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $recordTitleAttribute = 'number';

    public static function getGlobalSearchResultTitle(Model $record): string
    {
        return '#' . $record->number;
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['number', 'invoice_serial_number'];
    }

    public static function getGlobalSearchResultUrl(Model $record): string
    {
        return OrderResource::getUrl('view', ['record' => $record]);
    }

    public static function getLabel(): string
    {
        return __('Order');
    }

    public static function getPluralLabel(): string
    {
        return __('Orders');
    }

    public static function getNavigationGroup(): string
    {
        return  __('Shop');
    }

    protected static ?int $navigationSort = 10;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                Forms\Components\Placeholder::make('note')->label(__('Note'))
                    ->hidden(fn (?Order $record) => $record->note == null)
                    ->content(fn (?Order $record) => $record->note)
                    ->columnSpan('full')
                    ->visibleOn(Pages\ViewOrder::class),

                Forms\Components\Card::make()
                    ->schema([

                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\Placeholder::make('number')->label(__('Number'))
                                    ->content(fn (?Order $record): string => $record ? $record->number : '-'),
                                Forms\Components\TextInput::make('tracking_number')->label(__('Tracking Number')),
                            ])
                            ->columns([
                                'md' => 2
                            ])
                            ->columnSpan('full'),


                        Forms\Components\Fieldset::make('shipping')->label(__('Shipping Details'))
                            ->schema([
                                Forms\Components\RichEditor::make('shipping_label')->label(__('Shipping Label'))
                                    ->columnSpan(2),
                                Forms\Components\Placeholder::make('shippingPrice.name')->label(__('Shipping'))
                                    ->content(fn (?Order $record): string => $record && $record->shippingPrice ? $record->shippingPrice->name : '-'),
                            ])
                            ->visibleOn(Pages\ViewOrder::class),

                        Forms\Components\Fieldset::make('Payment')->label(__('Payment Details'))
                            ->schema([

                                Forms\Components\Group::make()
                                    ->schema([
                                        Forms\Components\Select::make('payment_gateway')->label(__('Payment Gateway'))
                                            ->options(config('custom.payment_gateways'))
                                            ->disablePlaceholderSelection(),
                                        Forms\Components\TextInput::make('payment_id')->label(__('Payment ID'))
                                            ->columnSpan([
                                                'md' => 2,
                                            ]),
                                    ])
                                    ->columns([
                                        'md' => 3
                                    ])
                                    ->columnSpan('full'),

                                Forms\Components\Group::make()
                                    ->schema([

                                        Forms\Components\TextInput::make('subtotal')->label(__('Subtotal'))
                                            ->prefix('€'),
                                        Forms\Components\TextInput::make('tax')->label(__('Tax'))
                                            ->prefix('€'),

                                        Forms\Components\TextInput::make('shipping_price')->label(__('Shipping'))
                                            ->prefix('€'),

                                        Forms\Components\TextInput::make('total')->label(__('Total'))
                                            ->prefix('€'),

                                        Forms\Components\Fieldset::make('coupon')->label(__('Coupon'))
                                            ->schema([
                                                Forms\Components\Select::make('coupon')->label(__('Code'))
                                                    ->relationship('coupon', 'code')
                                                    ->placeholder('-'),
                                                Forms\Components\TextInput::make('coupon_discount')->label(__('Discount'))
                                                    ->prefix('€'),
                                            ])
                                            ->hidden(fn (?Order $record) => $record->coupon == null)
                                            ->columns([
                                                'md' => 2
                                            ])
                                            ->columnSpan('full'),

                                        Forms\Components\RichEditor::make('billing_label')->label(__('Billing Address'))
                                            ->columnSpan('full'),
    
                                    ])
                                    ->columns([
                                        'md' => 2
                                    ])
                                    ->columnSpan('full')
                                    ->visibleOn(Pages\ViewOrder::class),

                            ])
                            ->columnSpan('full'),

                    ])
                    ->columnSpan(2),

                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Select::make('user.id')->label(__('User'))
                                    ->relationship('user', 'name')
                                    ->placeholder('-'),
                                Forms\Components\TextInput::make('email')->label(__('Email'))
                                    ->email(),
                                Forms\Components\TextInput::make('phone')->label(__('Telefono')),

                                Forms\Components\TextInput::make('fiscal_code')->label(__('Fiscal Code')),
                                
                                Forms\Components\TextInput::make('vat')->label(__('VAT')),
                            ]),
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('status')->label(__('Status'))
                                    ->content(fn (?Order $record): string => $record ? $record->status->label : '-'),
                                Forms\Components\Placeholder::make('invoice')->label(__('Invoice Number'))
                                    ->content(fn (?Order $record): string => $record && $record->invoice_serial_number ? $record->invoice_serial_number : '-'),
                            ])->columns(1),
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')->label(__('Created at'))
                                    ->content(fn (?Order $record): string => $record ? $record->created_at->format(config('custom.datetime_format')) : '-'),
                                Forms\Components\Placeholder::make('updated_at')->label(__('Updated at'))
                                    ->content(fn (?Order $record): string => $record ? $record->updated_at->format(config('custom.datetime_format')) : '-'),
                            ]),
                    ])
                    ->columnSpan(1),
            ])
            ->columns([
                'md' => 3,
                'lg' => null,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('number')->label(__('Number'))
                    ->searchable()
                    ->sortable(),
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
                    ->url(
                        fn (Order $record): string =>
                        $record->user ?
                            route('filament.resources.users.view', $record->user->id)
                            : route('filament.resources.users.index')
                    )
                    ->toggleable(),
                Tables\Columns\TextColumn::make('email')->label(__('Email'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\BadgeColumn::make('shippingPrice.name')->label(__('Shipping'))
                    ->colors([
                        'secondary',
                        'secondary' => fn ($state): bool => $state !== null,
                    ]),
                Tables\Columns\TextColumn::make('total')->label(__('Total'))
                    ->money('eur')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('invoice_serial_number')->label(__('Invoice Number'))
                    ->sortable()
                    ->searchable()
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
            ->defaultSort('created_at', 'desc')
            ->filters(
                [
                    Tables\Filters\SelectFilter::make('status')
                        ->label(__('Status'))
                        ->relationship('status', 'name')
                        ->options(fn () => \App\Models\OrderStatus::all()->pluck('label', 'id')),
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
                    Tables\Filters\Filter::make('fast_shipping')->label(__('Fast Shipping'))
                        ->query(fn (Builder $query): Builder => 
                            $query->whereHas('shippingPrice', fn($query) => $query->fast())
                        ),
                    Tables\Filters\Filter::make('hide_drafts')->label(__('Hide Drafts'))
                        ->query(fn (Builder $query): Builder => 
                            $query->whereHas('status', fn($query) => $query->where('name','!=','draft'))
                        )->default(),
                ],
                layout: Layout::AboveContent,
            )
            ->actions([
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
                            ->default(fn (Order $record) => __('Order Update') . " " . $record->number)
                            ->required(),
                        Forms\Components\RichEditor::make('message')
                            ->label(__('Message'))
                            ->disableToolbarButtons([
                                'attachFiles',
                            ])
                            ->required(),
                    ]),
                Tables\Actions\EditAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProductsRelationManager::class,
            RelationManagers\HistoryRelationManager::class,
        ];
    }

    public static function getWidgets(): array
    {
        return [
            OrderResource\Widgets\OrdersOverview::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }
}
