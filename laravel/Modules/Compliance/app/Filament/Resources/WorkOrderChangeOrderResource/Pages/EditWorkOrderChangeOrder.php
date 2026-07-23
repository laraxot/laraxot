<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\WorkOrderChangeOrderResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Compliance\Filament\Resources\WorkOrderChangeOrderResource;

final class EditWorkOrderChangeOrder extends EditRecord
{
    protected static string $resource = WorkOrderChangeOrderResource::class;
}
