<?php

namespace App\Filament\Resources\PostResource\Pages;

use App\Filament\Resources\PostResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

class CreatePost extends XotBaseCreateRecord
{
    use HasPostPreview;

    protected static string $resource = PostResource::class;
}
