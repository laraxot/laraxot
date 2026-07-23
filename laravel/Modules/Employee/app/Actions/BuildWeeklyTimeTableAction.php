<?php

declare(strict_types=1);

namespace Modules\Employee\Actions;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Modules\Employee\Enums\WorkHourTypeEnum;
use Modules\Employee\Models\WorkHour;
use Spatie\QueueableAction\QueueableAction;

/**
 * Build weekly time table data replicating dipendentincloud.it interface.
 *
 * Creates a structured table view with days of week and time entries
 * matching the original dipendentincloud.it layout and functionality.
 */
class BuildWeeklyTimeTableAction
{
    use QueueableAction;

    /**
     * Execute weekly table building.
     *
     * @return array<string, mixed>
     */
    public function execute(int $userId, Carbon $start, Carbon $end): array
    {
        /** @var Collection<int, WorkHour> $entries */
        $entries = WorkHour::query()
            ->where('employee_id', $userId)
            ->whereBetween('timestamp', [
                $start->copy()->startOfDay(),
                $end->copy()->endOfDay(),
            ])
            ->orderBy('timestamp', 'asc')
            ->get();

        $days = [];
        $weekSummary = [
            'totalWorked' => 0.0,
            'totalContract' => 0.0,
            'totalVariance' => 0.0,
            'averageDaily' => 0.0,
        ];

        // Costruisci dati per ogni giorno della settimana
        $currentDate = $start->copy()->startOfDay();
        while ($currentDate->lte($end)) {
            $dateKey = $currentDate->toDateString();

            // Filtra entries per questo giorno
            $dayEntries = $entries->filter(fn ($entry) => $entry->timestamp->isSameDay($currentDate));

            // Costruisci sessioni per questo giorno
            $sessions = $this->buildDaySessions($dayEntries);

            // Calcola ore lavorate
            $workedHours = $this->calculateDayHours($sessions);
            $contractHours = $this->getContractHours($currentDate); // 8 ore standard
            $variance = $workedHours - $contractHours;

            // Determina stato giorno
            /** @var array<int, array{time: string, type: string, status: string, duration?: float}> $typedSessions */
            $typedSessions = array_map(fn (array $session): array => [
                'time' => $session['time'],
                'type' => $session['type'],
                'status' => $session['status'],
                'duration' => isset($session['duration']) &&
                (is_float($session['duration']) || is_int($session['duration']))
                    ? ((float) $session['duration'])
                    : null,
            ], $sessions);
            $typedSessions = array_filter($typedSessions, fn (array $session) => isset($session['duration'])
                ? is_float($session['duration'])
                : true);
            $dayStatus = $this->determineDayStatus($typedSessions, $workedHours, $contractHours);

            $days[$dateKey] = [
                'date' => $currentDate->format('d/m'),
                'dayName' => $currentDate->translatedFormat('D'),
                'fullDate' => $currentDate->translatedFormat('d/m/Y'),
                'entries' => array_map(fn (array $session): array => [
                    'time' => $session['time'],
                    'type' => $session['type'],
                    'status' => $session['status'],
                ], $sessions),
                'totalHours' => $workedHours,
                'contractHours' => $contractHours,
                'variance' => $variance,
                'status' => $dayStatus,
                'isToday' => $currentDate->isToday(),
                'isWeekend' => $currentDate->isWeekend(),
            ];

            // Aggiungi al summary settimanale
            $weekSummary['totalWorked'] += $workedHours;
            $weekSummary['totalContract'] += $contractHours;
            $weekSummary['totalVariance'] += $variance;

            $currentDate = $currentDate->copy()->addDay();
        }

        // Calcola media giornaliera
        $workDays = collect($days)->filter(fn ($day) => ! $day['isWeekend'])->count();
        $weekSummary['averageDaily'] = $workDays > 0 ? round($weekSummary['totalWorked'] / $workDays, 2) : 0;

        return [
            'days' => $days,
            'weekSummary' => $weekSummary,
        ];
    }

    /**
     * Costruisce sessioni per un singolo giorno.
     *
     * @param  Collection<int, WorkHour>  $dayEntries
     * @return array<int, array<string, mixed>>
     */
    private function buildDaySessions(Collection $dayEntries): array
    {
        $sessions = [];
        $currentSession = null;

        foreach ($dayEntries as $entry) {
            switch ($entry->type) {
                case WorkHourTypeEnum::CLOCK_IN:
                    // Chiudi sessione precedente se aperta
                    if ($currentSession !== null) {
                        $sessions[] = $currentSession;
                    }

                    // Inizia nuova sessione
                    $currentSession = [
                        'time' => $entry->timestamp->format('H:i'),
                        'type' => 'session_start',
                        'status' => 'active',
                        'start' => $entry->timestamp,
                        'end' => null,
                        'duration' => null,
                    ];
                    break;

                case WorkHourTypeEnum::CLOCK_OUT:
                    if ($currentSession !== null && isset($currentSession['start'])) {
                        $currentSession['end'] = $entry->timestamp;
                        $currentSession['status'] = 'completed';
                        $startTime = $currentSession['start'];
                        if ($startTime instanceof Carbon) {
                            $currentSession['duration'] = $startTime->diffInHours($entry->timestamp, true);
                        }
                        $sessions[] = $currentSession;
                        $currentSession = null;
                    }
                    break;

                case WorkHourTypeEnum::BREAK_START:
                    // Aggiungi marker pausa
                    $sessions[] = [
                        'time' => $entry->timestamp->format('H:i'),
                        'type' => 'break_start',
                        'status' => 'break',
                        'start' => null,
                        'end' => null,
                        'duration' => null,
                    ];
                    break;

                case WorkHourTypeEnum::BREAK_END:
                    // Aggiungi marker fine pausa
                    $sessions[] = [
                        'time' => $entry->timestamp->format('H:i'),
                        'type' => 'break_end',
                        'status' => 'working',
                        'start' => null,
                        'end' => null,
                        'duration' => null,
                    ];
                    break;
            }
        }

        // Aggiungi sessione corrente se ancora aperta
        if ($currentSession !== null) {
            $sessions[] = $currentSession;
        }

        return $sessions;
    }

    /**
     * Calcola ore lavorate in un giorno.
     *
     * @param  array<int, array<string, mixed>>  $sessions
     */
    private function calculateDayHours(array $sessions): float
    {
        $totalHours = 0.0;

        foreach ($sessions as $session) {
            if (isset($session['duration']) && is_float($session['duration'])) {
                $totalHours += $session['duration'];
            }
        }

        return round($totalHours, 2);
    }

    /**
     * Ottieni ore contrattuali per un giorno.
     */
    private function getContractHours(Carbon $date): float
    {
        // Weekend = 0 ore, giorni feriali = 8 ore standard
        return $date->isWeekend() ? 0.0 : 8.0;
    }

    /**
     * Determina stato del giorno.
     *
     * @param  array<int, array<string, mixed>>  $sessions
     */
    private function determineDayStatus(array $sessions, float $workedHours, float $contractHours): string
    {
        if (empty($sessions)) {
            return 'no_entries';
        }

        // Verifica se ci sono sessioni aperte
        $hasActiveSession = collect($sessions)->contains('status', 'active');
        if ($hasActiveSession) {
            return 'in_progress';
        }

        // Confronta ore lavorate vs contrattuali
        if ($workedHours >= $contractHours) {
            return 'completed';
        } elseif ($workedHours > 0) {
            return 'partial';
        }

        return 'no_work';
    }
}
