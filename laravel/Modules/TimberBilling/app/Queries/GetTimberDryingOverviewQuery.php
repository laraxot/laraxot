<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Queries;

use Illuminate\Database\Eloquent\Collection;
use Modules\TimberBilling\Models\TimberDryingCycle;

final class GetTimberDryingOverviewQuery
{
    /**
     * @return Collection<int, TimberDryingCycle>
     */
    public function execute(string $statusCode = 'in_progress'): Collection
    {
        /** @var Collection<int, TimberDryingCycle> $cycles */
        $cycles = TimberDryingCycle::query()
            ->with(['dryable', 'humidityChecks' => fn ($query) => $query->latest('checked_at')])
            ->where('status_code', $statusCode)
            ->latest('entered_at')
            ->get();

        return $cycles;
    }
}
