<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Illuminate\Support\HtmlString;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $recordTitleAttribute = 'name';

    public static function getLabel(): string
    {
        return __('User');
    }

    public static function getPluralLabel(): string
    {
        return __('Users');
    }

    public static function getNavigationGroup(): string
    {
        return  __('Shop');
    }

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function canCreate(): bool { return false; }

    public static function canDelete(Model $record): bool { return false; }

    public static function canDeleteAny(): bool { return false; }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Toggle::make('is_admin')->label(__('Is Admin')),
                    ])
                    ->columnSpan(2),
                Forms\Components\Group::make()
                    ->schema([
                        //
                    ])->columnSpan(1),
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
                Tables\Columns\ImageColumn::make('profile_photo_url')->label(__('Profile Photo'))
                    ->rounded()
                    ->size(40)
                    ->toggleable(),
                Tables\Columns\TextColumn::make('name')->label(__('Name'))
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')->label(__('Email'))
                    ->searchable(),
                Tables\Columns\BadgeColumn::make('email_verified_at')->label(__('Email verified at'))
                    ->colors([
                        'success' => fn ($state): bool => $state !== null,
                    ])
                    ->dateTime(config('custom.datetime_format'))
                    ->toggleable(),
                Tables\Columns\TextColumn::make('orders_count')->label(__('Orders Count'))
                    ->counts('orders')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('created_at')->label(__('Created at'))
                    ->dateTime(config('custom.datetime_format'))
                    ->sortable()
                    ->toggleable(),
            ])
            ->defaultSort('created_at','desc')
            ->filters([
                Filter::make('is_admin')
                    ->query(fn (Builder $query): Builder => $query->where('is_admin', true))
                    ->label(__('Admin')),
            ])
            ->actions([
                Action::make('Email')->label(__('Email'))
                    ->color('success')
                    ->icon('heroicon-o-mail')
                    ->action(function (User $record, array $data): void {
                        $record->notify(new \App\Notifications\AdminMessage($data['subject'], $data['message']));
                        Filament::notify('success', 'Email sent');
                    })
                    ->form([
                        Forms\Components\TextInput::make('subject')
                            ->label(__('Subject'))
                            ->required(),
                        Forms\Components\RichEditor::make('message')
                            ->label(__('Message'))
                            ->required(),
                    ]),
                Tables\Actions\EditAction::make(),
            ]);
    }
    
    public static function getRelations(): array
    {
        return [
            RelationManagers\AddressesRelationManager::class,
        ];
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
                        ->with(['addresses','defaultAddress'])
                        ->withCount(['orders']);
    }
}
