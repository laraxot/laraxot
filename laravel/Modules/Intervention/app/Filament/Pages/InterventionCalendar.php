<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Pages;

use App\Models\User;
use App\Support\AccountFeatures;
use Carbon\CarbonImmutable;
use Filament\Actions\Action;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\Intervention\GoogleCalendar\Actions\BuildGoogleOverlayEventsAction;
use Modules\Intervention\Filament\Concerns\InterventionCalendarColors;
use Modules\Intervention\Filament\Concerns\ResolvesCalendarReturnUrl;
use Modules\Intervention\Filament\Resources\InterventionResource;
use Modules\Intervention\Models\Intervention;

final class InterventionCalendar extends Page
{
    use InterventionCalendarColors;
    use ResolvesCalendarReturnUrl;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $slug = 'intervention-calendar';

    protected string $view = 'interventions::filament.pages.intervention-calendar';

    /** @var array<int, array<string, mixed>> */
    public array $selectedIntervention = [];

    public bool $showModal = false;

    public static function shouldRegisterNavigation(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        $panelId = filament()->getCurrentPanel()?->getId();

        if ($panelId === null) {
            return true;
        }

        return match ($panelId) {
            'operator' => AccountFeatures::hasFeature($user, 'operator.intervention_calendar', true),
            'admin' => AccountFeatures::hasFeature($user, 'admin.intervention_calendar', true),
            default => false,
        };
    }

    public static function canAccess(): bool
    {
        /** @var User|null $user */
        $user = Auth::user();
        $panelId = filament()->getCurrentPanel()?->getId();

        if ($panelId === null) {
            return true;
        }

        return match ($panelId) {
            'operator' => AccountFeatures::hasFeature($user, 'operator.intervention_calendar', true),
            'admin' => AccountFeatures::hasFeature($user, 'admin.intervention_calendar', true),
            default => false,
        };
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('interventions.navigation');
    }

    public static function getNavigationLabel(): string
    {
        return __('interventions.calendar');
    }

    public function getTitle(): string
    {
        return __('interventions.calendar');
    }

