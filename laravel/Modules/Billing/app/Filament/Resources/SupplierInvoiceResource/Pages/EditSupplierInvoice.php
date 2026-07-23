<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\SupplierInvoiceResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Billing\Filament\Resources\SupplierInvoiceResource;

final class EditSupplierInvoice extends EditRecord
{
    protected static string $resource = SupplierInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            SupplierInvoiceResource::reconcileAction(),
        ];
    }
}
