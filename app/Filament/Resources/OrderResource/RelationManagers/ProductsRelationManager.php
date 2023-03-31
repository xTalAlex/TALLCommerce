<?php

namespace App\Filament\Resources\OrderResource\RelationManagers;

use Closure;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Filament\Resources\RelationManagers\RelationManager;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    public static function getTitle(): string
    {
        return __('Products');
    }

    protected static ?string $recordTitleAttribute = 'name';

    protected function getTableRecordUrlUsing(): Closure
    {
        return fn (Model $record): string => route('filament.resources.products.edit', ['record' => $record]);
    }

    protected function canCreate(): bool { return false; }

    protected function canEdit(Model $record): bool { return false; }

    protected function canDelete(Model $record): bool { return false; }

    protected function canDetach(Model $record): bool { return false; }

    protected function canDeleteAny(): bool { return false; }

    protected function canAttach(): bool { return false; }

    protected function canDetachAny(): bool { return false; }

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
                Tables\Columns\TextColumn::make('sku')->label(__('SKU'))
                    ->default('-')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('name')->label(__('Name')),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->label(__('Image'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('pivot.quantity')->label(__('Quantity')),
                Tables\Columns\TextColumn::make('pivot.price')->label(__('Price'))
                    ->money('eur')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('taxed_price')->label(__('Taxed Price'))
                    ->getStateUsing(function (Model $record){
                        return $record->applyTax($record->pivot->price);
                    })
                    ->money('eur',false)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('subtotal')->label(__('Subtotal'))
                    ->getStateUsing(function (Model $record){
                        return $record->pricePerQuantity($record->pivot->quantity, $record->pivot->price);
                    })
                    ->money('eur')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('total')->label(__('Total'))
                    ->getStateUsing(function (Model $record){
                        return $record->applyTax( $record->pricePerQuantity($record->pivot->quantity, $record->pivot->price) ) ;
                    })
                    ->money('eur')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('pivot.discount')->label(__('Discount'))
                    ->money('eur')
                    ->default(null)
                    ->toggleable(),
            ])
            ->filters([
                //
            ]);
    }
}
