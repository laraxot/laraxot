<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\QuotationTemplateResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Quotation\Filament\Resources\QuotationTemplateResource;

final class CreateQuotationTemplate extends CreateRecord
{
    protected static string $resource = QuotationTemplateResource::class;
}

