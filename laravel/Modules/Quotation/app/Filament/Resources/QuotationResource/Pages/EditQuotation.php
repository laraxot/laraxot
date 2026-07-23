<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\QuotationResource\Pages;

use Filament\Resources\Pages\EditRecord;
use Modules\Quotation\Filament\Resources\QuotationResource;

final class EditQuotation extends EditRecord
{
    protected static string $resource = QuotationResource::class;
}

