<?php

declare(strict_types=1);

namespace Modules\Activity\Adapters;

use Modules\Activity\Actions\Query\GetSubjectActivityLogAction;
use Modules\Activity\Actions\RecordSubjectActivityAction;
<<<<<<< HEAD
use Modules\Activity\Models\Contracts\ActivityRecorderContract;
=======
use Modules\Activity\Contracts\ActivityRecorderContract;
>>>>>>> 13cf1831 (.)

/**
 * Adapter for ActivityRecorderContract — not a QueueableAction (multi-operation contract).
 */
class ActivityRecorder implements ActivityRecorderContract
{
<<<<<<< HEAD
    /**
     * @param  array<string, mixed>  $changes
     */
=======
>>>>>>> 13cf1831 (.)
    public function record(
        string $modelClass,
        int|string $modelId,
        string $action,
        array $changes = []
    ): void {
<<<<<<< HEAD
        app(RecordSubjectActivityAction::class)->execute(
            $modelClass,
            $modelId,
            $action,
            $changes,
            null,
        );
    }

    /**
     * @return array<int, array<string, mixed>>
     */
=======
        app(RecordSubjectActivityAction::class)->execute($modelClass, $modelId, $action, $changes);
    }

>>>>>>> 13cf1831 (.)
    public function getLog(string $modelClass, int|string $modelId): array
    {
        return app(GetSubjectActivityLogAction::class)->execute($modelClass, $modelId);
    }
}