    public function getHeading(): string
    {
        return __('interventions.calendar');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('createIntervention')
                ->label('Nuovo intervento')
                ->icon('heroicon-o-plus')
                ->visible(fn (): bool => $this->canCreateIntervention())
                ->url(fn (): string => $this->getCreateInterventionUrl()),
        ];
    }

    public function getCreateInterventionUrl(
        ?string $selectedAt = null,
        bool $allDay = true,
        ?string $calendarView = null,
        ?string $calendarDate = null,
    ): string {
        if (! $this->canCreateIntervention()) {
            return '';
        }

        $scheduledAt = $selectedAt !== null && trim($selectedAt) !== ''
            ? CarbonImmutable::parse($selectedAt)
            : CarbonImmutable::now();

        if ($this->usesDateOnlyScheduling()) {
            $scheduledAt = $scheduledAt->startOfDay()->setTime(8, 0);
        } elseif ($allDay) {
            $scheduledAt = $scheduledAt->setTime(8, 0);
        }

        return InterventionResource::getUrl('create', [
            'scheduled_at' => $scheduledAt->format('Y-m-d H:i:s'),
            'return_to' => $this->buildCalendarReturnUrl($calendarView, $calendarDate),
        ]);
    }

    public function getViewInterventionUrl(
        int $interventionId,
        ?string $calendarView = null,
        ?string $calendarDate = null,
    ): string {
        return InterventionResource::getUrl('view', [
            'record' => $interventionId,
            'return_to' => $this->buildCalendarReturnUrl($calendarView, $calendarDate),
        ]);
    }

    /**
     * Restituisce gli eventi per FullCalendar nel range richiesto.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getCalendarEvents(string $start, string $end): array
    {
        $query = Intervention::query()
            ->with(['workOrder.customer', 'workOrder.site', 'customer', 'performedBy', 'assignedUsers'])
            ->whereBetween('scheduled_at', [$start, $end])
            ->whereNotNull('scheduled_at');

        $this->applyCurrentPanelVisibility($query);

        /** @var array<int, array<string, mixed>> $events */
        $events = $query->get()->map(function (Intervention $intervention): array {
            $operatorId = (int) $intervention->performed_by_user_id;
            $bgColor = self::operatorColorFor($operatorId);
            $textColor = self::eventTextColor($bgColor);
            $scheduledAt = $intervention->scheduled_at;
            $visualEndAt = $intervention->ended_at;

            if ($scheduledAt !== null && $visualEndAt === null) {
                $visualEndAt = $scheduledAt->copy()->addHour();
            }

            $borderColor = self::eventBorderColor($intervention->outcome_code);

            $operatorName = $intervention->assignedUsers->pluck('name')->filter()->implode(', ');
            if ($operatorName === '') {
                $operatorName = $intervention->performedBy?->name ?? '—';
            }
            $customerName = $intervention->workOrder?->customer?->legal_name
                ?? $intervention->customer?->legal_name
                ?? '—';
            $siteName = $intervention->workOrder?->site?->name ?? '';
            $title = $intervention->workOrder?->title ?? '—';

            $labelParts = array_filter([
                $title !== '—' ? $title : null,
                $customerName !== '—' ? $customerName : null,
                $siteName !== '' ? $siteName : null,
            ]);
            $eventLabel = $labelParts !== [] ? implode(' · ', $labelParts) : '—';

            return [
                'id' => $intervention->id,
                'title' => $eventLabel,
                'start' => $scheduledAt?->toIso8601String(),
                'end' => $visualEndAt?->toIso8601String(),
                'backgroundColor' => $bgColor,
                'borderColor' => $borderColor,
                'textColor' => $textColor,
                'borderWidth' => 3,
                'extendedProps' => [
                    'operator_id' => $operatorId,
                    'operator_name' => $operatorName,
                    'customer' => $customerName,
                    'site' => $siteName,
                    'work_order_title' => $title,
                    'notes' => $intervention->notes ?? '',
                    'outcome' => $intervention->outcome_code ?? '',
                    'amount' => ($intervention->total_amount ?? $intervention->amount) !== null
                        ? number_format((float) ($intervention->total_amount ?? $intervention->amount), 2, ',', '.').' €'
                        : null,
                    'started_at' => $intervention->started_at?->format('d/m/Y H:i'),
                    'ended_at' => $intervention->ended_at?->format('d/m/Y H:i'),
                    'scheduled_at' => $intervention->scheduled_at?->format('d/m/Y H:i'),
                    'color' => $bgColor,
                    'text_color' => $textColor,
                ],
            ];
        })->values()->all();

        // Overlay the current user's Google Calendar events (two-way sync).
        $user = Auth::user();
        if ($user instanceof User) {
            $overlay = app(BuildGoogleOverlayEventsAction::class)->execute(
                $user,
                CarbonImmutable::parse($start),
                CarbonImmutable::parse($end),
            );
            $events = [...$events, ...$overlay];
        }

        return $events;
    }

    /**
     * Apre il modale con i dettagli dell'intervento selezionato.
     */
    public function openInterventionDetail(int $id, ?string $calendarView = null, ?string $calendarDate = null): void
    {
        $query = Intervention::query()
            ->with(['workOrder.customer', 'workOrder.site', 'customer', 'performedBy', 'assignedUsers']);

        $this->applyCurrentPanelVisibility($query);

        $intervention = $query->find($id);

        if ($intervention === null) {
            return;
        }

        $operatorId = (int) $intervention->performed_by_user_id;

        $this->selectedIntervention = [
            'id' => $intervention->id,
            'work_order_title' => $intervention->workOrder?->title ?? '—',
            'customer' => $intervention->workOrder?->customer?->legal_name
                ?? $intervention->customer?->legal_name
                ?? '—',
            'site' => $intervention->workOrder?->site?->name ?? '',
            'operator' => ($intervention->assignedUsers->pluck('name')->filter()->implode(', ')) ?: ($intervention->performedBy?->name ?? '—'),
            'operator_color' => self::operatorColorFor($operatorId),
            'scheduled_at' => $intervention->scheduled_at?->format('d/m/Y H:i') ?? '—',
            'started_at' => $intervention->started_at?->format('d/m/Y H:i') ?? '—',
            'ended_at' => $intervention->ended_at?->format('d/m/Y H:i') ?? '—',
            'notes' => $intervention->notes ?? '',
            'outcome' => $intervention->outcome_code ?? '',
            'amount' => ($intervention->total_amount ?? $intervention->amount) !== null
                ? number_format((float) ($intervention->total_amount ?? $intervention->amount), 2, ',', '.').' €'
                : null,
            'view_url' => $this->getViewInterventionUrl($intervention->id, $calendarView, $calendarDate),
        ];

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedIntervention = [];
    }

    /**
     * Restituisce la lista operatori con i loro colori per la legenda.
     *
     * @return array<int, array<string, string>>
     */
    public function getOperatorLegend(): array
    {
        $query = Intervention::query()
            ->with('performedBy')
            ->whereNotNull('scheduled_at')
            ->select('performed_by_user_id')
            ->distinct();

        $this->applyCurrentPanelVisibility($query);

        return $query->get()
            ->map(function (Intervention $i): array {
                $operatorId = (int) $i->performed_by_user_id;

                return [
                    'id' => (string) $operatorId,
                    'name' => $i->performedBy?->name ?? "Operatore #{$operatorId}",
                    'color' => self::operatorColorFor($operatorId),
                ];
            })
            ->unique('id')
            ->values()
            ->all();
    }

    private function canCreateIntervention(): bool
    {
        $user = Auth::user();
        $panelId = filament()->getCurrentPanel()?->getId();

        if (! $user instanceof \App\Models\User || ! $user->can('Create:Intervention')) {
            return false;
        }

        if ($panelId === 'operator' || request()->is('operator/*')) {
            return AccountFeatures::hasFeature($user, 'operator.create_intervention_from_calendar', false);
        }

        return true;
    }

    private function usesDateOnlyScheduling(): bool
    {
        $user = Auth::user();

        return $user instanceof \App\Models\User
            && AccountFeatures::hasAssignedPerimeter($user)
            && AccountFeatures::hasAccountFeature($user, 'operator.force_self_assignment');
    }

    private function applyCurrentPanelVisibility(Builder $query): void
    {
        $panelId = filament()->getCurrentPanel()?->getId();

        if ($panelId === null) {
            return;
        }

        $user = Auth::user();

        if (! $user instanceof \App\Models\User) {
            $query->whereRaw('1=0');

            return;
        }

        if ($panelId === 'operator' && ! (bool) config('app.demo_mode')) {
            $query->where(function (Builder $innerQuery) use ($user): void {
                $innerQuery
                    ->where('performed_by_user_id', $user->id)
                    ->orWhereExists(function ($subQuery) use ($user): void {
                        $subQuery->selectRaw('1')
                            ->from('intervention_user_assignments')
                            ->whereColumn('intervention_user_assignments.intervention_id', 'interventions.id')
                            ->where('intervention_user_assignments.user_id', $user->id);
                    });
            });

            return;
        }

        if (
            $panelId === 'admin'
            && AccountFeatures::hasAssignedPerimeter($user)
            && AccountFeatures::hasFeature($user, 'admin.team_interventions', true)
        ) {
            AccountFeatures::scopeAssignedInterventions($query, $user);
        }
    }
}
