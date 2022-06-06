<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Table;
use Filament\Facades\Filament;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Fieldset;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\RichEditor;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Contracts\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function canCreate(): bool { return false; }

    public static function canDelete(Model $record): bool { return false; }

    public static function canDeleteAny(): bool { return false; }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Toggle::make('is_admin'),
                Fieldset::make('Default Address')
                    ->relationship('defaultAddress')
                    ->schema([
                        RichEditor::make('label')
                            ->disableLabel(true),
                    ])
                    ->visibleOn(Pages\ViewUser::class),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('profile_photo_url')
                    ->rounded()
                    ->size(40),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->searchable(),
                BadgeColumn::make('email_verified_at')
                    ->colors([
                        'success' => fn ($state): bool => $state !== null,
                    ])
                    ->dateTime(),
                TextColumn::make('orders_count')->counts('orders'),
                //send email
            ])
            ->filters([
                Filter::make('is_admin')
                    ->query(fn (Builder $query): Builder => $query->where('is_admin', true))
                    ->label('Admin'),
            ])
            ->prependActions([
                Action::make('Email')
                    ->color('success')
                    ->icon('heroicon-o-mail')
                    ->action(function (Model $record, array $data): void {
                        $record->notify(new \App\Notifications\AdminMessage($data['subject'], $data['message']));
                        Filament::notify('success', 'Email sent');
                    })
                    ->form([
                        Forms\Components\TextInput::make('subject')
                            ->label('Subject')
                            ->required(),
                        Forms\Components\RichEditor::make('message')
                            ->label('Message')
                            ->required(),
                    ]),
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
}
