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

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

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
                    ->disabled()
                    ->dehydrated(false)
                    ->relationship('status', 'name'),
                TextInput::make('tracking_number'),
                RichEditor::make('shipping_label')
                    ->columnSpan(2)
                    ->visibleOn(Pages\ViewOrder::class),
                Fieldset::make('Payment')
                    ->schema([
                        Select::make('payment_type')
                            ->options(config('custom.payment_gateways'))
                            ->disablePlaceholderSelection(),
                        TextInput::make('payment_id'),
                        TextInput::make('total')
                            ->prefix('€')
                            ->visibleOn(Pages\ViewOrder::class),
                        RichEditor::make('billing_label')
                            ->columnSpan(2)
                            ->visibleOn(Pages\ViewOrder::class),
                    ]),
                Fieldset::make('User')
                    ->schema([
                        TextInput::make('user.name'),
                        TextInput::make('email')->email(),
                        TextInput::make('phone'),
                    ])->visibleOn(Pages\ViewOrder::class),
                Textarea::make('message')
                    ->hidden(fn ($state) => $state==null)
                    ->columnSpan(2)
                    ->visibleOn(Pages\ViewOrder::class),                
                DateTimePicker::make('created_at')
                    ->visibleOn(Pages\ViewOrder::class),
                DateTimePicker::make('updated_at')
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
                    ->colors([
                        'secondary',
                        'primary' => 'Shipped',
                        'success' => 'Completed',
                        'warning' => 'Paied',
                        'danger' => 'Cancelled',
                    ]),
                TextColumn::make('user.name')
                    ->searchable()
                    ->url(fn (Order $record): string => 
                        $record->user ?
                            route('filament.resources.users.view', $record->user->id )
                            : route('filament.resources.users.index')
                    ),
                TextColumn::make('email')
                    ->searchable(),
                TextColumn::make('total')->money('eur')
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable(), 
            ])
            ->filters([
                SelectFilter::make('status')
                    ->relationship('status', 'name'),
                Filter::make('total')
                    ->form([
                        TextInput::make('total')
                            ->numeric()
                            ->suffix('or more'),
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
                            ->label('Subject')
                            ->required(),
                        Forms\Components\RichEditor::make('message')
                            ->label('Message')
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
