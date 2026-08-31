<?php

declare(strict_types=1);

namespace Modules\Employee\Actions;

use Carbon\CarbonInterface;
use Illuminate\Support\Carbon;
use Modules\Employee\Models\AbsenceRequest;
use Spatie\QueueableAction\QueueableAction;

/**
 * Rejects a pending absence request.
 */
class RejectAbsenceRequestAction
{
    use QueueableAction;

    public function execute(AbsenceRequest $request, int|string $decidedByUserId, ?CarbonInterface $decidedAt = null): AbsenceRequest
    {
        $request->forceFill([
            'status' => AbsenceRequest::STATUS_REJECTED,
            'decided_by_user_id' => $decidedByUserId,
            'decided_at' => $decidedAt ?? Carbon::now(),
        ])->save();

        return $request;
    }
}
