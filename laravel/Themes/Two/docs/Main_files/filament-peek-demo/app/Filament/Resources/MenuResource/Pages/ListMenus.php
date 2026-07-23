<?php

namespace App\Filament\Resources\MenuResource\Pages;

use App\Filament\Resources\MenuResource;
use Filament\Actions\CreateAction;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListMenus extends XotBaseListRecords
{
    protected static string $resource = MenuResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
