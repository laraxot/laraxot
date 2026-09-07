<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\QuotationTemplateResource\Pages;

use Modules\Quotation\Filament\Resources\QuotationTemplateResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseListRecords;

final class ListQuotationTemplates extends XotBaseListRecords
{
    protected static string $resource = QuotationTemplateResource::class;
}
