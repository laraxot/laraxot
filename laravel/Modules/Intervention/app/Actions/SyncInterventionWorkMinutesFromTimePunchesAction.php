<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Carbon\CarbonImmutable;
use Modules\Intervention\Domain\Actions\ComputeWorkMinutesAction;
use Modules\Intervention\Domain\ValueObjects\TimeInterval;
use Modules\Intervention\Models\Intervention;

final readonly class SyncInterventionWorkMinutesFromTimePunchesAction
{
    public function __construct(
        private ComputeWorkMinutesAction $computeWorkMinutes,
    ) {}

    public function execute(Intervention $intervention): Intervention
    {
        $intervals = [];

        foreach ($intervention->timePunches()->whereNotNull('ended_at')->get() as $timePunch) {
            /** @var CarbonImmutable $startedAt */
            $startedAt = CarbonImmutable::parse($timePunch->started_at);

            /** @var CarbonImmutable $endedAt */
            $endedAt = CarbonImmutable::parse($timePunch->ended_at);

            $intervals[] = new TimeInterval($startedAt, $endedAt);
        }

        $minutes = max(0, $this->computeWorkMinutes->execute($intervals));
        $intervention->work_minutes = $minutes;
        $intervention->save();

        return $intervention;
    }
}
