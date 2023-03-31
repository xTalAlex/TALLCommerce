<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Coupon;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\CouponResource\Pages;
use App\Filament\Resources\CategoryResource\RelationManagers;
use Carbon\Carbon;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static ?string $recordTitleAttribute = 'code';

    public static function getLabel(): string
    {
        return __('Coupon');
    }

    public static function getPluralLabel(): string
    {
        return __('Coupons');
    }

    public static function getNavigationGroup(): string
    {
        return  __('Shop');
    }

    protected static ?int $navigationSort = 13;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('code')->label(__('Code'))
                                    ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()'])
                                    ->unique(ignorable: fn (?Coupon $record): ?Coupon => $record)
                                    ->required(),
                                Forms\Components\TextInput::make('amount')->label(__('Amount'))
                                    ->prefix(fn (Closure $get) => $get('is_fixed_amount') ? '€' : null )
                                    ->suffix(fn (Closure $get) => $get('is_fixed_amount') ? null : '%' )
                                    ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask
                                            ->numeric()
                                            ->decimalPlaces(2)
                                            ->decimalSeparator('.')
                                            ->mapToDecimalSeparator([',','.'])
                                            ->thousandsSeparator(',')
                                            ->maxValue(999999)
                                    )
                                    ->required(),
                            ])->columns([
                                'md' => 2
                            ])->columnSpan('full'),
                        Forms\Components\Toggle::make('is_fixed_amount')->label(__('Is Fixed Amount'))
                            ->reactive()
                            ->columnSpan('full')
                            ->hiddenOn('edit'),

                        Forms\Components\Fieldset::make('restrictions')->label(__('Restrictions'))
                            ->schema([
                                Forms\Components\TextInput::make('min_total')->label(__('Min Total'))
                                    ->prefix('€')
                                    ->mask(fn (Forms\Components\TextInput\Mask $mask) => $mask
                                        ->numeric()
                                        ->decimalPlaces(2)
                                        ->decimalSeparator('.')
                                        ->mapToDecimalSeparator([',','.'])
                                        ->thousandsSeparator(',')
                                        ->maxValue(999999)
                                    ),
                                Forms\Components\Toggle::make('once_per_user')->label(__('Once Per User')),
                                Forms\Components\TextInput::make('max_redemptions')->label(__('Max Redemptions'))
                                    ->numeric()
                                    ->minValue(1),
                                Forms\Components\DatePicker::make('expires_on')->label(__('Expires on'))
                                    ->displayFormat(config('custom.date_format')),
                            ])
                            ->columns([
                                'md' => 2,
                                'lg' => null,
                            ])
                            ->columnSpan('full'),
                    ])
                    ->columns([
                        'md' => 3
                    ])
                    ->columnSpan(2),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                        ->schema([
                            Forms\Components\Placeholder::make('redemptions')->label(__('Redemptions'))
                                ->content(fn (?Coupon $record): string => $record && $record->redemptions ? $record->redemptions : '-'),
                        ]),
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')->label(__('Created at'))
                                    ->content(fn (?Coupon $record): string => $record ? $record->created_at->format(config('custom.datetime_format')) : '-'),
                                Forms\Components\Placeholder::make('updated_at')->label(__('Updated at'))
                                    ->content(fn (?Coupon $record): string => $record ? $record->updated_at->format(config('custom.datetime_format')) : '-'),
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
                Tables\Columns\TextColumn::make('code')->label(__('Code'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('label')->label(__('Amount'))
                    ->searchable(['amount']),
                Tables\Columns\TextColumn::make('redemptions')->label(__('Redemptions'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('max_redemptions')->label(__('Max Redemptions'))
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('expires_on')->label(__('Expires on'))
                    ->dateTime(config('custom.date_format'))
                    ->sortable()
                    ->toggleable(),    
                Tables\Columns\TextColumn::make('created_at')->label(__('Created at'))
                    ->dateTime(config('custom.datetime_format'))
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('active')->label(__('Active'))
                    ->query(fn (Builder $query): Builder => 
                        $query->where( fn($query) =>
                                    $query->whereNotNull('max_redemptions')
                                        ->whereColumn('max_redemptions', '>', 'redemptions')
                                )
                                ->orWhere( fn($query) =>
                                    $query->whereNotNull('expires_on')
                                        ->where('expires_on', '>', Carbon::now())
                                )
                                ->orWhere( fn($query) =>
                                    $query->whereNull('max_redemptions')
                                        ->whereNull('expires_on')
                                )
                ),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
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
