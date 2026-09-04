<?php

declare(strict_types=1);

namespace Modules\Production\Filament\Resources\ProductionOrderResource\Pages;

use Modules\Production\Filament\Resources\ProductionOrderResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateProductionOrder extends XotBaseCreateRecord
{
    protected static string $resource = ProductionOrderResource::class;
}
