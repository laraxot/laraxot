<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\SupplierResource\Pages;

use Filament\Actions\EditAction;
use Modules\Billing\Filament\Resources\SupplierResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewSupplier extends XotBaseViewRecord
{
    protected static string $resource = SupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'edit' => EditAction::make(),
        ];
    }
}
