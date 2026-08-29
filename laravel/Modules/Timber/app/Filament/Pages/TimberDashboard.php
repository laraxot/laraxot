<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Pages\Dashboard;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Filament\Pages\Concerns\NormalizesTimberConfigMaps;
use Modules\Timber\Queries\GetTimberDashboardChartsQuery;
use Modules\Timber\Queries\GetTimberDashboardStatsQuery;
use Modules\Timber\Support\WoodSpeciesCatalog;

final class TimberDashboard extends Dashboard
{
    use NormalizesTimberConfigMaps;

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static ?string $navigationLabel = 'TB-00 - Dashboard';

    protected static ?string $title = 'TB-00 - Dashboard legname';

    protected static ?int $navigationSort = 0;

    protected string $view = 'timber::filament.pages.dashboard';

    /**
     * @var array<string, string>
     */
    private const STATUS_COLORS = [
        'da_lavorare' => 'bg-blue-500 dark:bg-blue-400',
        'in_lavorazione' => 'bg-emerald-500 dark:bg-emerald-400',
        'in_essiccazione' => 'bg-amber-500 dark:bg-amber-400',
        'pronto_da_confezionare' => 'bg-teal-600 dark:bg-teal-400',
        'pronto_per_vendita' => 'bg-violet-600 dark:bg-violet-400',
        'prenotato' => 'bg-rose-500 dark:bg-rose-400',
        'scarto' => 'bg-orange-500 dark:bg-orange-400',
    ];

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessPanel(Auth::user(), 'timber');
    }

    /**
     * @return array{
     *     stats: array<string, int|float|null>,
     *     statusSegments: array<int, array{label: string, color: string, percent: float, formattedValue: string}>,
     *     speciesBars: array<int, array{label: string, heightPercent: float, formattedValue: string}>,
     *     trendBars: array<int, array{label: string, heightPercent: float, formattedValue: string}>,
     *     supplierBars: array<int, array{label: string, widthPercent: float, formattedValue: string}>
     * }
     */
    protected function getViewData(): array
    {
        $charts = app(GetTimberDashboardChartsQuery::class)->execute();

        return [
            'stats' => app(GetTimberDashboardStatsQuery::class)->execute(),
            'statusSegments' => $this->buildStatusSegments($charts['materialsByStatus']),
            'speciesBars' => $this->buildSpeciesBars($charts['stockBySpecies']),
            'trendBars' => $this->buildTrendBars($charts['purchasesTrend']),
            'supplierBars' => $this->buildSupplierBars($charts['topSuppliers']),
        ];
    }

    /**
     * @param  array<string, float>  $materialsByStatus
     * @return array<int, array{label: string, color: string, percent: float, formattedValue: string}>
     */
    private function buildStatusSegments(array $materialsByStatus): array
    {
        $total = array_sum($materialsByStatus);
        if ($total <= 0) {
            return [];
        }

        $labels = $this->configMap('timber.material_statuses');
        $segments = [];

        foreach ($labels as $code => $label) {
            $value = $materialsByStatus[$code] ?? 0.0;
            if ($value <= 0) {
                continue;
            }

            $segments[] = [
                'label' => $label,
                'color' => self::STATUS_COLORS[$code] ?? 'bg-slate-400 dark:bg-slate-500',
                'percent' => round(($value / $total) * 100, 1),
                'formattedValue' => number_format($value, 0, ',', '.'),
            ];
        }

        return $segments;
    }

    /**
     * @param  array<string, float>  $stockBySpecies
     * @return array<int, array{label: string, heightPercent: float, formattedValue: string}>
     */
    private function buildSpeciesBars(array $stockBySpecies): array
    {
        if ($stockBySpecies === []) {
            return [];
        }

        arsort($stockBySpecies);
        $max = max($stockBySpecies);

        $bars = [];
        foreach ($stockBySpecies as $code => $value) {
            // $code may be an int here: PHP casts numeric-string array keys to
            // int, so cast back before formatting. Resolve the species name from
            // the full catalog (DB + config), not just config.
            $bars[] = [
                'label' => WoodSpeciesCatalog::name((string) $code),
                'heightPercent' => $max > 0 ? round(($value / $max) * 100, 1) : 0.0,
                'formattedValue' => number_format($value, 0, ',', '.'),
            ];
        }

        return $bars;
    }

    /**
     * @param  array<int, array{weekStart: string, weekEnd: string, quantity: float}>  $purchasesTrend
     * @return array<int, array{label: string, heightPercent: float, formattedValue: string}>
     */
    private function buildTrendBars(array $purchasesTrend): array
    {
        if (array_sum(array_column($purchasesTrend, 'quantity')) <= 0) {
            return [];
        }

        $max = max([0.0, ...array_column($purchasesTrend, 'quantity')]);

        $bars = [];
        foreach ($purchasesTrend as $week) {
            $bars[] = [
                'label' => Carbon::parse($week['weekStart'])->translatedFormat('d/m'),
                'heightPercent' => $max > 0 ? round(($week['quantity'] / $max) * 100, 1) : 0.0,
                'formattedValue' => number_format($week['quantity'], 0, ',', '.'),
            ];
        }

        return $bars;
    }

    /**
     * @param  array<int, array{supplierName: string, quantity: float}>  $topSuppliers
     * @return array<int, array{label: string, widthPercent: float, formattedValue: string}>
     */
    private function buildSupplierBars(array $topSuppliers): array
    {
        if ($topSuppliers === []) {
            return [];
        }

        $max = max(array_column($topSuppliers, 'quantity'));

        $bars = [];
        foreach ($topSuppliers as $supplier) {
            $bars[] = [
                'label' => $supplier['supplierName'],
                'widthPercent' => $max > 0 ? round(($supplier['quantity'] / $max) * 100, 1) : 0.0,
                'formattedValue' => number_format($supplier['quantity'], 0, ',', '.'),
            ];
        }

        return $bars;
    }
}
