<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Widgets;

use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Modules\Employee\Actions\BuildTimelineVisualizationAction;
use Modules\Employee\Actions\BuildWorkHoursForRangeAction;
use Modules\Employee\Actions\ExportTimeDataAction;
use Modules\Employee\Actions\GetCurrentEmployeeDataAction;
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;
use Override;

/**
 * Weekly Time Table Widget - Replica esatta di dipendentincloud.it
 *
 * Implementa l'interfaccia complessa mostrata nell'immagine:
 * - Tabella settimanale con dipendente e summary ore
 * - Timeline visualization con fasce orarie 06:00-20:00
 * - Blocchi colorati per sessioni di lavoro
 * - Indicatori di stato (arancione "Problemi", verde completato, etc.)
 * - Navigazione settimana e export functionality
 */
class WorkHoursBoardWidget extends XotBaseSchemaWidget
{
    protected string $view = 'employee::filament.widgets.work-hours-board';

    protected static ?int $sort = 0;

    protected static ?string $maxHeight = '800px';

    // State management per navigazione settimana
    public Carbon $weekStart;

    public Carbon $weekEnd;

    public bool $showToleranceThreshold = false;

    // Dati computati dal widget
    /** @var array<string, mixed> */
    public array $weekData = [];

    /** @var array<string, mixed> */
    public array $timelineData = [];

    /** @var array<string, mixed> */
    public array $employeeInfo = [];

    /** @var array<string, mixed> */
    public array $summaryData = [];

    public function mount(): void
    {
        // Inizializza alla settimana corrente (come dipendentincloud.it)
        $this->weekStart = Carbon::now()->startOfWeek();
        $this->weekEnd = Carbon::now()->endOfWeek();

        $this->loadWidgetData();
    }

