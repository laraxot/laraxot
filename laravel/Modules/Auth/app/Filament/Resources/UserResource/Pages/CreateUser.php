<?php

declare(strict_types=1);

namespace Modules\Auth\Filament\Resources\UserResource\Pages;

use Modules\Auth\Filament\Resources\UserResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateUser extends XotBaseCreateRecord
{
    protected static string $resource = UserResource::class;
}
