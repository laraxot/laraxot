<?php

declare(strict_types=1);

namespace Modules\Production\Filament\Resources\ProductionOrderResource\Pages;

use Filament\Actions;
use Modules\Production\Filament\Resources\ProductionOrderResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListProductionOrders extends XotBaseListRecords
{
    protected static string $resource = ProductionOrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => Actions\CreateAction::make(),
        ];
    }
}
