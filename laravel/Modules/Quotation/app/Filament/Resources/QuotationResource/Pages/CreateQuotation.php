<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\QuotationResource\Pages;

use Filament\Resources\Pages\CreateRecord;
use Modules\Quotation\Filament\Resources\QuotationResource;

final class CreateQuotation extends CreateRecord
{
    protected static string $resource = QuotationResource::class;
}

