<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Widgets;

use App\Support\AccountFeatures;
use Illuminate\Support\Facades\Auth;
use Modules\Quotation\Models\Quotation;
use Modules\Xot\Filament\Widgets\XotBaseChartWidget;

/**
 * Quotations pipeline for the shop owner: how many quotes sit in each status
 * (draft / sent / confirmed / rejected) — the sales funnel at a glance.
 */
final class QuotationsByStatusChart extends XotBaseChartWidget
{
    protected static ?int $sort = 4;

    protected int|string|array $columnSpan = 1;

    protected ?string $maxHeight = '180px';

    /** @var array<string, string> status code => bar/slice color */
    private const STATUS_COLORS = [
        'draft' => '#94a3b8',
        'sent' => '#3b82f6',
        'confirmed' => '#22c55e',
        'rejected' => '#ef4444',
    ];

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
        return __('dashboard.charts.quotations_by_status');
    }

    /** @return array<string, mixed> */
    protected function getData(): array
    {
        /** @var array<string, int> $counts */
        $counts = Quotation::query()
            ->toBase()
            ->selectRaw('status_code, COUNT(*) as total')
            ->groupBy('status_code')
            ->pluck('total', 'status_code')
            ->map(static fn ($value): int => is_int($value) ? $value : 0)
            ->all();

        $labels = [];
        $data = [];
        $colors = [];

        // Known statuses first (stable order/colors), then any extra ones.
        $ordered = array_keys(self::STATUS_COLORS);
        foreach (array_keys($counts) as $code) {
            if (! in_array($code, $ordered, true)) {
                $ordered[] = $code;
            }
        }

        foreach ($ordered as $code) {
            $total = $counts[$code] ?? 0;
            if ($total === 0) {
                continue;
            }
            $labels[] = $this->statusLabel($code);
            $data[] = $total;
            $colors[] = self::STATUS_COLORS[$code] ?? '#a855f7';
        }

        return [
            'datasets' => [[
                'label' => __('quotations.title'),
                'data' => $data,
                'backgroundColor' => $colors,
            ]],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }

    private function statusLabel(string $code): string
    {
        $key = 'quotations.statuses.'.$code;
        $label = __($key);

        return $label === $key ? ucfirst($code) : $label;
    }
}
