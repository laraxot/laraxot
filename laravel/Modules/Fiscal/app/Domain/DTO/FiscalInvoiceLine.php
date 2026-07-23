<?php

declare(strict_types=1);

namespace Modules\Fiscal\Domain\DTO;

/**
 * A single e-invoice line, carrying both the display description and the
 * numeric data needed to recompute the VAT summary (DatiRiepilogo).
 */
final readonly class FiscalInvoiceLine
{
    public function __construct(
        public string $description,
        public float $quantity,
        public float $unitPrice,
        public float $discountPercent,
        public float $vatRatePercent,
    ) {}
}
