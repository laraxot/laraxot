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
 * - Responsive layout (auto-fit, 1 colonna su telefono): [Time+Date] | [Smart Action Button] | [Sessions]
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
        // Mobile, rete lenta: la vista si aggiorna ogni 15 s e il telefono puo' essere rimasto
        // indietro (altro dispositivo, tab in background). `isClockedIn` e' una proprieta'
        // pubblica tenuta dal client: va riletta dal DB prima di decidere, altrimenti un doppio
        // tocco o una vista vecchia creano due entrate.
        $this->updateData();

        if ($this->isClockedIn) {
            $this->notifyWarning('already_clocked_in');

            return;
        }

        $this->createWorkHour(WorkHourTypeEnum::CLOCK_IN, 'Entrata registrata', 'clock_in_success');
    }

    /**
     * Clock-out action.
     */
    public function clockOut(): void
    {
        $this->updateData();

        if (! $this->isClockedIn) {
            $this->notifyWarning('must_clock_in_first');

            return;
        }

        $this->createWorkHour(WorkHourTypeEnum::CLOCK_OUT, 'Uscita registrata', 'clock_out_success');
    }

    /**
     * Create a work-hour entry.
     *
     * `$notes` e' il testo salvato in `work_hours.notes` (invariato); `$successKey` e' la chiave
     * `employee::widgets.time_clock.notifications.*` mostrata nel toast.
     */
    private function createWorkHour(WorkHourTypeEnum $type, string $notes, string $successKey): void
    {
        $userId = Auth::id();
        $now = Carbon::now();

        WorkHour::query()->create([
            'employee_id' => $userId,
            'type' => $type,
            'timestamp' => $now,
            'status' => WorkHourStatusEnum::PENDING,
            'notes' => $notes.' da dashboard widget',
        ]);

        $this->updateData();
        $this->notifySuccess($successKey, $now->format('H:i'));
    }

    /**
     * Toast di successo: solo titolo, una riga leggibile su schermo stretto.
     */
    private function notifySuccess(string $key, string $time): void
    {
        Notification::make()
            ->title((string) __('employee::widgets.time_clock.notifications.'.$key, ['time' => $time]))
            ->success()
            ->send();
    }

    /**
     * Toast di avviso: solo titolo, una riga leggibile su schermo stretto.
     */
    private function notifyWarning(string $key): void
    {
        Notification::make()
            ->title((string) __('employee::widgets.time_clock.notifications.'.$key))
            ->warning()
            ->send();
    }
}
