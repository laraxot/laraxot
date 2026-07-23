<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

class EditPost extends XotBaseEditRecord
{
    use HasPostPreview;

    protected static string $resource = PostResource::class;
}
