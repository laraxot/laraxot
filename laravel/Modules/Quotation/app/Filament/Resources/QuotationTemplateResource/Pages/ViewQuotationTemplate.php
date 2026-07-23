<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\QuotationTemplateResource\Pages;

use Filament\Resources\Pages\ViewRecord;
use Modules\Quotation\Filament\Resources\QuotationTemplateResource;

final class ViewQuotationTemplate extends ViewRecord
{
    protected static string $resource = QuotationTemplateResource::class;
}

