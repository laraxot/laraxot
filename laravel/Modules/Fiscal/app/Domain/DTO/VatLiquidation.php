<?php

declare(strict_types=1);

namespace Modules\Fiscal\Domain\DTO;

use Modules\Quotation\Domain\ValueObjects\Money;

final readonly class VatLiquidation
{
    /**
     * @param  array<int, VatSummaryRow>  $salesRows  IVA a debito per aliquota (vendite).
     * @param  array<int, VatSummaryRow>  $purchaseRows  IVA a credito per aliquota (acquisti).
     */
    public function __construct(
        public string $fromDate,
        public string $toDate,
        public array $salesRows,
        public array $purchaseRows,
        public Money $totalDebit,
        public Money $totalCredit,
        public Money $balance,
    ) {}

    public function isPayable(): bool
    {
        return $this->balance->asFloat() > 0;
    }
}
