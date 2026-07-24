<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberStockMovementResource\Pages;

use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;
use Modules\TimberBilling\Filament\Resources\TimberStockMovementResource;

final class ListTimberStockMovements extends ListRecords
{
    protected static string $resource = TimberStockMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('Nuovo movimento'),
        ];
    }
}
