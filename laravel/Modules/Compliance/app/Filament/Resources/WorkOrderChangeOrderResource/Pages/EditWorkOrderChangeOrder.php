<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\WorkOrderChangeOrderResource\Pages;

use Modules\Compliance\Filament\Resources\WorkOrderChangeOrderResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditWorkOrderChangeOrder extends XotBaseEditRecord
{
    protected static string $resource = WorkOrderChangeOrderResource::class;
}
