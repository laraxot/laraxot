<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberInvoiceResource\Pages;

use Modules\Timber\Filament\Resources\TimberInvoiceResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewTimberInvoice extends XotBaseViewRecord
{
    protected static string $resource = TimberInvoiceResource::class;
}
