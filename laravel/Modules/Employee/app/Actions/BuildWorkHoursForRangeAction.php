<?php

declare(strict_types=1);

namespace Modules\Employee\Actions;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Modules\Employee\Enums\WorkHourTypeEnum;
use Modules\Employee\Models\WorkHour;
use Spatie\QueueableAction\QueueableAction;

/**
 * Queueable action: builds work-hours data for a given user and date range.
 */
class BuildWorkHoursForRangeAction
{
    use QueueableAction;

    /**
     * Execute pairing and summarization.
     *
     * @return array{
     *  byDate: array<string, list<array{start: string, end: string|null, status: string}>>,
     *  summary: array{workedMinutes:int, addedMinutes:int, reducedMinutes:int, contractMinutes:int},
     *  contracts: array<string, int>
     * }
     */
    public function execute(int $userId, Carbon $start, Carbon $end): array
    {
        /** @var Collection<int, WorkHour> $entries */
        $entries = WorkHour::query()
            ->where('employee_id', $userId)
            ->whereBetween('timestamp', [
                (clone $start)->startOfDay(),
                (clone $end)->endOfDay(),
            ])
            ->orderBy('timestamp', 'asc')
            ->get();

        /** @var array<int, array{in: Carbon|null, out: Carbon|null, status: string}> $sessions */
        $sessions = [];

        foreach ($entries as $entry) {
            if ($entry->type === WorkHourTypeEnum::CLOCK_IN) {
                $sessions[] = [
                    'in' => $entry->timestamp,
                    'out' => null,
                    'status' => 'active',
                ];

                continue;
            }

            if ($entry->type === WorkHourTypeEnum::CLOCK_OUT) {
                $last = \count($sessions) - 1;
                if ($last >= 0 && $sessions[$last]['out'] === null) {
                    $sessions[$last]['out'] = $entry->timestamp;
                    $sessions[$last]['status'] = 'completed';
                } else {
                    $sessions[] = [
                        'in' => null,
                        'out' => $entry->timestamp,
                        'status' => 'completed',
                    ];
                }
            }
        }

        /** @var array<string, list<array{start:string,end:string|null,status:string}>> $byDate */
        $byDate = [];
        $workedMinutes = 0;

        foreach ($sessions as $s) {
            $dateKey = ($s['in'] ?? $s['out'] ?? Carbon::now())->toDateString();
            $startStr = $s['in'] ? $s['in']->format('H:i') : null;
            $endStr = $s['out'] ? $s['out']->format('H:i') : null;

            if (! isset($byDate[$dateKey])) {
                $byDate[$dateKey] = [];
            }

            $byDate[$dateKey][] = [
                'start' => $startStr ?? '--:--',
                'end' => $endStr,
                'status' => $s['status'],
            ];

            if ($s['in'] && $s['out']) {
                $workedMinutes += (int) $s['out']->diffInMinutes($s['in']);
            }
        }

        /** @var array<string, int> $contracts */
        $contracts = [];
        $cursor = (clone $start)->startOfDay();
        while ($cursor->lte($end)) {
            $contracts[$cursor->toDateString()] = 0; // Placeholder until contract logic exists
            $cursor->addDay();
        }

        return [
            'byDate' => $byDate,
            'summary' => [
                'workedMinutes' => $workedMinutes,
                'addedMinutes' => 0,
                'reducedMinutes' => 0,
                'contractMinutes' => array_sum($contracts),
            ],
            'contracts' => $contracts,
        ];
    }
}
