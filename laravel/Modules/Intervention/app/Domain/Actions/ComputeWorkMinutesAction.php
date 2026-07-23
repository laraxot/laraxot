<?php

declare(strict_types=1);

namespace Modules\Intervention\Domain\Actions;

use Modules\Intervention\Domain\ValueObjects\TimeInterval;

final class ComputeWorkMinutesAction
{
    /**
     * @param  array<int, TimeInterval>  $intervals
     */
    public function execute(array $intervals): int
    {
        $minutes = 0;

        foreach ($intervals as $interval) {
            $minutes += $interval->minutes();
        }

        return $minutes;
    }
}
