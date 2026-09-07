<?php

declare(strict_types=1);

namespace Modules\Production\Filament\Resources\ProductionOrderResource\Pages;

use Modules\Production\Filament\Resources\ProductionOrderResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewProductionOrder extends XotBaseViewRecord
{
    protected static string $resource = ProductionOrderResource::class;
}
