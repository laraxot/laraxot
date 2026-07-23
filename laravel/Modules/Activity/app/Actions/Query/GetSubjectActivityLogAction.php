<?php

declare(strict_types=1);

namespace Modules\Activity\Actions\Query;

use Modules\Activity\Models\Activity;
use Spatie\QueueableAction\QueueableAction;

/**
 * Get activity log entries for a subject identified by class and id.
 */
class GetSubjectActivityLogAction
{
    use QueueableAction;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function execute(string $modelClass, int|string $modelId, int $limit = 500): array
    {
        /** @var array<int, array<string, mixed>> */
        return Activity::query()
            ->where('subject_type', $modelClass)
            ->where('subject_id', $modelId)
            ->latest()
            ->limit($limit)
            ->get()
            ->map(fn (Activity $activity): array => $activity->toArray())
            ->values()
            ->all();
    }
}
