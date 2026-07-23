<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\InvoiceResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Billing\Filament\Resources\InvoiceResource;

final class EditInvoice extends EditRecord
{
    protected static string $resource = InvoiceResource::class;
}

