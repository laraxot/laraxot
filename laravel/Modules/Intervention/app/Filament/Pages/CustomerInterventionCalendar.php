<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Pages;

use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Modules\Intervention\Filament\Concerns\InterventionCalendarColors;
use Modules\Intervention\Models\Intervention;

final class CustomerInterventionCalendar extends Page
{
    use InterventionCalendarColors;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $slug = 'intervention-calendar';

    protected string $view = 'interventions::filament.pages.customer-intervention-calendar';

    /** @var array<int, array<string, mixed>> */
    public array $selectedIntervention = [];

    public bool $showModal = false;

    public static function shouldRegisterNavigation(): bool
    {
        return request()->is('customer/*');
    }

    public static function canAccess(): bool
    {
        return request()->is('customer/*');
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Cliente';
    }

    public static function getNavigationLabel(): string
    {
        return 'Calendario interventi';
    }

    public function getTitle(): string
    {
        return 'Calendario interventi';
    }

    public function getHeading(): string
    {
        return 'Calendario interventi';
    }

    /** @return array<int, array<string, mixed>> */
    public function getCalendarEvents(string $start, string $end): array
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $customerId = is_object($user) ? (int) ($user->customer_id ?? 0) : 0;

        if ($customerId <= 0) {
            return [];
        }

        /** @var array<int, array<string, mixed>> $events */
        $events = Intervention::query()
            ->with(['workOrder.customer', 'workOrder.site', 'performedBy'])
            ->whereBetween('scheduled_at', [$start, $end])
            ->whereNotNull('scheduled_at')
            ->whereHas('workOrder', fn ($q) => $q->where('customer_id', $customerId))
            ->get()
            ->map(function (Intervention $intervention): array {
                $operatorId = (int) $intervention->performed_by_user_id;
                $bgColor = self::operatorColorFor($operatorId);
                $textColor = self::eventTextColor($bgColor);
                $borderColor = self::eventBorderColor($intervention->outcome_code);

                $operatorName = $intervention->performedBy?->name ?? '—';
                $customerName = $intervention->workOrder?->customer?->legal_name ?? '—';
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
                    'start' => $intervention->scheduled_at?->toIso8601String(),
                    'end' => $intervention->ended_at?->toIso8601String(),
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
            })
            ->values()
            ->all();

        return $events;
    }

    public function openInterventionDetail(int $id): void
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $customerId = is_object($user) ? (int) ($user->customer_id ?? 0) : 0;
        if ($customerId <= 0) {
            return;
        }

        $intervention = Intervention::query()
            ->with(['workOrder.customer', 'workOrder.site', 'performedBy'])
            ->whereHas('workOrder', fn ($q) => $q->where('customer_id', $customerId))
            ->find($id);

        if ($intervention === null) {
            return;
        }

        $operatorId = (int) $intervention->performed_by_user_id;

        $this->selectedIntervention = [
            'id' => $intervention->id,
            'work_order_title' => $intervention->workOrder?->title ?? '—',
            'customer' => $intervention->workOrder?->customer?->legal_name ?? '—',
            'site' => $intervention->workOrder?->site?->name ?? '',
            'operator' => $intervention->performedBy?->name ?? '—',
            'operator_color' => self::operatorColorFor($operatorId),
            'scheduled_at' => $intervention->scheduled_at?->format('d/m/Y H:i') ?? '—',
            'started_at' => $intervention->started_at?->format('d/m/Y H:i') ?? '—',
            'ended_at' => $intervention->ended_at?->format('d/m/Y H:i') ?? '—',
            'notes' => $intervention->notes ?? '',
            'outcome' => $intervention->outcome_code ?? '',
            'amount' => ($intervention->total_amount ?? $intervention->amount) !== null
                ? number_format((float) ($intervention->total_amount ?? $intervention->amount), 2, ',', '.').' €'
                : null,
        ];

        $this->showModal = true;
    }

    public function closeModal(): void
    {
        $this->showModal = false;
        $this->selectedIntervention = [];
    }

    /** @return array<int, array<string, string>> */
    public function getOperatorLegend(): array
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();
        $customerId = is_object($user) ? (int) ($user->customer_id ?? 0) : 0;

        if ($customerId <= 0) {
            return [];
        }

        return Intervention::query()
            ->with('performedBy')
            ->whereNotNull('scheduled_at')
            ->whereHas('workOrder', fn ($q) => $q->where('customer_id', $customerId))
            ->select('performed_by_user_id')
            ->distinct()
            ->get()
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
}
