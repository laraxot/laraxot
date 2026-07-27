<?php

declare(strict_types=1);

namespace Modules\Employee\Actions;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Modules\Employee\Enums\WorkHourTypeEnum;
use Modules\Employee\Models\WorkHour;
use Spatie\QueueableAction\QueueableAction;

/**
 * Build timeline visualization data for weekly time table widget.
 *
 * Replicates the complex timeline visualization shown in dipendentincloud.it
 * with time slots, session blocks, and visual indicators.
 */
class BuildTimelineVisualizationAction
{
    use QueueableAction;

    /**
     * Execute timeline visualization building.
     *
     * @return array{
     *   timeSlots: array<string, string>,
     *   sessionBlocks: array<string, array<int, array{
     *     start: string,
     *     end: string|null,
     *     duration: float,
     *     color: string,
     *     status: string,
     *     position: array{top: int, height: int}
     *   }>>,
     *   workingHoursBounds: array{start: string, end: string},
     *   dayStatus: array<string, array{status: string, indicator: string, color: string, dayName: string, isToday: bool, isWeekend: bool}>
     * }
     */
    public function execute(int $userId, Carbon $weekStart, Carbon $weekEnd): array
    {
        // 1. Costruisci slot temporali (06:00-20:00 come nell'immagine)
        $timeSlots = $this->buildTimeSlots();

        // 2. Ottieni tutte le timbrature della settimana
        $entries = $this->getWeekEntries($userId, $weekStart, $weekEnd);

        // 3. Costruisci blocchi sessioni per ogni giorno
        $sessionBlocks = $this->buildSessionBlocks($entries, $weekStart, $weekEnd);

        // 4. Determina stati giorni (come nell'immagine: arancione "Problemi", verde OK, etc.)
        $dayStatus = $this->buildDayStatus($sessionBlocks, $weekStart, $weekEnd);

        return [
            'timeSlots' => $timeSlots,
            'sessionBlocks' => $sessionBlocks,
            'workingHoursBounds' => ['start' => '08:00', 'end' => '18:00'],
            'dayStatus' => $dayStatus,
        ];
    }

    /**
     * Costruisce slot temporali per timeline (06:00-20:00).
     *
     * @return array<string, string>
     */
    private function buildTimeSlots(): array
    {
        $slots = [];

        // Fasce orarie come nell'immagine: 06:00, 08:00, 10:00, 12:00, 14:00, 16:00, 18:00, 20:00
        for ($hour = 6; $hour <= 20; $hour += 2) {
            $timeStr = sprintf('%02d:00', $hour);
            $slots[$timeStr] = $timeStr;
        }

        return $slots;
    }

    /**
     * Ottieni tutte le timbrature della settimana.
     *
     * @return Collection<int, WorkHour>
     */
    private function getWeekEntries(int $userId, Carbon $weekStart, Carbon $weekEnd): Collection
    {
        return WorkHour::query()
            ->where('employee_id', $userId)
            ->whereBetween('timestamp', [
                $weekStart->startOfDay(),
                $weekEnd->endOfDay(),
            ])
            ->orderBy('timestamp', 'asc')
            ->get();
    }

    /**
     * Costruisce blocchi sessioni per visualizzazione timeline.
     *
     * @param  Collection<int, WorkHour>  $entries
     * @return array<string, array<int, array{start: string, end: string|null, duration: float, color: string, status: string, position: array{top: int, height: int}}>>
     */
    private function buildSessionBlocks(Collection $entries, Carbon $weekStart, Carbon $weekEnd): array
    {
        $blocks = [];

        // Itera per ogni giorno della settimana
        $current = $weekStart->copy();
        while ($current->lte($weekEnd)) {
            $dateKey = $current->toDateString();
            $dayEntries = $entries->filter(fn ($entry) => $entry->timestamp->isSameDay($current));

            $blocks[$dateKey] = $this->buildDaySessionBlocks($dayEntries);

            $current->addDay();
        }

        return $blocks;
    }

