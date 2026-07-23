<?php

declare(strict_types=1);

namespace Modules\Blog\DataObjects;

use Spatie\LaravelData\Data;

class ArticleImportScores extends Data
{
    public function __construct(
        public readonly float $brierScore = 0.0,
        public readonly float $brierScorePlayMoney = 0.0,
        public readonly float $brierScoreRealMoney = 0.0,
    ) {
    }
}
