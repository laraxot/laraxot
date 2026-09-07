<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Resources\QuotationResource\Pages;

use Modules\Quotation\Filament\Resources\QuotationResource;
use Modules\Xot\Filament\Resources\Pages\XotBaseCreateRecord;

final class CreateQuotation extends XotBaseCreateRecord
{
    protected static string $resource = QuotationResource::class;
}
