<?php

declare(strict_types=1);

namespace Modules\Billing\Filament\Resources\InvoiceResource\Pages;

use Modules\Billing\Filament\Resources\InvoiceResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseEditRecord;

final class EditInvoice extends XotBaseEditRecord
{
    protected static string $resource = InvoiceResource::class;
}
