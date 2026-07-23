<?php

declare(strict_types=1);

namespace Modules\Fiscal\Domain\DTO;

/**
 * Vertical-agnostic, fully resolved snapshot of an invoice, ready to be
 * serialised to FatturaPA XML. Each vertical builds this from its own
 * persistence via a dedicated mapper; the Fiscal core never touches Eloquent.
 */
final readonly class FiscalInvoiceData
{
    /**
     * @param  array<int, FiscalInvoiceLine>  $lines
     */
    public function __construct(
        public string $number,
        public string $date,
        public FiscalSeller $seller,
        public FiscalBuyer $buyer,
        public array $lines,
    ) {}
}
