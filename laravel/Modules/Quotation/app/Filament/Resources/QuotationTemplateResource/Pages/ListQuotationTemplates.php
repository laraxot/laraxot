<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\QuotationTemplateResource\Pages;

use Filament\Resources\Pages\ListRecords;
use Modules\Quotation\Filament\Resources\QuotationTemplateResource;

final class ListQuotationTemplates extends ListRecords
{
    protected static string $resource = QuotationTemplateResource::class;
}

