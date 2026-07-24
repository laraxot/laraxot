<?php

declare(strict_types=1);

namespace Modules\Auth\Filament\Resources\UserResource\Pages;

use Modules\Auth\Filament\Resources\UserResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListUsers extends XotBaseListRecords
{
    protected static string $resource = UserResource::class;
}
