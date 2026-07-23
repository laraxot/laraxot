<?php

namespace App\Filament\Resources\ContactEntryResource\Pages;

use App\Filament\Resources\ContactEntryResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

class CreateContactEntry extends XotBaseCreateRecord
{
    protected static string $resource = ContactEntryResource::class;
}
