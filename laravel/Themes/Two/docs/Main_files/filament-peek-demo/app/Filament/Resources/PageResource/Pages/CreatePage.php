<?php

namespace App\Filament\Resources\PageResource\Pages;

use App\Filament\Resources\PageResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

class CreatePage extends XotBaseCreateRecord
{
    use HasPagePreview;

    protected static string $resource = PageResource::class;
}
