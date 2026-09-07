<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberStockMovementResource\Pages;

use Modules\Timber\Filament\Resources\TimberStockMovementResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewTimberStockMovement extends XotBaseViewRecord
{
    protected static string $resource = TimberStockMovementResource::class;
}
