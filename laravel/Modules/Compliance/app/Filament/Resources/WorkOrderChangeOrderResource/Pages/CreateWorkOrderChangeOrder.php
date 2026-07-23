<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\WorkOrderChangeOrderResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Compliance\Filament\Resources\WorkOrderChangeOrderResource;

final class CreateWorkOrderChangeOrder extends CreateRecord
{
    protected static string $resource = WorkOrderChangeOrderResource::class;
}
