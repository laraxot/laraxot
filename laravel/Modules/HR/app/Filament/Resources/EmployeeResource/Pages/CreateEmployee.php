<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\EmployeeResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\HR\Filament\Resources\EmployeeResource;

final class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;
}

