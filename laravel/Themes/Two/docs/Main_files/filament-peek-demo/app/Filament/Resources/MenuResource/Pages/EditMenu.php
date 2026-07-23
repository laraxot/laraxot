<?php

namespace App\Filament\Resources\MenuResource\Pages;

use App\Filament\Resources\MenuResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

class EditMenu extends XotBaseEditRecord
{
    protected static string $resource = MenuResource::class;

    protected function getActions(): array
    {
        return [];
    }
}
