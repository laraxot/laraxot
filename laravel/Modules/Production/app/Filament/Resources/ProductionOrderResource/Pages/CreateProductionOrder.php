<?php

declare(strict_types=1);

namespace Modules\Production\Filament\Resources\ProductionOrderResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Production\Filament\Resources\ProductionOrderResource;

final class CreateProductionOrder extends CreateRecord
{
    protected static string $resource = ProductionOrderResource::class;
}

