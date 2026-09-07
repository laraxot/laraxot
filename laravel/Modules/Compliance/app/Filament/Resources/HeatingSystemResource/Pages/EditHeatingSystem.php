<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\HeatingSystemResource\Pages;

use Modules\Compliance\Filament\Resources\HeatingSystemResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditHeatingSystem extends XotBaseEditRecord
{
    protected static string $resource = HeatingSystemResource::class;
}
