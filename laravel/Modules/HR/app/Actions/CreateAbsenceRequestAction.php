<?php

declare(strict_types=1);

namespace Modules\HR\Actions;

use Carbon\CarbonImmutable;
use Modules\HR\Models\AbsenceRequest;
use Spatie\QueueableAction\QueueableAction;

final class CreateAbsenceRequestAction
{
    use QueueableAction;

    public function execute(
        int|string $userId,
        string $type,
        CarbonImmutable $startsAt,
        CarbonImmutable $endsAt,
        ?string $notes = null,
    ): AbsenceRequest {
        /** @var AbsenceRequest $request */
        $request = AbsenceRequest::query()->create([
            'user_id' => $userId,
            'type' => $type,
            'starts_at' => $startsAt,
            'ends_at' => $endsAt,
            'notes' => $notes,
        ]);

        return $request;
    }
}
