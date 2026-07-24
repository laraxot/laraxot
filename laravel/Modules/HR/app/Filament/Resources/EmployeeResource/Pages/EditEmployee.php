<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\EmployeeResource\Pages;

use Modules\HR\Filament\Resources\EmployeeResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditEmployee extends XotBaseEditRecord
{
    protected static string $resource = EmployeeResource::class;
}