    /**
     * Costruisce blocchi sessioni per un singolo giorno.
     *
     * @param  Collection<int, WorkHour>  $dayEntries
     * @return array<int, array{start: string, end: string|null, duration: float, color: string, status: string, position: array{top: int, height: int}}>
     */
    private function buildDaySessionBlocks(Collection $dayEntries): array
    {
        $blocks = [];
        $currentSession = null;

        foreach ($dayEntries as $entry) {
            switch ($entry->type) {
                case WorkHourTypeEnum::CLOCK_IN:
                    // Chiudi sessione precedente se aperta
                    if ($currentSession) {
                        $blocks[] = $this->finalizeSessionBlock($currentSession);
                    }

                    // Inizia nuova sessione
                    $currentSession = [
                        'start' => $entry->timestamp->format('H:i'),
                        'startTime' => $entry->timestamp,
                        'end' => null,
                        'endTime' => null,
                        'color' => 'orange', // Sessione in corso
                        'status' => 'active',
                    ];
                    break;

                case WorkHourTypeEnum::CLOCK_OUT:
                    if ($currentSession) {
                        $currentSession['end'] = $entry->timestamp->format('H:i');
                        $currentSession['endTime'] = $entry->timestamp;
                        $currentSession['color'] = $this->determineSessionColor($currentSession);
                        $currentSession['status'] = 'completed';

                        $blocks[] = $this->finalizeSessionBlock($currentSession);
                        $currentSession = null;
                    }
                    break;
            }
        }

        // Aggiungi sessione corrente se ancora aperta
        if ($currentSession) {
            $blocks[] = $this->finalizeSessionBlock($currentSession);
        }

        return $blocks;
    }

    /**
     * Finalizza un blocco sessione con posizione e durata.
     *
     * @param  array{start: string, startTime: Carbon, end: string|null, endTime: Carbon|null, color: string, status: string}  $session
     * @return array{start: string, end: string|null, duration: float, color: string, status: string, position: array{top: int, height: int}}
     */
    private function finalizeSessionBlock(array $session): array
    {
        $startTime = $session['startTime'];
        $endTime = $session['endTime'] ?? Carbon::now();

        // Calcola posizione nel timeline (06:00-20:00 = 840 minuti totali)
        $timelineStart = Carbon::today()->setTime(6, 0); // 06:00
        $timelineEnd = Carbon::today()->setTime(20, 0); // 20:00
        $totalMinutes = $timelineStart->diffInMinutes($timelineEnd); // 840 minuti

        // Posizione inizio (percentuale da 06:00)
        $startMinutesFromBase = max(0, $timelineStart->diffInMinutes($startTime));
        $topPercent = ($startMinutesFromBase / $totalMinutes) * 100;

        // Altezza blocco (durata sessione)
        $sessionMinutes = $startTime->diffInMinutes($endTime);
        $heightPercent = ($sessionMinutes / $totalMinutes) * 100;

        return [
            'start' => $session['start'],
            'end' => $session['end'],
            'duration' => round($sessionMinutes / 60, 2),
            'color' => $session['color'],
            'status' => $session['status'],
            'position' => [
                'top' => (int) round($topPercent),
                'height' => (int) round($heightPercent),
            ],
        ];
    }

    /**
     * Determina colore sessione basato su orari e durata.
     *
     * @param  array{start: string, startTime: Carbon, end: string|null, endTime: Carbon|null, color: string, status: string}  $session
     */
    private function determineSessionColor(array $session): string
    {
        $startTime = $session['startTime'];
        $endTime = $session['endTime'];

        if (! $endTime) {
            return 'orange'; // Sessione in corso
        }

        // Verde per sessioni mattina (08:00-13:00)
        if ($startTime->hour >= 8 && $startTime->hour < 13) {
            return 'green';
        }

        // Arancione per sessioni pomeriggio (13:00-18:00)
        if ($startTime->hour >= 13 && $startTime->hour < 18) {
            return 'orange';
        }

        // Rosso per orari anomali
        return 'red';
    }

