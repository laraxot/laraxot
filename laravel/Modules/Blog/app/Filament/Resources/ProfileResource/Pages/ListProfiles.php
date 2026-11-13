<?php

declare(strict_types=1);

namespace Modules\Blog\Filament\Resources\ProfileResource\Pages;

use Modules\Blog\Filament\Resources\ProfileResource;
use Modules\User\Filament\Resources\ProfileResource\Pages\BaseListProfiles;

class ListProfiles extends BaseListProfiles
{
    protected static string $resource = ProfileResource::class;
}
