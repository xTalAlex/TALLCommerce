<?php

namespace App\Filament\Resources\BrandResource\RelationManagers;

use App\Models\Scopes\NotHiddenScope;
use Closure;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class ProductsRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $inverseRelationship = 'brand';

    protected static ?string $recordTitleAttribute = 'name';

    public static function getTitle(): string
    {
        return __('Products');
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
                Tables\Columns\TextColumn::make('name')->label(__('Name'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('image')->label(__('Image'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('paid_orders_count')->label(__('Orders'))
                    ->counts('orders')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('price')->label(__('Price'))
                    ->money('eur')
                    ->sortable(['selling_price', 'original_price']),
                Tables\Columns\TextColumn::make('quantity')->label(__('Quantity'))
                    ->sortable(),
                Tables\Columns\IconColumn::make('avaiable_from')->label(__('Avaiable From'))
                    ->boolean()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('featured')->label(__('Featured'))
                    ->trueColor('primary')
                    ->falseColor('secondary')
                    ->toggleable(),
                Tables\Columns\IconColumn::make('hidden')->label(__('Hidden'))
                    ->trueColor('primary')
                    ->falseColor('secondary')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('updated_at')->label(__('Updated at'))
                    ->dateTime(config('custom.datetime_format'))
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                    Tables\Filters\SelectFilter::make('categories')->label(__('Categories'))
                        ->multiple()
                        ->options(\App\Models\Category::all()->pluck('name', 'id'))
                        ->query(function (Builder $query, array $data): Builder {
                            return $query
                                ->when(
                                    $data['values'],
                                    fn (Builder $query, $values): Builder =>
                                    $query->whereHas('categories', fn ($query) => $query->whereIn('categories.id', $values)),
                                );
                        }),
                    Tables\Filters\SelectFilter::make('attributes')->label(__('Attributes'))
                        ->multiple()
                        ->options(\App\Models\AttributeValue::all()->sortBy('attribute_id')->pluck('label', 'id'))
                        ->query(function (Builder $query, array $data): Builder {
                            $values = $data['values'];
                            if ($values) {
                                $query->whereHas('attributeValues', function ($query) use ($values) {
                                    foreach ($values as $value)
                                        $query->where('attribute_values.id', $value);
                                    return $query;
                                });
                            }
                            return $query;
                        }),
                    Tables\Filters\Filter::make('featured')->label(__('Featured'))
                        ->query(fn (Builder $query): Builder => $query->where('featured', true)),
                    Tables\Filters\Filter::make('hidden')->label(__('Hidden'))
                        ->query(fn (Builder $query): Builder => $query->where('hidden', true)),
                    Tables\Filters\Filter::make('discounted')->label(trans_choice('Discounted', 1))
                        ->query(fn (Builder $query): Builder => $query->whereColumn('selling_price', '<', 'original_price')),
                    Tables\Filters\Filter::make('quantity')
                        ->form([
                            Forms\Components\TextInput::make('quantity')->label(__('Quantity'))
                                ->numeric()
                                ->suffix('or less'),
                        ])
                        ->query(function (Builder $query, array $data): Builder {
                            return $query
                                ->when(
                                    $data['quantity'],
                                    fn (Builder $query, $quantity): Builder => $query->where('quantity', '<=', $quantity),
                                );
                        }),
                    Tables\Filters\TrashedFilter::make(),
                ],
            )->actions([
                Tables\Actions\RestoreAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\RestoreBulkAction::make(),
                Tables\Actions\DeleteBulkAction::make(),
                Tables\Actions\ForceDeleteBulkAction::make(),
            ]);
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
                NotHiddenScope::class,
            ]);
    }
}
