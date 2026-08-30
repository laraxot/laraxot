<?php

declare(strict_types=1);

namespace Modules\Billing\Datas;

use DateTimeImmutable;
use Modules\Quotation\DTO\Money;

/**
 * Outstanding invoices sorted by how late they are, as of a given date.
 */
final readonly class ReceivablesAging
{
    /**
     * @param  array<string, AgingBucket>  $buckets  Keyed by bucket code.
     */
    public function __construct(
        public DateTimeImmutable $asOf,
        public array $buckets,
    ) {}

    public function total(): Money
    {
        $total = Money::zero();

        foreach ($this->buckets as $bucket) {
            $total = $total->add($bucket->total);
        }

        return $total;
    }

    /**
     * Everything past its due date — the figure worth chasing this week.
     */
    public function overdueTotal(): Money
    {
        $total = Money::zero();

        foreach ($this->buckets as $code => $bucket) {
            if ($code === AgingBuckets::NOT_DUE) {
                continue;
            }

            $total = $total->add($bucket->total);
        }

        return $total;
    }

    public function bucket(string $code): ?AgingBucket
    {
        return $this->buckets[$code] ?? null;
    }
}
