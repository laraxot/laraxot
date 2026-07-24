<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderResource\Pages;

use Filament\Actions;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListWorkOrders extends XotBaseListRecords
{
    protected static string $resource = WorkOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => Actions\CreateAction::make(),
        ];
    }

    public static function canCreate(): bool
    {
        if (request()->is('operator/*') || request()->is('customer/*')) {
            return false;
        }

        return WorkOrderResource::canCreate();
    }
}
