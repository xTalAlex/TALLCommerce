<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Review;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use App\Models\Scopes\ApprovedScope;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\ReviewResource\Pages;

class ReviewResource extends Resource
{
    protected static ?string $model = Review::class;

    protected static ?string $navigationIcon = 'heroicon-o-annotation';

    public static function getLabel(): string
    {
        return trans_choice('Review',1);
    }

    public static function getPluralLabel(): string
    {
        return trans_choice('Review',2);
    }

    public static function getNavigationGroup(): string
    {
        return  __('Shop');
    }

    protected static ?int $navigationSort = 14;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\Placeholder::make('user')->label(__('User'))
                            ->content(fn (?Review $record): string => $record && $record->user ? $record->user->email : '-'),
                        Forms\Components\Placeholder::make('rating')->label(__('Rating'))
                            ->content(fn (?Review $record): string => $record && $record->rating ? $record->rating : '-'),
                        Forms\Components\RichEditor::make('description')->label(__('Description'))
                            ->disabled()
                            ->columnSpan('full'),
                    ])
                    ->columns([
                        'sm' => 2,
                    ])
                    ->columnSpan([
                        'sm' => fn (?Review $record) => $record === null ? 3 : 2,
                    ]),
                Forms\Components\Group::make()
                        ->schema([
                            Forms\Components\Card::make()
                                ->schema([
                                    Forms\Components\Placeholder::make('product')->label(__('Product'))
                                        ->content(fn (?Review $record) => $record && $record->product ? $record->product->name : '-'),
                                    Forms\Components\Placeholder::make('product.image')->label(__('Image'))
                                        ->disableLabel()
                                        ->content(fn (?Review $record) => $record && $record->product ? new HtmlString("<img class='h-48 mx-auto' src='{$record->product->image}'/>") : '-'),
                                ]),
                            Forms\Components\Card::make()
                                ->schema([
                                    Forms\Components\Toggle::make('approved')->label(__('Approved')),
                                ]),
                            Forms\Components\Card::make()
                                ->schema([
                                    Forms\Components\Placeholder::make('created_at')->label(__('Created at'))
                                        ->content(fn (?Review $record): string => $record ? $record->created_at->format(config('custom.datetime_format')) : '-'),
                                    Forms\Components\Placeholder::make('updated_at')->label(__('Updated at'))
                                        ->content(fn (?Review $record): string => $record ? $record->updated_at->format(config('custom.datetime_format')) : '-'),
                                ]),
                        ])
                        ->columnSpan(1)
                        ->hidden(fn (?Review $record) => $record === null),
            ])
            ->columns([
                'sm' => 3,
                'lg' => null,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.email')->label(__('User'))
                    ->searchable(['name', 'email'])
                    ->sortable(),
                Tables\Columns\TextColumn::make('product.name')->label(__('Product'))
                    ->url( fn (Review $record): string => route('filament.resources.products.edit', ['record' => $record->product]) )
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\ImageColumn::make('product.image')->label(__('Image'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('rating')->label(__('Rating'))
                    ->sortable(),
                Tables\Columns\TextColumn::make('description')->label(__('Description'))
                    ->limit(100)
                    ->wrap(),
                Tables\Columns\IconColumn::make('approved')->label(__('Approved'))
                    ->trueColor('success')
                    ->falseColor('danger')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')->label(__('Created at'))
                    ->dateTime(config('custom.datetime_format'))
                    ->sortable(),
            ])
            ->defaultSort('created_at','desc')
            ->filters([
                Tables\Filters\TernaryFilter::make('approved')->label(__('Approvato'))
                    ->placeholder('-')
                    ->trueLabel(__('Yes'))
                    ->falseLabel(__('No'))
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                //
            ])
            ->bulkActions([
                //
            ]);
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageReviews::route('/'),
        ];
    }
    
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                ApprovedScope::class,
            ]);
    }
}
