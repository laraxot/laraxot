<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Carbon\CarbonImmutable;
use Modules\Intervention\Exceptions\OpenTimePunchAlreadyExists;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\TimePunch;
use Spatie\QueueableAction\QueueableAction;

final class StartTimePunchAction
{
    use QueueableAction;

    public function execute(Intervention $intervention, int $userId, ?CarbonImmutable $startedAt = null): TimePunch
    {
        $startedAt = $startedAt ?? CarbonImmutable::now();

        $hasOpenPunch = TimePunch::query()
            ->where('user_id', $userId)
            ->whereNull('ended_at')
            ->exists();

        if ($hasOpenPunch) {
            throw OpenTimePunchAlreadyExists::forUser($userId);
        }

        if ($intervention->started_at === null) {
            $intervention->forceFill(['started_at' => $startedAt])->save();
        }

        /** @var TimePunch $punch */
        $punch = TimePunch::query()->create([
            'intervention_id' => $intervention->id,
            'user_id' => $userId,
            'started_at' => $startedAt,
            'ended_at' => null,
        ]);

        return $punch;
    }
}
