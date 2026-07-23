<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\HeatingSystemResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Compliance\Filament\Resources\HeatingSystemResource;

final class CreateHeatingSystem extends CreateRecord
{
    protected static string $resource = HeatingSystemResource::class;
}
