<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\InvoiceResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Billing\Filament\Resources\InvoiceResource;

final class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;
}

