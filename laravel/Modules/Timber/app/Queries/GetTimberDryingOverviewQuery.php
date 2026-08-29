<?php

declare(strict_types=1);

namespace Modules\Timber\Queries;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Modules\Timber\Models\TimberDryingCycle;

final class GetTimberDryingOverviewQuery
{
    /**
     * @return Collection<int, TimberDryingCycle>
     */
    public function execute(string $statusCode = 'in_progress'): Collection
    {
        /** @var Collection<int, TimberDryingCycle> $cycles */
        $cycles = TimberDryingCycle::query()
            ->with(['dryable', 'humidityChecks' => fn (Relation $query) => $query->latest('checked_at')])
            ->where('status_code', $statusCode)
            ->latest('entered_at')
            ->get();

        return $cycles;
    }
}
