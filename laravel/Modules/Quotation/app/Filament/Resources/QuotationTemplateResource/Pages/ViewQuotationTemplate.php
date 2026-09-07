<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\QuotationTemplateResource\Pages;

use Modules\Quotation\Filament\Resources\QuotationTemplateResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseViewRecord;

final class ViewQuotationTemplate extends XotBaseViewRecord
{
    protected static string $resource = QuotationTemplateResource::class;
}
