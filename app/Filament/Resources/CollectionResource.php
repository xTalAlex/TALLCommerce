<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Collection;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use App\Models\Scopes\NotHiddenScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\CollectionResource\Pages;
use App\Filament\Resources\CollectionResource\RelationManagers;

class CollectionResource extends Resource
{
    protected static ?string $model = Collection::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getLabel(): string
    {
        return __('Collection');
    }

    public static function getPluralLabel(): string
    {
        return __('Collections');
    }

    public static function getNavigationGroup(): string
    {
        return  __('Shop');
    }
    
    protected static ?int $navigationSort = 12;

    protected static ?string $navigationIcon = 'heroicon-o-database';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')->label(__('Name'))
                            ->required()
                            ->unique(callback: function (\Illuminate\Validation\Rules\Unique $rule, callable $get) {
                                return $rule->where('brand_id', $get('brand_id'));
                            }, ignorable: fn (?Model $record): ?Model => $record),
                        Forms\Components\Select::make('brand_id')->label(__('Brand'))
                            ->relationship('brand', 'name')
                            ->placeholder('-'),
                        Forms\Components\Textarea::make('description')->label(__('Description'))
                            ->rows(3)
                            ->maxLength(255)
                            ->autosize(true)
                            ->columnSpan([
                                'md' => 2,
                            ]),
                    ])
                    ->columns([
                        'md' => 2,
                    ])
                    ->columnSpan(2),
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\SpatieMediaLibraryFileUpload::make('hero')->label(__('Hero'))
                                    ->collection('hero')
                                    ->panelLayout('circular')
                                    ->imageCropAspectRatio('16:9')
                                    ->panelAspectRatio('16:9')
                                    ->enableDownload()
                                    ->maxSize(config('media-library.max_file_size')/1024),
                            ]),
                        Forms\Components\Section::make(__('Settings'))
                            ->schema([   
                                Forms\Components\Toggle::make('featured')->label(__('Featured')),
                                Forms\Components\Toggle::make('hidden')->label(__('Hidden')),
                            ]),
                        Forms\Components\Card::make()
                            ->schema([
                                Forms\Components\Placeholder::make('created_at')->label(__('Created at'))
                                    ->content(fn (?Collection $record): string => $record ? $record->created_at->format(config('custom.datetime_format')) : '-'),
                                Forms\Components\Placeholder::make('updated_at')->label(__('Updated at'))
                                    ->content(fn (?Collection $record): string => $record ? $record->updated_at->format(config('custom.datetime_format')) : '-'),
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
                Tables\Columns\TextColumn::make('brand.name')->label(__('Brand'))
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('products_count')->label(__('Products Count'))
                    ->sortable(),
                Tables\Columns\IconColumn::make('featured')->label(__('Featured'))
                    ->trueColor('primary')
                    ->falseColor('secondary')
                    ->toggleable(),
                Tables\Columns\IconColumn::make('hidden')->label(__('Hidden'))
                    ->trueColor('primary')
                    ->falseColor('secondary')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('description')->label(__('Description'))
                    ->limit(100)
                    ->wrap()
                    ->visibleFrom('lg')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\SpatieMediaLibraryImageColumn::make('hero')
                    ->label(__('Hero'))
                    ->visibleFrom('md')
                    ->toggleable(),
            ])
            ->filters([
                Tables\Filters\Filter::make('featured')->label(__('Featured'))
                        ->query(fn (Builder $query): Builder => $query->where('featured', true)),
                Tables\Filters\Filter::make('hidden')->label(__('Hidden'))
                    ->query(fn (Builder $query): Builder => $query->where('hidden', true)),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\ProductsRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCollections::route('/'),
            'create' => Pages\CreateCollection::route('/create'),
            'edit' => Pages\EditCollection::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
                        ->withoutGlobalScopes([ NotHiddenScope::class ])
                        ->withCount(['products' => function ($query) {
                            return $query->withoutGlobalScopes([
                                SoftDeletingScope::class,
                                NotHiddenScope::class,
                            ]);
                        }]);
    }
    
    public static function getGlobalSearchResultDetails(Model $record): array
    {
        $details = [];
        if($record->brand)
            $details = [__('Brand') => $record->brand->name,];
        return $details;
    }

    protected static function getGlobalSearchEloquentQuery(): Builder
    {
        return parent::getGlobalSearchEloquentQuery()->with(['brand']);
    }
}
