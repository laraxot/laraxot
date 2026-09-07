<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Resources\TimberEInvoiceResource\Schemas;

use Modules\Timber\Filament\Resources\TimberEInvoiceResource;
use Modules\Xot\Filament\Resources\Schemas\XotBaseResourceForm;

/**
 * Le fatture elettroniche Timber non sono editabili: il form rimane vuoto
 * perche' la risorsa serve solo a consultare, trasmettere e scaricare.
 */
final class TimberEInvoiceForm extends XotBaseResourceForm
{
    public function getFormSchema(): array
    {
        return [];
    }

    protected static string $resource = TimberEInvoiceResource::class;
}
