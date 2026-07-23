<?php

declare(strict_types=1);

namespace Modules\Employee\Filament\Widgets;

use Carbon\Carbon;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Modules\Employee\Enums\WorkHourStatusEnum;
use Modules\Employee\Enums\WorkHourTypeEnum;
use Modules\Employee\Models\WorkHour;
use Modules\Xot\Filament\Widgets\XotBaseSchemaWidget;
use Override;

/**
 * Enhanced Time Clock Widget - Primary time tracking interface with improved UI/UX.
 *
 * Features (2025 Enhanced Version):
 * - 3-column responsive layout: [Time+Date+Stats] | [Enhanced Sessions] | [Smart Action Button]
 * - Interactive session cards with status badges
 * - Action buttons with count badges
 * - Real-time statistics and duration tracking
 * - Enhanced accessibility with proper ARIA labels
 * - Native Filament components (badges, buttons, icons)
 * - Smart Clock In/Out logic with visual feedback
 * - Complete time tracking functionality
 *
 * UI/UX Improvements:
 * - Badge-based time entries display for clear visual hierarchy
 * - Color-coded session status (success, danger, warning, info, gray)
 * - Duration calculation and display
 * - Enhanced mobile responsiveness
 * - Improved information architecture
 *
 * This is the ONLY time tracking widget - consolidates all time tracking features.
 */
class TimeClockWidget extends XotBaseSchemaWidget
{
    /**
     * Vista del widget.
     */
    protected string $view = 'employee::filament.widgets.time-clock-widget';

    /**
     * Ordine di visualizzazione (primo widget).
     */
    protected static ?int $sort = 0;

    /**
     * Occupa tutta la larghezza della riga.
     */
    protected int|string|array $columnSpan = 'full';

    /**
     * Polling per aggiornamento real-time.
     */
    protected static ?string $pollingInterval = '1s';

    /**
     * Ora corrente per display.
     */
    public string $currentTime = '';

    /**
     * Data formattata in italiano.
     */
    public string $todayDate = '';

    /**
     * Today's work-hour entries.
     *
     * @var array<int, array{time: string, type: string, status: string}>
     */
    public array $todayEntries = [];

    /**
     * Current session blocks.
     *
     * @var array<int, array{start: string, end: string|null, duration: float, status: string}>
     */
    public array $sessions = [];

    /**
     * Current session state.
     */
    public bool $isClockedIn = false;

    /**
     * Descriptive session status.
     */
    public string $sessionStatus = 'not_started';

    /**
     * Inizializzazione del widget.
     */
    public function mount(): void
    {
        $this->updateData();
    }

    /**
     * Schema form (vuoto per questo widget).
     *
     * @return array<string, mixed>
     */
    #[Override]
    public function getFormSchema(): array
    {
        return [];
    }

    /**
     * Aggiorna tutti i dati del widget.
     */
    public function updateData(): void
    {
        // Aggiorna ora e data
        $this->currentTime = Carbon::now()->format('H:i');
        $carbon = Carbon::now();
        $carbon->locale('it');
        $this->todayDate = $carbon->isoFormat('dddd D MMMM YYYY');

        $userId = Auth::id();
        if ($userId === null) {
            $this->todayEntries = [];
            $this->sessions = [];

            return;
        }

        $this->loadTodayEntries((string) $userId);
        $this->updateSessionStatus();
    }

    /**
     * Load today's entries.
     */
    private function loadTodayEntries(string $userId): void
    {
        /** @var Collection<int, WorkHour> $entries */
        $entries = WorkHour::query()
            ->where('employee_id', $userId)
            ->whereDate('timestamp', today())
            ->orderBy('timestamp', 'asc')
            ->get();

        $todayEntries = [];
        foreach ($entries as $entry) {
            $todayEntries[] = [
                'time' => $entry->timestamp->format('H:i'),
                'type' => $entry->type->value,
                'status' => $entry->status->value,
            ];
        }
        $this->todayEntries = $todayEntries;
    }

    /**
     * Update current session state.
     */
    private function updateSessionStatus(): void
    {
        if (empty($this->todayEntries)) {
            $this->isClockedIn = false;
            $this->sessionStatus = 'not_started';

            return;
        }

        $lastEntry = end($this->todayEntries);
        if (is_array($lastEntry) && isset($lastEntry['type']) && is_string($lastEntry['type'])) {
            $this->isClockedIn = $lastEntry['type'] === 'clock_in';
            $this->sessionStatus = $this->isClockedIn ? 'active' : 'completed';
        } else {
            $this->isClockedIn = false;
            $this->sessionStatus = 'not_started';
        }
    }

    /**
     * Clock-in action.
     */
    public function clockIn(): void
    {
        if ($this->isClockedIn) {
            $this->notifyWarning('Sei già in sessione');

            return;
        }

        $this->createWorkHour(WorkHourTypeEnum::CLOCK_IN, 'Entrata registrata');
    }

    /**
     * Clock-out action.
     */
    public function clockOut(): void
    {
        if (! $this->isClockedIn) {
            $this->notifyWarning('Devi prima timbrare l\'entrata');

            return;
        }

        $this->createWorkHour(WorkHourTypeEnum::CLOCK_OUT, 'Uscita registrata');
    }

    /**
     * Create a work-hour entry.
     */
    private function createWorkHour(WorkHourTypeEnum $type, string $successMessage): void
    {
        $userId = Auth::id();

        WorkHour::query()->create([
            'employee_id' => $userId,
            'type' => $type,
            'timestamp' => Carbon::now(),
            'status' => WorkHourStatusEnum::PENDING,
            'notes' => $successMessage.' da dashboard widget',
        ]);

        $this->updateData();
        $this->notifySuccess($successMessage.' alle '.Carbon::now()->format('H:i'));
    }

    /**
     * Notifica di successo (DRY principle).
     */
    private function notifySuccess(string $message): void
    {
        Notification::make()
            ->title('Successo')
            ->body($message)
            ->success()
            ->send();
    }

    /**
     * Notifica di avviso (DRY principle).
     */
    private function notifyWarning(string $message): void
    {
        Notification::make()
            ->title('Attenzione')
            ->body($message)
            ->warning()
            ->send();
    }
}
