<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Carbon\CarbonImmutable;
use Modules\Intervention\Exceptions\NoOpenTimePunchToStop;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\TimePunch;
use Spatie\QueueableAction\QueueableAction;

final class StopTimePunchAction
{
    use QueueableAction;

    public function execute(Intervention $intervention, int $userId, ?CarbonImmutable $endedAt = null): TimePunch
    {
        $endedAt = $endedAt ?? CarbonImmutable::now();

        /** @var TimePunch|null $punch */
        $punch = TimePunch::query()
            ->where('intervention_id', $intervention->id)
            ->where('user_id', $userId)
            ->whereNull('ended_at')
            ->first();

        if ($punch === null) {
            throw NoOpenTimePunchToStop::forUserAndIntervention($userId, $intervention->id);
        }

        $punch->forceFill(['ended_at' => $endedAt])->save();

        $intervention->forceFill(['ended_at' => $endedAt])->save();

        return $punch;
    }
}
