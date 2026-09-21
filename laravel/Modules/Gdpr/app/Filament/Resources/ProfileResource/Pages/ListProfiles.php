<?php

declare(strict_types=1);

namespace Modules\Gdpr\Filament\Resources\ProfileResource\Pages;

use Modules\Gdpr\Filament\Resources\ProfileResource;
use Modules\User\Filament\Resources\BaseProfileResource\Pages\ListProfiles as UserListProfiles;

class ListProfiles extends UserListProfiles
{
    protected static string $resource = ProfileResource::class;
}
