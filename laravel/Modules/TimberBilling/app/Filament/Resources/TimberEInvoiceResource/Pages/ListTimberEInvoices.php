<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Resources\TimberEInvoiceResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\TimberBilling\Filament\Resources\TimberEInvoiceResource;

final class ListTimberEInvoices extends ListRecords
{
    protected static string $resource = TimberEInvoiceResource::class;
}
