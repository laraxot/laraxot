<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\WorkOrderChangeOrderResource\Pages;

use Filament\Actions;
use Modules\Compliance\Filament\Resources\WorkOrderChangeOrderResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListWorkOrderChangeOrders extends XotBaseListRecords
{
    protected static string $resource = WorkOrderChangeOrderResource::class;

    protected function getHeaderActions(): array
    {
        return ['create' => Actions\CreateAction::make()];
    }
}
