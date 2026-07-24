<?php

declare(strict_types=1);

namespace Modules\HR\Actions;

use Carbon\CarbonImmutable;
use Modules\HR\Models\AbsenceRequest;
use Spatie\QueueableAction\QueueableAction;

final class ApproveAbsenceRequestAction
{
    use QueueableAction;

    public function execute(AbsenceRequest $request, int $decidedByUserId, ?CarbonImmutable $decidedAt = null): AbsenceRequest
    {
        $decidedAt = $decidedAt ?? CarbonImmutable::now();

        $request->forceFill([
            'status' => 'approved',
            'decided_by_user_id' => $decidedByUserId,
            'decided_at' => $decidedAt,
        ])->save();

        return $request;
    }
}
