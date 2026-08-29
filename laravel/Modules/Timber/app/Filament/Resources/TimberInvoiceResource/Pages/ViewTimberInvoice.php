<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberInvoiceResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Timber\Filament\Resources\TimberInvoiceResource;

final class ViewTimberInvoice extends ViewRecord
{
    protected static string $resource = TimberInvoiceResource::class;
}
