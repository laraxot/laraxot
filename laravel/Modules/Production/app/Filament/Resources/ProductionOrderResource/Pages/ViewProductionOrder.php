<?php

declare(strict_types=1);

namespace Modules\Production\Filament\Resources\ProductionOrderResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Production\Filament\Resources\ProductionOrderResource;

final class ViewProductionOrder extends ViewRecord
{
    protected static string $resource = ProductionOrderResource::class;
}

