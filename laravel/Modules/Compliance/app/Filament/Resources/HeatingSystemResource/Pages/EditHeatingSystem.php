<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\HeatingSystemResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Compliance\Filament\Resources\HeatingSystemResource;

final class EditHeatingSystem extends EditRecord
{
    protected static string $resource = HeatingSystemResource::class;
}
