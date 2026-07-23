<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\QuotationTemplateResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Quotation\Filament\Resources\QuotationTemplateResource;

final class EditQuotationTemplate extends EditRecord
{
    protected static string $resource = QuotationTemplateResource::class;
}

