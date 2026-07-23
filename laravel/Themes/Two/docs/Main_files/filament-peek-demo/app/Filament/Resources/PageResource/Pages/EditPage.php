<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

class EditPage extends XotBaseEditRecord
{
    use HasPagePreview;

    protected static string $resource = PageResource::class;
}
