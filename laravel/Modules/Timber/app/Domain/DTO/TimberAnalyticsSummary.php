<?php

declare(strict_types=1);

namespace Modules\Timber\Domain\DTO;

use Modules\Quotation\DTO\Money;

final readonly class TimberAnalyticsSummary
{
    /**
     * @param  array<int, CustomerSalesRow>  $topCustomers
     */
    public function __construct(
        public string $fromDate,
        public string $toDate,
        public Money $salesTotal,
        public Money $purchasesTotal,
        public Money $collectedTotal,
        public Money $outstandingTotal,
        public array $topCustomers,
    ) {}

    public function marginProxy(): Money
    {
        return $this->salesTotal->sub($this->purchasesTotal);
    }
}
