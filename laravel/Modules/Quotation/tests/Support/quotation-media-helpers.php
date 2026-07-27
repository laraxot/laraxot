<?php

declare(strict_types=1);

use Modules\Quotation\Actions\AddQuotationAttachmentAction;
use Modules\Quotation\Models\Quotation;

function attachQuotationDocument(Quotation $quotation, string $path, string $disk = 'local', ?string $title = null): void
{
    app(AddQuotationAttachmentAction::class)->execute($quotation, $path, $disk, null, $title);
}
