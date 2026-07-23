<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\SupplierInvoiceResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Billing\Filament\Resources\SupplierInvoiceResource;

final class CreateSupplierInvoice extends CreateRecord
{
    protected static string $resource = SupplierInvoiceResource::class;
}
