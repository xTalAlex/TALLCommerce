<?php

namespace App\Filament\Resources;

use Closure;
use Filament\Forms;
use Filament\Tables;
use App\Models\Attribute;
use Filament\Resources\Form;
use Filament\Resources\Table;
use App\Models\AttributeValue;
use App\Models\Scopes\NotHiddenScope;
use Filament\Resources\Resource;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\AttributeResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AttributeResource extends Resource
{
    protected static ?string $model = Attribute::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    public static function getLabel(): string
    {
        return __('Attribute');
    }

    public static function getPluralLabel(): string
    {
        return __('Attributes');
    }

    public static function getNavigationGroup(): string
    {
        return  __('Settings');
    }

    protected static ?int $navigationSort = 23;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\TextInput::make('name')->label(__('Name'))
                                    ->required()
                                    ->unique(ignorable: fn (?Attribute $record): ?Attribute => $record), 
                                Forms\Components\Select::make('type')->label(__('Type'))
                                    ->options( collect(config('custom.attribute_types'))->map(fn($option)=> __($option)) )
                                    ->default(array_key_first(config('custom.attribute_types')))
                                    ->reactive()
                                    ->required(),
                            ])->columns([
                                'md' => 2,
                            ]),  

                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Repeater::make('values')->label(__('Values'))
                                ->relationship('values')
                                ->schema([
                                    Forms\Components\TextInput::make('value')
                                        ->disableLabel()
                                        ->required()
                                        ->columnSpan([
                                            'md' => 1,
                                        ]),
                                    Forms\Components\Placeholder::make('products_count')->label(__('Products Count'))
                                        ->content(fn (?AttributeValue $record): string => $record ? 
                                                            $record->products()->withoutGlobalScopes([
                                                                    SoftDeletingScope::class,
                                                                    NotHiddenScope::class,
                                                            ])->count() 
                                                            : 0 ),
                                    Forms\Components\ColorPicker::make('color')
                                        ->disableLabel()
                                        ->hidden(fn (Closure $get) => $get('type') !== 'color')
                                        ->columnSpan([
                                            'md' => 1,
                                        ]),
                                ])
                                ->columns([
                                    'md' => 2,
                                ]),
                            ]),
                    ])->columnSpan(2),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')->label(__('Created at'))
                            ->content(fn (?Attribute $record): string => $record ? $record->created_at->format(config('custom.datetime_format')) : '-'),
                        Forms\Components\Placeholder::make('updated_at')->label(__('Updated at'))
                            ->content(fn (?Attribute $record): string => $record ? $record->updated_at->format(config('custom.datetime_format')) : '-'),
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
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('values_count')->label(__('Values Count'))
                    ->counts('values')
                    ->sortable(),
            ])
            ->filters([
                //
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
            'index' => Pages\ManageAttributes::route('/'),
        ];
    } 
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }
}
