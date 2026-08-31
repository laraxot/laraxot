<?php

declare(strict_types=1);

namespace Modules\Platform\Filament\Widgets;

use App\Support\AccountFeatures;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\HR\Models\AbsenceRequest;
use Modules\Intervention\Models\Intervention;
use Modules\Platform\Filament\Widgets\Concerns\ResolvesPanelUserScope;
use Modules\WorkOrder\Models\WorkOrder;

final class OperationsOverviewStats extends BaseWidget
{
    use ResolvesPanelUserScope;

    protected static ?int $sort = 1;

    public static function canView(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.dashboard',
            operatorFeature: 'operator.dashboard',
            adminDefault: true,
            operatorDefault: true,
            fallback: false,
        );
    }

    /** @return array<int, Stat> */
    protected function getStats(): array
    {
        $monthStart = now()->startOfMonth();
        $now = now();
        $userId = $this->currentUserId();

        $interventionsThisMonth = (clone $this->scopedInterventionsQuery($userId))
            ->whereBetween('created_at', [$monthStart, $now])
            ->count();

        $workMinutesThisMonth = (int) (clone $this->scopedInterventionsQuery($userId))
            ->whereBetween('created_at', [$monthStart, $now])
            ->whereRaw('work_minutes > 0')
            ->sum('work_minutes');

        $pendingAbsenceRequests = (clone $this->scopedAbsenceRequestsQuery($userId))
            ->whereRaw('status = ?', ['pending'])
            ->count();

        $openWorkOrders = (clone $this->scopedWorkOrdersQuery($userId))
            ->whereNull('closed_at')
            ->count();

        return [
            Stat::make(__('dashboard.stats.open_work_orders'), number_format($openWorkOrders))
                ->description(__('dashboard.stats.open_work_orders_description'))
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('warning'),
            Stat::make(__('dashboard.stats.interventions_this_month'), number_format($interventionsThisMonth))
                ->description(__('dashboard.stats.interventions_this_month_description'))
                ->descriptionIcon('heroicon-m-wrench-screwdriver')
                ->color('info'),
            Stat::make(
                __('dashboard.stats.worked_hours_this_month'),
                number_format($workMinutesThisMonth / 60, 1),
            )
                ->description(__('dashboard.stats.worked_hours_this_month_description'))
                ->descriptionIcon('heroicon-m-clock')
                ->color('success'),
            Stat::make(__('dashboard.stats.pending_absence_requests'), number_format($pendingAbsenceRequests))
                ->description(__('dashboard.stats.pending_absence_requests_description'))
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('danger'),
        ];
    }

    /** @return Builder<Intervention> */
    private function scopedInterventionsQuery(int|string|null $userId): Builder
    {
        return Intervention::query()
            ->when(
                $this->shouldScopeOperatorPanelData() && $userId !== null,
                fn (Builder $query): Builder => $query->where('performed_by_user_id', $userId),
            );
    }

    /** @return Builder<AbsenceRequest> */
    private function scopedAbsenceRequestsQuery(int|string|null $userId): Builder
    {
        return AbsenceRequest::query()
            ->when(
                $this->shouldScopeOperatorPanelData() && $userId !== null,
                fn (Builder $query): Builder => $query->where('user_id', $userId),
            );
    }

    /** @return Builder<WorkOrder> */
    private function scopedWorkOrdersQuery(int|string|null $userId): Builder
    {
        $query = WorkOrder::query();

        if ($this->shouldScopeOperatorPanelData() && $userId !== null) {
            $query->whereIn(
                'work_orders.id',
                Intervention::query()
                    ->select('work_order_id')
                    ->where('performed_by_user_id', $userId)
                    ->whereNotNull('work_order_id'),
            );
        }

        return $query;
    }
}
