<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\EmployeeResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\HR\Filament\Resources\EmployeeResource;

final class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;
}

