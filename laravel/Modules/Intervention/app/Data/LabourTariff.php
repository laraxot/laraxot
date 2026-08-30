<?php

declare(strict_types=1);

namespace Modules\Intervention\Data;

/**
 * Selling price of an hour of work, plus the surcharges that apply outside
 * ordinary hours.
 *
 * Deliberately distinct from `accounting.hourly_labour_cost_cents`, which is
 * what an hour *costs* the company and is used to compute margins: that number
 * must never end up on a customer document. This one is what the hour is
 * *sold* for.
 *
 * Everything defaults to zero, which switches the feature off: an install that
 * has not entered its own rate keeps the previous behaviour (labour left blank
 * for a human to fill) instead of silently pricing work at a figure nobody
 * chose.
 */
final readonly class LabourTariff
{
    public function __construct(
        public int $hourlyPriceCents = 0,
        public int $calloutFeeCents = 0,
        public int $nightPercent = 0,
        public int $nightFromHour = 20,
        public int $nightToHour = 7,
        public int $weekendPercent = 0,
    ) {}

    public function pricesLabour(): bool
    {
        return $this->hourlyPriceCents > 0;
    }

    /**
     * True when the given hour falls in the night band. The band is allowed to
     * wrap past midnight (e.g. 20 → 7), which is the normal case.
     */
    public function isNightHour(int $hour): bool
    {
        if ($this->nightPercent <= 0) {
            return false;
        }

        if ($this->nightFromHour === $this->nightToHour) {
            return false;
        }

        return $this->nightFromHour < $this->nightToHour
            ? $hour >= $this->nightFromHour && $hour < $this->nightToHour
            : $hour >= $this->nightFromHour || $hour < $this->nightToHour;
    }
}
