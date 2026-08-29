<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberStockMovementResource\Pages;

use Filament\Actions\CreateAction;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
use Modules\Timber\Filament\Resources\TimberStockMovementResource;

final class ListTimberStockMovements extends XotBaseListRecords
{
    protected static string $resource = TimberStockMovementResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make()->label('Nuovo movimento'),
        ];
    }
}
