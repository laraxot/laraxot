<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\SupplierInvoiceResource\Pages;

use Filament\Actions\CreateAction;
use Modules\Billing\Filament\Resources\SupplierInvoiceResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListSupplierInvoices extends XotBaseListRecords
{
    protected static string $resource = SupplierInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'create' => CreateAction::make(),
        ];
    }
}
