<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\SupplierResource\Pages;

use Filament\Actions\CreateAction;
use Modules\Billing\Filament\Resources\SupplierResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListSuppliers extends XotBaseListRecords
{
    protected static string $resource = SupplierResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make(),
        ];
    }
}
