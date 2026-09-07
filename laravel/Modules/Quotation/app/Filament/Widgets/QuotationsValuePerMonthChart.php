<?php

declare(strict_types=1);

namespace Modules\Quotation\Filament\Widgets;

use App\Support\AccountFeatures;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Modules\Quotation\Models\Quotation;
use Modules\Xot\Filament\Widgets\XotBaseChartWidget;

/**
 * Monthly quotations value for the last 6 months: total quoted vs. confirmed.
 * Lets the plumber see the business trend and the quote→win conversion.
 */
final class QuotationsValuePerMonthChart extends XotBaseChartWidget
{
    protected static ?int $sort = 5;

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
        return __('dashboard.charts.quotations_value_per_month');
    }

    /** @return array<string, mixed> */
    protected function getData(): array
    {
        $labels = [];
        $quoted = [];
        $confirmed = [];

        foreach ($this->resolveMonthStarts() as $monthStart) {
            $monthEnd = $monthStart->endOfMonth();
            $labels[] = $monthStart->translatedFormat('M y');

            $base = Quotation::query()
                ->toBase()
                ->whereRaw(
                    'COALESCE(issued_at, created_at) BETWEEN ? AND ?',
                    [$monthStart->toDateTimeString(), $monthEnd->toDateTimeString()],
                );

            $quoted[] = round((float) (clone $base)->sum('total'), 2);
            $confirmed[] = round((float) (clone $base)->where('status_code', 'confirmed')->sum('total'), 2);
        }

        return [
            'datasets' => [
                [
                    'label' => __('dashboard.charts.datasets.quoted'),
                    'data' => $quoted,
                    'backgroundColor' => '#93c5fd',
                    'borderColor' => '#3b82f6',
                ],
                [
                    'label' => __('dashboard.charts.datasets.confirmed'),
                    'data' => $confirmed,
                    'backgroundColor' => '#22c55e',
                    'borderColor' => '#16a34a',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }

    /** @return array<int, CarbonImmutable> */
    private function resolveMonthStarts(): array
    {
        $currentMonthStart = CarbonImmutable::now()->startOfMonth();
        $monthStarts = [];

        for ($offset = 5; $offset >= 0; $offset--) {
            $monthStarts[] = $currentMonthStart->subMonths($offset);
        }

        return $monthStarts;
    }
}
