<?php

declare(strict_types=1);

namespace Modules\Compliance\Filament\Resources\WorkOrderChangeOrderResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\Compliance\Filament\Resources\WorkOrderChangeOrderResource;

final class ListWorkOrderChangeOrders extends ListRecords
{
    protected static string $resource = WorkOrderChangeOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [Actions\CreateAction::make()];
    }
}
