<?php

namespace App\Filament\Widgets;

use Closure;
use Filament\Tables;
use App\Models\Review;
use App\Models\Scopes\ApprovedScope;
use Illuminate\Database\Eloquent\Builder;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestReviews extends BaseWidget
{
    protected static ?int $sort = 4;

    protected int | string | array $columnSpan = 'full';

    protected function getTableHeading(): string
    {
        return trans('widgets.reviews.latest.label');
    }

    protected function getTableQuery(): Builder
    {
        return Review::withoutGlobalScopes([ ApprovedScope::class ])->latest()->limit(50);
    }

    protected function getTableRecordUrlUsing(): Closure
    {
        return fn (Review $record): string => route('filament.resources.reviews.index');
    }

    protected function getTableColumns(): array
    {
        return [
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
        ];
    }

    protected function getTableFilters(): array
    {
        return [
            Tables\Filters\TernaryFilter::make('approved')->label(__('Approvato'))
                    ->placeholder('-')
                    ->trueLabel(__('Yes'))
                    ->falseLabel(__('No'))
        ];
    }

    protected function getTableActions(): array
    {
        return [
            // Tables\Actions\EditAction::make()
            //     ->url(fn (Review $record): string => route('filament.resources.reviews.index', ['record' => $record])),
        ];
    }
}
