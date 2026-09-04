<?php

declare(strict_types=1);

namespace Modules\Production\Filament\Resources\ProductionOrderResource\Pages;

use Modules\Production\Filament\Resources\ProductionOrderResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditProductionOrder extends XotBaseEditRecord
{
    protected static string $resource = ProductionOrderResource::class;
}
