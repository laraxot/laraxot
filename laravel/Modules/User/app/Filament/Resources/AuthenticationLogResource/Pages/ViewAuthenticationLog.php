<?php

declare(strict_types=1);

namespace Modules\User\Filament\Resources\AuthenticationLogResource\Pages;

use Modules\User\Filament\Resources\AuthenticationLogResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

use function Safe\json_encode;

class ViewAuthenticationLog extends XotBaseViewRecord
{
    protected static string $resource = AuthenticationLogResource::class;
}
