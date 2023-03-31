<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Province;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Layout;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ProvinceResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\ProvinceResource\RelationManagers;

class ProvinceResource extends Resource
{
    protected static ?string $model = Province::class;

    protected static ?string $navigationIcon = 'heroicon-o-location-marker';

    public static function getLabel(): string
    {
        return __('Province');
    }

    public static function getPluralLabel(): string
    {
        return __('Provinces');
    }

    public static function getNavigationGroup(): string
    {
        return  __('Settings');
    }

    protected static ?int $navigationSort = 27;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Group::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')->label(__('Name'))
                                    ->unique(ignorable: fn (?Province $record): ?Province => $record)
                                    ->required(),
                                Forms\Components\TextInput::make('code')->label(__('Code'))
                                    ->extraInputAttributes(['onInput' => 'this.value = this.value.toUpperCase()'])
                                    ->unique(ignorable: fn (?Province $record): ?Province => $record)
                                    ->required()
                                    ->maxLength(2),
                                Forms\Components\Hidden::make('country')->label(__('Country'))
                                    ->required()
                                    ->default('IT'),
                                Forms\Components\Toggle::make('is_active')->label(__('Active'))
                                    ->default(false),
                            ])->columns([
                                'md' => 2
                            ])->columnSpan('full'),                 
                    ])
                    ->columns([
                        'md' => 2
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->label(__('Name'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('code')->label(__('Code'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('country')->label(__('Country'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\IconColumn::make('is_active')->label(__('Active'))
                    ->boolean()
            ])
            ->filters([
                Tables\Filters\Filter::make('active')->label(__('Active'))
                    ->query(fn (Builder $query): Builder => $query->active()),
                    
            ],layout: Layout::AboveContent)
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageProvinces::route('/'),
        ];
    }    
}
