<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Widgets;

use App\Support\AccountFeatures;
use Illuminate\Support\Facades\Auth;
use Modules\Intervention\Models\Intervention;
use Modules\Platform\Filament\Widgets\Concerns\ResolvesPanelUserScope;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\Xot\Filament\Widgets\XotBaseChartWidget;

final class WorkOrdersStatusChart extends XotBaseChartWidget
{
    use ResolvesPanelUserScope;

    protected static ?int $sort = 2;

    protected int|string|array $columnSpan = 1;

    protected ?string $maxHeight = '180px';

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

    public function getHeading(): string
    {
        return __('dashboard.charts.work_orders_by_status');
    }

    /** @return array<string, mixed> */
    protected function getData(): array
    {
        $userId = $this->currentUserId();

        $query = WorkOrder::query()
            ->leftJoin('work_order_statuses', 'work_orders.status_id', '=', 'work_order_statuses.id')
            ->whereNull('work_orders.deleted_at');

        if ($this->shouldScopeOperatorPanelData() && $userId !== null) {
            $query->whereIn(
                'work_orders.id',
                Intervention::query()
                    ->select('work_order_id')
                    ->where('performed_by_user_id', $userId)
                    ->whereNotNull('work_order_id'),
            );
        }

        $totalsByStatus = $query
            ->toBase()
            ->selectRaw('COALESCE(work_order_statuses.name, ?) as status_label', [__('dashboard.charts.unassigned_status')])
            ->selectRaw('COUNT(work_orders.id) as total')
            ->groupBy('status_label')
            ->orderByDesc('total')
            ->limit(6)
            ->pluck('total', 'status_label');

        /** @var array<int, string> $labels */
        $labels = array_map('strval', array_keys($totalsByStatus->all()));
        /** @var array<int, int> $data */
        $data = array_map(
            static fn (mixed $value): int => is_numeric($value) ? (int) $value : 0,
            array_values($totalsByStatus->all()),
        );

        if ($labels === [] || $data === []) {
            $labels = [__('dashboard.charts.no_data')];
            $data = [0];
        }

        return [
            'datasets' => [
                [
                    'label' => __('dashboard.charts.datasets.work_orders'),
                    'data' => $data,
                    'backgroundColor' => array_slice($this->colorPalette(), 0, count($data)),
                    'borderWidth' => 0,
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    /** @return array<int, string> */
    private function colorPalette(): array
    {
        return [
            '#22c55e',
            '#3b82f6',
            '#f59e0b',
            '#ef4444',
            '#8b5cf6',
            '#14b8a6',
        ];
    }
}
