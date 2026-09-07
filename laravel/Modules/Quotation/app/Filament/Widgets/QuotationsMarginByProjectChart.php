<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Widgets;

use App\Support\AccountFeatures;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Quotation\Models\Quotation;
use Modules\Xot\Filament\Widgets\XotBaseChartWidget;

/**
 * Revenue vs. cost of the quoted work, broken down by project/brand — the view
 * that answers "which brand actually pays?" rather than "which brand orders
 * most", which are rarely the same answer.
 *
 * Quotations with no cost recorded are left out: showing them would read as
 * 100% margin and flatter the numbers.
 */
final class QuotationsMarginByProjectChart extends XotBaseChartWidget
{
    protected static ?int $sort = 6;

    protected int|string|array $columnSpan = 1;

    protected ?string $maxHeight = '180px';

    /** How many projects to plot: past this the chart stops being readable. */
    /** @var int */
    private const MAX_PROJECTS = 6;

    public static function canView(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.dashboard',
            operatorFeature: 'operator.dashboard',
            adminDefault: true,
            operatorDefault: false,
            fallback: false,
        );
    }

    public function getHeading(): string
    {
        return (string) __('dashboard.charts.margin_by_project');
    }

    /** @return array<string, mixed> */
    protected function getData(): array
    {
        $rows = Quotation::query()
            ->toBase()
            ->whereNotNull('project')
            ->where('project', '!=', '')
            ->where('cost_total', '>', 0)
            ->groupBy('project')
            ->orderByDesc(DB::raw('SUM(total) - SUM(cost_total)'))
            ->limit(self::MAX_PROJECTS)
            ->get(['project', DB::raw('SUM(total) as revenue'), DB::raw('SUM(cost_total) as cost')]);

        $labels = [];
        $revenue = [];
        $margin = [];

        foreach ($rows as $row) {
            $rowRevenue = is_numeric($row->revenue) ? (float) $row->revenue : 0.0;
            $rowCost = is_numeric($row->cost) ? (float) $row->cost : 0.0;

            $labels[] = is_string($row->project) ? $row->project : '';
            $revenue[] = round($rowRevenue, 2);
            $margin[] = round($rowRevenue - $rowCost, 2);
        }

        return [
            'datasets' => [
                [
                    'label' => (string) __('dashboard.charts.margin_revenue'),
                    'data' => $revenue,
                    'backgroundColor' => 'rgba(59, 130, 246, 0.5)',
                ],
                [
                    'label' => (string) __('dashboard.charts.margin_amount'),
                    'data' => $margin,
                    'backgroundColor' => 'rgba(16, 185, 129, 0.6)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
