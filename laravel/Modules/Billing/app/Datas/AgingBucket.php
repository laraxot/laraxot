<?php

declare(strict_types=1);

namespace Modules\Billing\Datas;

use Modules\Quotation\DTO\Money;

/**
 * One age band of an ageing report, with the items that fell into it.
 */
final readonly class AgingBucket
{
    /**
     * @param  list<OpenItem>  $items
     */
    public function __construct(
        public string $code,
        public Money $total,
        public array $items,
    ) {}

    public function count(): int
    {
        return count($this->items);
    }

    public function isEmpty(): bool
    {
        return $this->items === [];
    }
}
