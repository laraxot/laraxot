<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\EmployeeResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\HR\Filament\Resources\EmployeeResource;

final class ViewEmployee extends ViewRecord
{
    protected static string $resource = EmployeeResource::class;
}

