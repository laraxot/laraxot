<?php

declare(strict_types=1);

namespace Modules\Comment\Filament\Resources\CommentResource\Pages;

use Modules\Comment\Filament\Resources\CommentResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

class ListComments extends XotBaseListRecords
{
    protected static string $resource = CommentResource::class;
}
