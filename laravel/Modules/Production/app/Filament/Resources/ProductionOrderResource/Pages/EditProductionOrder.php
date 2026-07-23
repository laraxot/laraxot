<?php

declare(strict_types=1);

namespace Modules\Production\Filament\Resources\ProductionOrderResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Production\Filament\Resources\ProductionOrderResource;

final class EditProductionOrder extends EditRecord
{
    protected static string $resource = ProductionOrderResource::class;
}

