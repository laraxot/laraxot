<?php

declare(strict_types=1);

namespace Modules\Blog\DataObjects;

use Spatie\LaravelData\Data;

class ArticleImportMetrics extends Data
{
    public function __construct(
        public readonly int $wagerableFlag = 0,
        public readonly ArticleImportScores $scores = new ArticleImportScores(),
        public readonly int $wagersCount = 0,
        public readonly int $wagersCountCanonical = 0,
        public readonly int $wagersCountTotal = 0,

        /** @var array<mixed> */
        public readonly array $wagers = [],
        public readonly float $volumePlayMoney = 0.0,
        public readonly float $volumeRealMoney = 0.0,
    ) {
    }

    public function isWagerable(): bool
    {
        return 1 === $this->wagerableFlag;
    }
}
