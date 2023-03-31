<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Product;
use Filament\Resources\Form;
use Filament\Resources\Table;
use App\Models\Scopes\NotHiddenScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class VariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'variants';

    protected static ?string $inverseRelationship = 'defaultVariant';

    protected static ?string $recordTitleAttribute = 'slug';
    
    public static function getTitle(): string
    {
        return __('Variants');
    }

    public static function getModelLabel(): string
    {
        return __('Variant');
    }

    protected function getTableRecordUrlUsing(): Closure
    {
        return fn (Model $record): string => route('filament.resources.products.edit', ['record' => $record]);
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
                Tables\Columns\TextColumn::make('sku')->label(__('SKU'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('name')->label(__('Name')),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->label(__('Image'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('quantity')->label(__('Quantity')),
                Tables\Columns\TextColumn::make('price')->label(__('Price'))->money('eur'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AssociateAction::make()
                    ->recordSelectOptionsQuery(fn (Builder $query) => 
                        $query->withoutGlobalScopes([NotHiddenScope::class])
                            ->whereDoesntHave('variants')                
                ),
            ])
            ->actions([
                Tables\Actions\DissociateAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DissociateBulkAction::make(),
            ]);
    }    

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            //->whereColumn('id','!=','variant_id')
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
                NotHiddenScope::class,
            ]);
    }
}
