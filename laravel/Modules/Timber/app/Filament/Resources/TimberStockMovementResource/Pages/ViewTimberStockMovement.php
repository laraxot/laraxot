<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberStockMovementResource\Pages;

use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;
use Modules\Timber\Filament\Resources\TimberStockMovementResource;

final class ViewTimberStockMovement extends XotBaseViewRecord
{
    protected static string $resource = TimberStockMovementResource::class;

    /** @return array<string, \Filament\Schemas\Components\Component> */
    protected function getInfolistSchema(): array
    {
        return [];
    }
}
