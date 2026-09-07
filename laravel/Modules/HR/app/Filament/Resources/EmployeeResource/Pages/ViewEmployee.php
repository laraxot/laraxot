<?php

declare(strict_types=1);

namespace Modules\HR\Filament\Resources\EmployeeResource\Pages;

use Modules\HR\Filament\Resources\EmployeeResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewEmployee extends XotBaseViewRecord
{
    protected static string $resource = EmployeeResource::class;
}