    /**
     * @return array<string, mixed>
     */
    #[Override]
    public function getFormSchema(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    public function getViewData(): array
    {
        return [
            'weekStart' => $this->weekStart,
            'weekEnd' => $this->weekEnd,
            'weekData' => $this->weekData,
            'timelineData' => $this->timelineData,
            'employeeInfo' => $this->employeeInfo,
            'summaryData' => $this->summaryData,
            'showToleranceThreshold' => $this->showToleranceThreshold,
        ];
    }

    /**
     * Carica tutti i dati del widget tramite Actions.
     */
    public function loadWidgetData(): void
    {
        /** @var int $userId */
        $userId = (int) (Auth::id() ?? 0);

        // 1. Dati base timbrature (WorkHour-based Action)
        $baseData = app(BuildWorkHoursForRangeAction::class)->execute($userId, $this->weekStart, $this->weekEnd);

        // 2. Dati timeline visualization (Action nuova)
        $this->timelineData = app(BuildTimelineVisualizationAction::class)
            ->execute($userId, $this->weekStart, $this->weekEnd);

        // 3. Info dipendente corrente
        $this->employeeInfo = app(GetCurrentEmployeeDataAction::class)->execute($userId);

        // 4. Costruisci dati settimana per tabella
        $this->weekData = $this->buildWeekTableData($baseData, $this->timelineData);

        // 5. Summary data per header tabella
        $this->summaryData = $this->buildSummaryData($baseData);
    }

    /**
     * Costruisce dati per tabella settimanale.
     *
     * @param  array<string, mixed>  $baseData
     * @param  array<string, mixed>  $timelineData
     * @return array<string, mixed>
     */
    private function buildWeekTableData(array $baseData, array $timelineData): array
    {
        $days = [];

        $currentDate = $this->weekStart->copy();
        while ($currentDate->lte($this->weekEnd)) {
            $dateKey = $currentDate->toDateString();

            // Safe access to timeline data
            $sessionBlocks = is_array($timelineData['sessionBlocks'] ?? null) ? $timelineData['sessionBlocks'] : [];
            $dayStatuses = is_array($timelineData['dayStatus'] ?? null) ? $timelineData['dayStatus'] : [];

            $dayBlocks = [];
            if (isset($sessionBlocks[$dateKey]) && is_array($sessionBlocks[$dateKey])) {
                $dayBlocks = $sessionBlocks[$dateKey];
            }
            $dayStatus = isset($dayStatuses[$dateKey]) && is_array($dayStatuses[$dateKey])
                ? $dayStatuses[$dateKey]
                : ['status' => 'no_work', 'indicator' => '', 'color' => 'gray'];

            // Calcola ore totali giorno
            $totalHours = 0;
            if (! empty($dayBlocks)) {
                /** @var array<int, float|int> $durations */
                $durations = array_values(array_map(
                    static fn (mixed $duration): float => is_numeric($duration) ? (float) $duration : 0.0,
                    array_column($dayBlocks, 'duration'),
                ));
                $totalHours = array_sum($durations);
            }

            $days[$dateKey] = [
                'date' => $currentDate->format('d'),
                'dayName' => $currentDate->translatedFormat('D'),
                'fullDate' => $currentDate->translatedFormat('dddd D MMMM'),
                'totalHours' => $totalHours,
                'status' => $dayStatus['status'] ?? 'no_work',
                'indicator' => $dayStatus['indicator'] ?? '',
                'color' => $dayStatus['color'] ?? 'gray',
                'isToday' => $currentDate->isToday(),
                'isWeekend' => $currentDate->isWeekend(),
                'sessions' => $dayBlocks,
            ];

            $currentDate = $currentDate->copy()->addDay();
        }

        return $days;
    }

    /**
     * Costruisce summary data per header tabella.
     *
     * @param  array<string, mixed>  $baseData
     * @return array<string, mixed>
     */
    private function buildSummaryData(array $baseData): array
    {
        $summary = $baseData['summary'] ?? [];

        $workedMinutes = 0;
        $addedMinutes = 0;
        $reducedMinutes = 0;
        $contractMinutes = 0;

        if (is_array($summary)) {
            $workedMinutes = (int) ($summary['workedMinutes'] ?? 0);
            $addedMinutes = (int) ($summary['addedMinutes'] ?? 0);
            $reducedMinutes = (int) ($summary['reducedMinutes'] ?? 0);
            $contractMinutes = (int) ($summary['contractMinutes'] ?? 0);
        }

        return [
            'workedHours' => $this->formatMinutesToHours($workedMinutes),
            'addedHours' => $this->formatMinutesToHours($addedMinutes),
            'reducedHours' => $this->formatMinutesToHours($reducedMinutes),
            'contractHours' => $this->formatMinutesToHours($contractMinutes),
            'hasAdded' => $addedMinutes > 0,
            'hasReduced' => $reducedMinutes > 0,
        ];
    }

    /**
     * Navigazione settimana precedente.
     */
    public function previousWeek(): void
    {
        $this->weekStart = $this->weekStart->copy()->subWeek();
        $this->weekEnd = $this->weekEnd->copy()->subWeek();
        $this->loadWidgetData();
    }

    /**
     * Navigazione settimana successiva.
     */
    public function nextWeek(): void
    {
        $this->weekStart = $this->weekStart->copy()->addWeek();
        $this->weekEnd = $this->weekEnd->copy()->addWeek();
        $this->loadWidgetData();
    }

    /**
     * Torna alla settimana corrente.
     */
    public function currentWeek(): void
    {
        $this->weekStart = Carbon::now()->startOfWeek();
        $this->weekEnd = Carbon::now()->endOfWeek();
        $this->loadWidgetData();
    }

    /**
     * Toggle soglie di tolleranza.
     */
    public function toggleToleranceThreshold(): void
    {
        $this->showToleranceThreshold = ! $this->showToleranceThreshold;
        $this->loadWidgetData(); // Ricarica con/senza soglie
    }

    /**
     * Esporta dati settimana corrente.
     */
    public function exportData(): void
    {
        /** @var int $userId */
        $userId = (int) (Auth::id() ?? 0);

        app(ExportTimeDataAction::class)
            ->onQueue('exports')
            ->execute($userId, $this->weekStart, $this->weekEnd, 'xlsx');

        Notification::make()
            ->title('Export avviato')
            ->body('Riceverai una notifica quando completato.')
            ->success()
            ->send();
    }

    /**
     * Formatta minuti in formato ore:minuti.
     */
    public function formatMinutesToHours(int $minutes): string
    {
        if ($minutes === 0) {
            return 'Nessuna'; // Come nell'immagine per "Aggiunte" e "Ridotte"
        }

        $hours = intdiv($minutes, 60);
        $mins = $minutes % 60;

        if ($mins === 0) {
            return "{$hours}h";
        }

        return "{$hours}h {$mins}m";
    }

    /**
     * Calcola posizione temporale per timeline (06:00-20:00).
     */
    public function getTimePosition(string $time): float
    {
        [$hours, $minutes] = explode(':', $time);
        $totalMinutes = (((int) $hours) * 60) + ((int) $minutes);
        $baseMinutes = 6 * 60; // 06:00
        $maxMinutes = 20 * 60; // 20:00

        return (($totalMinutes - $baseMinutes) / ($maxMinutes - $baseMinutes)) * 100;
    }

    /**
     * Ottieni classe CSS per colore sessione.
     */
    public function getSessionColorClass(string $color): string
    {
        return match ($color) {
            'green' => 'timeline-session-green',
            'orange' => 'timeline-session-orange',
            'red' => 'timeline-session-red',
            default => 'bg-gray-200 dark:bg-gray-600 border-gray-400',
        };
    }
}
