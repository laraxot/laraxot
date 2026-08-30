<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderStatusResource\Pages;

use Modules\WorkOrder\Filament\Resources\WorkOrderStatusResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateWorkOrderStatus extends XotBaseCreateRecord
{
    protected static string $resource = WorkOrderStatusResource::class;
}
