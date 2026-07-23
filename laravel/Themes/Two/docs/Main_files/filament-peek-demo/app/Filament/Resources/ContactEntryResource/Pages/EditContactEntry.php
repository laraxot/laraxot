<?php

namespace App\Filament\Resources\ContactEntryResource\Pages;

use App\Filament\Resources\ContactEntryResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

class EditContactEntry extends XotBaseEditRecord
{
    protected static string $resource = ContactEntryResource::class;

    protected function getActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
