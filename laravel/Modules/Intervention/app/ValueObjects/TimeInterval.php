<?php

declare(strict_types=1);

namespace Modules\Intervention\ValueObjects;

use Carbon\CarbonImmutable;
use Modules\Intervention\Exceptions\InvalidTimeInterval;

final readonly class TimeInterval
{
    public function __construct(
        public CarbonImmutable $startedAt,
        public CarbonImmutable $endedAt,
    ) {
        if ($this->endedAt->lessThan($this->startedAt)) {
            throw InvalidTimeInterval::endBeforeStart();
        }
    }

    public function minutes(): int
    {
        return intdiv($this->endedAt->getTimestamp() - $this->startedAt->getTimestamp(), 60);
    }
}
