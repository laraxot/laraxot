<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\SupplierInvoiceResource\Pages;

use Modules\Billing\Filament\Resources\SupplierInvoiceResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditSupplierInvoice extends XotBaseEditRecord
{
    protected static string $resource = SupplierInvoiceResource::class;

    protected function getHeaderActions(): array
    {
        return [
            'reconcile' => SupplierInvoiceResource::reconcileAction(),
        ];
    }
}