    /**
     * Costruisce stati giorni con indicatori.
     *
     * @param  array<string, array<int, array{start: string, end: string|null, duration: float, color: string, status: string, position: array{top: int, height: int}}>>  $sessionBlocks
     * @return array<string, array{status: string, indicator: string, color: string, dayName: string, isToday: bool, isWeekend: bool}>
     */
    private function buildDayStatus(array $sessionBlocks, Carbon $weekStart, Carbon $weekEnd): array
    {
        $dayStatus = [];

        $current = $weekStart->copy();
        while ($current->lte($weekEnd)) {
            $dateKey = $current->toDateString();
            $blocks = $sessionBlocks[$dateKey] ?? [];

            $status = $this->determineDayStatus($blocks, $current);

            $dayStatus[$dateKey] = [
                'status' => $status['status'],
                'indicator' => $status['indicator'],
                'color' => $status['color'],
                'dayName' => $current->copy()->translatedFormat('D d'),
                'isToday' => $current->isToday(),
                'isWeekend' => $current->isWeekend(),
            ];

            $current->addDay();
        }

        return $dayStatus;
    }

    /**
     * Determina stato di un giorno basato sulle sessioni.
     *
     * @param  array<int, array{start: string, end: string|null, duration: float, color: string, status: string, position: array{top: int, height: int}}>  $blocks
     * @return array{status: string, indicator: string, color: string}
     */
    private function determineDayStatus(array $blocks, Carbon $date): array
    {
        if (empty($blocks)) {
            return [
                'status' => 'no_work',
                'indicator' => '',
                'color' => 'gray',
            ];
        }

        // Verifica sessioni attive
        $hasActiveSession = collect($blocks)->contains('status', 'active');
        if ($hasActiveSession) {
            return [
                'status' => 'in_progress',
                'indicator' => '●', // Dot indicator
                'color' => 'orange',
            ];
        }

        // Verifica problemi (come nell'immagine "Problemi")
        $hasProblems = $this->detectProblems($blocks, $date);
        if ($hasProblems) {
            return [
                'status' => 'problems',
                'indicator' => '✕', // X indicator come nell'immagine
                'color' => 'red',
            ];
        }

        // Calcola ore totali
        $totalHours = collect($blocks)->sum('duration');

        if ($totalHours >= 8) {
            return [
                'status' => 'completed',
                'indicator' => '✓',
                'color' => 'green',
            ];
        } elseif ($totalHours > 0) {
            return [
                'status' => 'partial',
                'indicator' => '○',
                'color' => 'yellow',
            ];
        }

        return [
            'status' => 'incomplete',
            'indicator' => '',
            'color' => 'gray',
        ];
    }

    /**
     * Rileva problemi nelle timbrature (timbrature incomplete, orari anomali, etc.).
     *
     * @param  array<int, array{start: string, end: string|null, duration: float, color: string, status: string, position: array{top: int, height: int}}>  $blocks
     */
    private function detectProblems(array $blocks, Carbon $date): bool
    {
        foreach ($blocks as $block) {
            // Type check for proper array structure
            if (! is_array($block)) {
                continue;
            }

            // Sessione senza uscita
            if (
                isset($block['status']) &&
                    is_string($block['status']) &&
                    $block['status'] === 'active' &&
                    ! $date->isToday()
            ) {
                return true;
            }

            // Sessioni troppo lunghe (>12 ore)
            if (isset($block['duration']) && is_float($block['duration']) && $block['duration'] > 12) {
                return true;
            }

            // Orari anomali (prima delle 06:00 o dopo le 22:00)
            if (isset($block['startTime']) && $block['startTime'] instanceof Carbon) {
                $hour = $block['startTime']->hour;
                if ($hour < 6 || $hour > 22) {
                    return true;
                }
            }
        }

        return false;
    }
}
