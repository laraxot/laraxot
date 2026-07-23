<?php

declare(strict_types=1);

namespace Modules\Activity\Actions\Query;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Modules\Activity\Models\Activity;
use Modules\User\Models\User;
use Spatie\QueueableAction\QueueableAction;

/**
 * Get activity statistics, cached 5 minutes.
 */
class GetActivityStatisticsAction
{
    use QueueableAction;

    /**
     * @return array{total: int, by_type: array<string, int>, today: int, this_week: int, this_month: int}
     */
    public function execute(?User $user = null): array
    {
        $userKey = $user?->getKey();
        $cacheKeySuffix = is_scalar($userKey) ? (string) $userKey : 'global';
        $cacheKey = 'activity.statistics.'.$cacheKeySuffix;

        /** @var array{total: int, by_type: array<string, int>, today: int, this_week: int, this_month: int} $stats */
        $stats = Cache::remember($cacheKey, now()->addMinutes(5), function () use ($user): array {
            return $this->computeStatistics($user);
        });

        return $stats;
    }

    /**
     * @return array{total: int, by_type: array<string, int>, today: int, this_week: int, this_month: int}
     */
    private function computeStatistics(?User $user): array
    {
        $query = Activity::query();

        if ($user) {
            $query->where('causer_id', $user->getKey())
                ->where('causer_type', $user::class);
        }

        return [
            'total' => $query->count(),
            'by_type' => $this->countByType($query),
            'today' => $query->clone()
                ->whereDate('created_at', now()->toDateString())
                ->count(),
            'this_week' => $query->clone()
                ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
                ->count(),
            'this_month' => $query->clone()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];
    }

    /**
     * @param  Builder<Activity>  $query
     * @return array<string, int>
     */
    private function countByType(Builder $query): array
    {
        /** @var Builder<Activity> $clonedQuery */
        $clonedQuery = $query->clone();

        /** @var Collection<int, object{event: string, count: int}> $results */
        $results = $clonedQuery
            ->selectRaw('event, COUNT(*) as count')
            ->groupBy('event')
            ->get();

        /** @var array<string, int> $byType */
        $byType = $results->mapWithKeys(function (object $item): array {
            // PHPStan L10: isset() per magic attributes invece di property_exists()
            if (! isset($item->event, $item->count)) {
                return [];
            }

            return [(string) $item->event => (int) $item->count];
        })->toArray();

        return $byType;
    }
}
