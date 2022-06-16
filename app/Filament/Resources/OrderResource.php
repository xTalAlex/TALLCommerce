<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Order;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Forms\Components\RichEditor;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\BelongsToSelect;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    public static function getLabel(): string
    {
        return __('Order');
    }

    public static function getPluralLabel(): string
    {
        return __('Orders');
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->latest();
    }

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-cart';

    public static function canCreate(): bool { return false; }

    public static function canDelete(Model $record): bool { return false; }

    public static function canDeleteAny(): bool { return false; }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                BelongsToSelect::make('status')
                    ->label(__('Status'))
                    ->disabled()
                    ->dehydrated(false)
                    ->relationship('status', 'name'),
                TextInput::make('tracking_number')
                    ->label(__('Tracking Number')),
                RichEditor::make('shipping_label')
                    ->label(__('Shipping Label'))
                    ->columnSpan(2)
                    ->visibleOn(Pages\ViewOrder::class),
                Fieldset::make('Payment')
                    ->label(__('Payment'))
                    ->schema([
                        Select::make('payment_gateway')
                            ->label(__('Payment Gateway'))
                            ->options(config('custom.payment_gateways'))
                            ->disablePlaceholderSelection(),
                        TextInput::make('payment_id')
                            ->label(__('Payment ID')),
                        BelongsToSelect::make('coupon')
                            ->label(__('Coupon'))
                            ->relationship('coupon','code')
                            ->visibleOn(Pages\ViewOrder::class),
                        TextInput::make('coupon_discount')
                            ->label(__('Coupon Discount'))
                            ->prefix('€')
                            ->visibleOn(Pages\ViewOrder::class),
                        TextInput::make('subtotal')
                            ->label(__('Subtotal'))
                            ->prefix('€')
                            ->visibleOn(Pages\ViewOrder::class),
                        TextInput::make('tax')
                            ->label(__('Tax'))
                            ->prefix('%')
                            ->visibleOn(Pages\ViewOrder::class),
                        TextInput::make('total')
                            ->label(__('Total'))
                            ->prefix('€')
                            ->visibleOn(Pages\ViewOrder::class),
                        RichEditor::make('billing_label')
                            ->label(__('Billing Label'))
                            ->columnSpan(2)
                            ->visibleOn(Pages\ViewOrder::class),
                    ]),
                Fieldset::make('User')
                    ->schema([
                        TextInput::make('user.name')
                            ->label(__('Name')),
                        TextInput::make('email')
                            ->label(__('Email'))
                            ->email(),
                        TextInput::make('phone')
                            ->label(__('Telefono')),
                    ])->visibleOn(Pages\ViewOrder::class),
                Textarea::make('message')
                    ->label(__('Message'))
                    ->hidden(fn ($state) => $state==null)
                    ->columnSpan(2)
                    ->visibleOn(Pages\ViewOrder::class),                
                DateTimePicker::make('created_at')
                    ->label(__('Created at'))    
                    ->visibleOn(Pages\ViewOrder::class),
                DateTimePicker::make('updated_at')
                    ->label(__('Updated at'))
                    ->visibleOn(Pages\ViewOrder::class),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                    ->searchable(),
                BadgeColumn::make('status.name')
                    ->label(__('Status'))
                    ->colors([
                        'secondary',
                        'primary' => 'Shipped',
                        'success' => 'Completed',
                        'warning' => 'Paied',
                        'danger' => 'Cancelled',
                    ]),
                TextColumn::make('user.name')
                    ->label(__('Name'))
                    ->searchable()
                    ->url(fn (Order $record): string => 
                        $record->user ?
                            route('filament.resources.users.view', $record->user->id )
                            : route('filament.resources.users.index')
                    ),
                TextColumn::make('email')
                    ->label(__('Email'))
                    ->searchable(),
                TextColumn::make('total')
                    ->label(__('Total'))
                    ->money('eur')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->label(__('Updated at'))
                    ->dateTime()
                    ->sortable(), 
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label(__('Status'))
                    ->relationship('status', 'name'),
                Filter::make('total')
                    ->form([
                        TextInput::make('total')
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
                ],
                layout: Layout::AboveContent,
            )
            ->prependActions([
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
                            ->required(),
                        Forms\Components\RichEditor::make('message')
                            ->label(__('Message'))
                            ->required(),
                    ]),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\ProductsRelationManager::class,
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
}
