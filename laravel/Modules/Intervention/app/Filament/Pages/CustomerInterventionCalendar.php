<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Pages;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\Intervention\Filament\Concerns\InterventionCalendarColors;
use Modules\Intervention\Models\Intervention;
use Modules\Platform\Filament\Pages\GatedXotBasePage;
use Modules\User\Models\User;
use Modules\WorkOrder\Models\WorkOrder;

final class CustomerInterventionCalendar extends GatedXotBasePage
{
    use InterventionCalendarColors;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-calendar';

    protected static ?string $slug = 'intervention-calendar';

    protected string $view = 'intervention::filament.pages.customer-intervention-calendar';

    /** @var array<string, mixed> */
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

    public static function getNavigationGroup(): string
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
        /** @var User|null $user */
        $user = Auth::user();
        $customerId = $user instanceof User ? (int) ($user->customer_id ?? 0) : 0;

        if ($customerId <= 0) {
            return [];
        }

        /** @var array<int, array<string, mixed>> $events */
        $events = Intervention::query()
            ->with(['workOrder.customer', 'workOrder.site', 'performedBy'])
            ->whereBetween('scheduled_at', [$start, $end])
            ->whereNotNull('scheduled_at')
            ->whereHas('workOrder', fn (Builder $q) => $q->where('customer_id', $customerId))
            ->get()
            ->map(function (Intervention $intervention): array {
                $operatorId = (string) $intervention->performed_by_user_id;
                $bgColor = self::operatorColorFor($operatorId);
                $textColor = self::eventTextColor($bgColor);
                $borderColor = self::eventBorderColor($intervention->outcome_code);

                $workOrder = $intervention->workOrder;
                $performedBy = $intervention->performedBy;
                $operatorName = $performedBy !== null ? ($performedBy->name ?? '—') : '—';
                $customerName = self::customerName($workOrder, null) ?? '—';
                $siteName = self::siteName($workOrder);
                $title = self::workOrderTitle($workOrder);

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
        /** @var User|null $user */
        $user = Auth::user();
        $customerId = is_object($user) ? (int) ($user->customer_id ?? 0) : 0;
        if ($customerId <= 0) {
            return;
        }

        $intervention = Intervention::query()
            ->with(['workOrder.customer', 'workOrder.site', 'performedBy'])
            ->whereHas('workOrder', fn (Builder $q) => $q->where('customer_id', $customerId))
            ->find($id);

        if ($intervention === null) {
            return;
        }

        $operatorId = (string) $intervention->performed_by_user_id;
        $workOrder = $intervention->workOrder;
        $performedBy = $intervention->performedBy;

        $this->selectedIntervention = [
            'id' => $intervention->id,
            'work_order_title' => self::workOrderTitle($workOrder),
            'customer' => self::customerName($workOrder, null) ?? '—',
            'site' => self::siteName($workOrder),
            'operator' => $performedBy !== null ? ($performedBy->name ?? '—') : '—',
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
        /** @var User|null $user */
        $user = Auth::user();
        $customerId = is_object($user) ? (int) ($user->customer_id ?? 0) : 0;

        if ($customerId <= 0) {
            return [];
        }

        return Intervention::query()
            ->with('performedBy')
            ->whereNotNull('scheduled_at')
            ->whereHas('workOrder', fn (Builder $q) => $q->where('customer_id', $customerId))
            ->select('performed_by_user_id')
            ->distinct()
            ->get()
            ->map(function (Intervention $i): array {
                $operatorId = (string) $i->performed_by_user_id;
                $performedBy = $i->performedBy;

                return [
                    'id' => (string) $operatorId,
                    'name' => $performedBy !== null ? ($performedBy->name ?? "Operatore #{$operatorId}") : "Operatore #{$operatorId}",
                    'color' => self::operatorColorFor($operatorId),
                ];
            })
            ->unique('id')
            ->values()
            ->all();
    }

    private static function workOrderTitle(?WorkOrder $workOrder): string
    {
        return $workOrder !== null ? (string) ($workOrder->title ?? '—') : '—';
    }

    private static function customerName(?WorkOrder $workOrder, ?Customer $customer): ?string
    {
        if ($workOrder !== null && $workOrder->customer !== null) {
            return $workOrder->customer->legal_name;
        }

        return $customer?->legal_name;
    }

    private static function siteName(?WorkOrder $workOrder): string
    {
        if ($workOrder === null || ! $workOrder->site instanceof CustomerSite) {
            return '';
        }

        return (string) ($workOrder->site->name ?? '');
    }
}
