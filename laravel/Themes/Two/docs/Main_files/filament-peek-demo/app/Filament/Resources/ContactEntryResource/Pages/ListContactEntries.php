<?php

namespace App\Filament\Resources\ContactEntryResource\Pages;

use App\Filament\Resources\ContactEntryResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListContactEntries extends XotBaseListRecords
{
    protected static string $resource = ContactEntryResource::class;

    protected function getActions(): array
    {
        return [];
    }
}
