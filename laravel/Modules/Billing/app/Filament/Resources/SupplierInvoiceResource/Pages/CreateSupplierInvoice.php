<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\SupplierInvoiceResource\Pages;

use Modules\Billing\Filament\Resources\SupplierInvoiceResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateSupplierInvoice extends XotBaseCreateRecord
{
    protected static string $resource = SupplierInvoiceResource::class;
}
