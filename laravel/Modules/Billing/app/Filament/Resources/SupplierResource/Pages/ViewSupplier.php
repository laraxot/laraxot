<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\SupplierResource\Pages;

use Filament\Actions\EditAction;
use Filament\Resources\Pages\ViewRecord;
use Modules\Billing\Filament\Resources\SupplierResource;

final class ViewSupplier extends ViewRecord
{
    protected static string $resource = SupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            EditAction::make(),
        ];
    }
}
