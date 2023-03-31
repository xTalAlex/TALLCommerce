<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShippingPriceResource\Pages;
use App\Filament\Resources\ShippingPriceResource\RelationManagers;
use App\Models\ShippingPrice;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ShippingPriceResource extends Resource
{
    protected static ?string $model = ShippingPrice::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    public static function getNavigationGroup(): string
    {
        return  __('Settings');
    }

    protected static ?int $navigationSort = 26;

    public static function getLabel(): string
    {
        return __('Shipping Price');
    }

    public static function getPluralLabel(): string
    {
        return __('Shipping Prices');
    }

    public static function canForceDelete(Model $record): bool
    {
       return $record->orders()->doesntExist(); 
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')->label(__('Name'))
                                    ->required(),
                                    //->unique(ignorable: fn (?ShippingPrice $record): ?ShippingPrice => $record),
                                Forms\Components\TextInput::make('price')->label(__('Price'))
                                    ->required()
                                    ->prefix('€')
                                    ->mask(
                                        fn (Forms\Components\TextInput\Mask $mask) => $mask
                                            ->numeric()
                                            ->decimalPlaces(2)
                                            ->decimalSeparator('.')
                                            ->mapToDecimalSeparator([',', '.'])
                                            ->thousandsSeparator(',')
                                            ->maxValue(999999)
                                    ),
                                Forms\Components\TextInput::make('min_spend')->label(__('Minimum Spend'))
                                    ->prefix('€')
                                    ->mask(
                                        fn (Forms\Components\TextInput\Mask $mask) => $mask
                                            ->numeric()
                                            ->decimalPlaces(2)
                                            ->decimalSeparator('.')
                                            ->mapToDecimalSeparator([',', '.'])
                                            ->thousandsSeparator(',')
                                            ->maxValue(999999)
                                    ),
                                Forms\Components\TextInput::make('description')->label(__('Description'))
                                    ->columnSpan('full'),
                                Forms\Components\Fieldset::make('delivery_time')->label(__('Delivery Time'))
                                    ->schema([
                                        Forms\Components\TextInput::make('min_days')->label(__('Min'))
                                            ->lte('max_days')
                                            ->numeric()
                                            ->minValue(0)
                                            ->suffix(strtolower(__('Days'))),
                                        Forms\Components\TextInput::make('max_days')->label(__('Max'))
                                            ->gte('min_days')
                                            ->numeric()
                                            ->minValue(0)
                                            ->suffix(strtolower(__('Days'))),
                                    ]),
                            ])->columns([
                                'md' => 2,
                            ]),
                    ])->columnSpan(2),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make(__('Settings'))
                            ->schema([
                                Forms\Components\Toggle::make('active')->label(__('Active'))
                                    ->columnSpan('full'),
                            ]),
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')->label(__('Created at'))
                                    ->content(fn (?ShippingPrice $record): string => $record ? $record->created_at->format(config('custom.datetime_format')) : '-'),
                                Forms\Components\Placeholder::make('updated_at')->label(__('Updated at'))
                                    ->content(fn (?ShippingPrice $record): string => $record ? $record->updated_at->format(config('custom.datetime_format')) : '-'),
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
                Tables\Columns\TextColumn::make('name')->label(__('Name'))
                    ->sortable(),
                Tables\Columns\IconColumn::make('active')->label(__('Active'))
                    ->trueColor('primary')
                    ->falseColor('secondary')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('price')->label(__('Price'))
                    ->money('eur')
                    ->sortable(),
                Tables\Columns\TextColumn::make('delivery_time')->label(__('Delivery Time'))
                    ->getStateUsing(fn (ShippingPrice $record) => $record->deliveryTimeLabel())
                    ->toggleable(),
                Tables\Columns\TextColumn::make('description')->label(__('Description')),
                Tables\Columns\TextColumn::make('updated_at')->label(__('Updated at'))
                    ->dateTime(config('custom.datetime_format'))
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
                Tables\Actions\RestoreBulkAction::make(),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListShippingPrices::route('/'),
            'create' => Pages\CreateShippingPrice::route('/create'),
            'edit' => Pages\EditShippingPrice::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
