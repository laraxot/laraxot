<?php

declare(strict_types=1);

namespace Modules\Fiscal\Domain\DTO;

/**
 * A single taxable amount with its VAT rate, fed to the VAT liquidation
 * calculator. Verticals produce these from their own sales/purchase sources;
 * the calculator groups them by rate and derives the VAT.
 */
final readonly class VatableAmount
{
    public function __construct(
        public float $ratePercent,
        public float $taxable,
    ) {}
}
