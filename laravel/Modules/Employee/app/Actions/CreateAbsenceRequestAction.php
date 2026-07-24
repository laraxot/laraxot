<?php

declare(strict_types=1);

namespace Modules\Employee\Actions;

use Carbon\CarbonInterface;
use Modules\Employee\Models\AbsenceRequest;
use Spatie\QueueableAction\QueueableAction;

/**
 * Creates a new pending absence request for a user.
 */
class CreateAbsenceRequestAction
{
    use QueueableAction;

    public function execute(
        int $userId,
        string $type,
        CarbonInterface $startsAt,
        CarbonInterface $endsAt,
        ?string $notes = null,
    ): AbsenceRequest {
        /** @var AbsenceRequest $request */
        $request = AbsenceRequest::query()->create([
            'user_id' => $userId,
            'type' => $type,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'notes' => $notes,
            'status' => AbsenceRequest::STATUS_PENDING,
        ]);

        return $request;
    }
}
