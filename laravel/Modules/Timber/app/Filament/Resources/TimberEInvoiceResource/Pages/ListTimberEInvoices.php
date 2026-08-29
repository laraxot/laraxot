<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberEInvoiceResource\Pages;

use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;
use Modules\Timber\Filament\Resources\TimberEInvoiceResource;

final class ListTimberEInvoices extends XotBaseListRecords
{
    protected static string $resource = TimberEInvoiceResource::class;
}
