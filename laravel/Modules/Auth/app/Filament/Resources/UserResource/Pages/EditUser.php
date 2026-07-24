<?php

declare(strict_types=1);

namespace Modules\Auth\Filament\Resources\UserResource\Pages;

use Modules\Auth\Filament\Resources\UserResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditUser extends XotBaseEditRecord
{
    protected static string $resource = UserResource::class;
}
