<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Resources\WorkOrderResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource;

final class ListWorkOrders extends ListRecords
{
    protected static string $resource = WorkOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public static function canCreate(): bool
    {
        if (request()->is('operator/*') || request()->is('customer/*')) {
            return false;
        }

        return parent::canCreate();
    }
}

