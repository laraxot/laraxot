<?php

declare(strict_types=1);

namespace Modules\Fiscal\Datas;

use Modules\Quotation\DTO\Money;

final readonly class InvoiceTotals
{
    /**
     * @param  array<int, Money>  $lineTotals  Net (discounted) total per line, keyed by line index.
     * @param  array<string, Money>  $vatByRate  VAT amount grouped by rate percent (as string key).
     * @param  array<string, Money>  $taxableByRate  Taxable amount grouped by rate percent (as string key).
     */
    public function __construct(
        public Money $taxable,
        public Money $vat,
        public Money $total,
        public array $lineTotals,
        public array $vatByRate,
        public array $taxableByRate,
    ) {}
}
