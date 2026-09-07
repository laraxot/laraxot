<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\HeatingSystemResource\Pages;

use Modules\Compliance\Filament\Resources\HeatingSystemResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewHeatingSystem extends XotBaseViewRecord
{
    protected static string $resource = HeatingSystemResource::class;
}
