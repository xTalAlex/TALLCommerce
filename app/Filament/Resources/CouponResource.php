<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Coupon;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Toggle;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use App\Filament\Resources\CouponResource\Pages;
use App\Filament\Resources\CouponResource\RelationManagers;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('code')
                    ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()']),
                Toggle::make('is_fixed_amount')
                    ->reactive(),
                TextInput::make('amount')
                    ->prefix(fn (Closure $get) => $get('is_fixed_amount') ? '€' : null )
                    ->suffix(fn (Closure $get) => $get('is_fixed_amount') ? null : '%' )
                    ->mask(fn (TextInput\Mask $mask) => $mask
                            ->numeric()
                            ->decimalPlaces(2)
                            ->decimalSeparator('.')
                            ->mapToDecimalSeparator([',','.'])
                            ->thousandsSeparator(',')
                            ->maxValue(999999)
                        ),
                TextInput::make('max_redemption')
                    ->numeric()
                    ->minValue(1),
                DateTimePicker::make('expires_on'),
                DateTimePicker::make('created_at')
                    ->visibleOn(Pages\ViewCoupon::class),
                DateTimePicker::make('updated_at')
                    ->visibleOn(Pages\ViewCoupon::class),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('code')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('label')
                    ->searchable(['amount']),
                TextColumn::make('updated_at')->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ListCoupons::route('/'),
            'create' => Pages\CreateCoupon::route('/create'),
            'edit' => Pages\EditCoupon::route('/{record}/edit'),
        ];
    }
}
