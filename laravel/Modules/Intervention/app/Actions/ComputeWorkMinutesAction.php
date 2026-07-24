<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Modules\Intervention\ValueObjects\TimeInterval;
use Spatie\QueueableAction\QueueableAction;

final class ComputeWorkMinutesAction
{
    use QueueableAction;

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
