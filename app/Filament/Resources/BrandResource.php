<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BrandResource\Pages;
use App\Filament\Resources\BrandResource\RelationManagers;
use App\Models\Brand;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BrandResource extends Resource
{
    protected static ?string $model = Brand::class;

    protected static ?string $navigationIcon = 'heroicon-o-office-building';

    protected static ?int $navigationSort = 7;

    public static function getLabel(): string
    {
        return __('Brand');
    }

    public static function getPluralLabel(): string
    {
        return __('Brands');
    }

    public static function getNavigationGroup(): string
    {
        return  __('Settings');
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
                                    ->required()
                                    ->unique(ignorable: fn (?Brand $record): ?Brand => $record), 
                                Forms\Components\TextInput::make('link')->label(__('Link'))
                                    ->url(),
                                Forms\Components\Toggle::make('featured')->label(__('Featured'))
                                    ->columnSpan('full'),
                                Forms\Components\SpatieMediaLibraryFileUpload::make('hero')->label(__('Hero'))
                                    ->collection('hero')
                                    ->panelLayout('circular')
                                    ->imageCropAspectRatio('16:9')
                                    ->panelAspectRatio('16:9')
                                    ->columnSpan('full'),
                            ])->columns([
                                'md' => 2,
                            ]),  
                    ])->columnSpan(2),
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\SpatieMediaLibraryFileUpload::make('logo')->label(__('Logo'))
                                    ->collection('logo')
                                    ->panelLayout('circular')
                                    ->imageCropAspectRatio('1:1')
                                    ->panelAspectRatio('1:1'),
                            ]),
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')->label(__('Created at'))
                                    ->content(fn (?Brand $record): string => $record ? $record->created_at->format(config('custom.datetime_format')) : '-'),
                                Forms\Components\Placeholder::make('updated_at')->label(__('Updated at'))
                                    ->content(fn (?Brand $record): string => $record ? $record->updated_at->format(config('custom.datetime_format')) : '-'),
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
                    ->sortable()
                    ->searchable(),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('logo')->label(__('Logo'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('products_count')->label(__('Products Count'))
                    ->counts('products')
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\BooleanColumn::make('featured')->label(__('Featured'))
                    ->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\Action::make('visit')->label(__('Visit'))
                    ->url(fn (Brand $record): string => url($record->link))
                    ->openUrlInNewTab()
                    ->icon('heroicon-o-external-link'),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListBrands::route('/'),
            'create' => Pages\CreateBrand::route('/create'),
            'edit' => Pages\EditBrand::route('/{record}/edit'),
        ];
    }    

    protected static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()
                            ->withCount(['products']);
    }
}
