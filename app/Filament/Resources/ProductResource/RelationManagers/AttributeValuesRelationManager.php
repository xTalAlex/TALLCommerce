<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use Closure;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class AttributeValuesRelationManager extends RelationManager
{
    protected static string $relationship = 'attributeValues';

    protected static ?string $recordTitleAttribute = 'value';

    public static function getTitle(): string
    {
        return __('Attribute Values');
    }

    public static function getModelLabel(): string
    {
        return __('Attribute Value');
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
                TextColumn::make('attribute.name')
                    ->label(__('Name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('value')
                    ->label(__('Value')),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make()
                    ->form(fn (Tables\Actions\AttachAction $action): array => [
                        Select::make('attribute')
                            ->label(('Name'))
                            ->relationship('attribute','name')
                            ->reactive()
                            ->dehydrated(false)
                            ->afterStateUpdated(fn(callable $set) => $set('recordId', null ) ),
                        $action->getRecordSelect()
                            ->label(('Value'))
                            ->disableLabel(false)
                            ->options(function(callable $get) {
                                $attribute = \App\Models\Attribute::find($get('attribute'));
                                if(!$attribute)
                                    $attribute_values = \App\Models\AttributeValue::all();
                                else
                                    $attribute_values = $attribute->values;
                                return $attribute_values->pluck('value','id');
                            })
                            ->searchable(false),
                    ]),
            ])
            ->actions([
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DetachBulkAction::make(),
            ]);
    }    
}
