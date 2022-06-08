<?php

namespace App\Filament\Resources\OrderResource\Pages;

use Filament\Facades\Filament;
use Filament\Pages\Actions\Action;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\OrderResource;
use Filament\Forms\Components\RichEditor;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;
}
