<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberEInvoiceResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Timber\Filament\Resources\TimberEInvoiceResource;

final class ListTimberEInvoices extends ListRecords
{
    protected static string $resource = TimberEInvoiceResource::class;
}
