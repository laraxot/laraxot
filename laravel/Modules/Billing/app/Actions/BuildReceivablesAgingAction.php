<?php

declare(strict_types=1);

namespace Modules\Billing\Actions;

use DateTimeImmutable;
use Modules\Billing\Datas\AgingBucket;
use Modules\Billing\Datas\AgingBuckets;
use Modules\Billing\Datas\OpenItem;
use Modules\Billing\Datas\ReceivablesAging;
use Modules\Quotation\DTO\Money;

/**
 * Groups outstanding invoices by how late they are.
 *
 * Every bucket is present in the result even when empty, so a report never
 * changes shape between runs and a missing band cannot be mistaken for a band
 * that happened to be clean.
 */
final class BuildReceivablesAgingAction
{
    /**
     * @param  list<OpenItem>  $items
     */
    public function execute(DateTimeImmutable $asOf, array $items): ReceivablesAging
    {
        /** @var array<string, list<OpenItem>> $grouped */
        $grouped = array_fill_keys(AgingBuckets::all(), []);

        foreach ($items as $item) {
            $code = AgingBuckets::forDaysOverdue($item->daysOverdue($asOf));
            $grouped[$code][] = $item;
        }

        $buckets = [];
        foreach ($grouped as $code => $bucketItems) {
            $buckets[$code] = new AgingBucket(
                code: $code,
                total: $this->sum($bucketItems),
                items: $this->oldestFirst($bucketItems),
            );
        }

        return new ReceivablesAging(asOf: $asOf, buckets: $buckets);
    }

    /**
     * @param  list<OpenItem>  $items
     */
    private function sum(array $items): Money
    {
        $total = Money::zero();

        foreach ($items as $item) {
            $total = $total->add($item->outstanding);
        }

        return $total;
    }

    /**
     * @param  list<OpenItem>  $items
     * @return list<OpenItem>
     */
    private function oldestFirst(array $items): array
    {
        usort(
            $items,
            static fn (OpenItem $a, OpenItem $b): int => $a->dueOn <=> $b->dueOn,
        );

        return $items;
    }
}
