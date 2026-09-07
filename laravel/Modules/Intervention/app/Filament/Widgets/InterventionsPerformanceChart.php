<?php

declare(strict_types=1);

namespace Modules\Intervention\Filament\Widgets;

use App\Support\AccountFeatures;
use Carbon\CarbonImmutable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\Intervention\Models\Intervention;
use Modules\Platform\Filament\Widgets\Concerns\ResolvesPanelUserScope;
use Modules\Xot\Filament\Widgets\XotBaseChartWidget;

final class InterventionsPerformanceChart extends XotBaseChartWidget
{
    use ResolvesPanelUserScope;

    protected static ?int $sort = 3;

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
        return __('dashboard.charts.interventions_performance');
    }

    /** @return array<string, mixed> */
    protected function getData(): array
    {
        $userId = $this->currentUserId();
        $baseQuery = Intervention::query()
            ->when(
                $this->isOperatorPanelRequest() && $userId !== null,
                fn (Builder $query): Builder => $query->where('performed_by_user_id', $userId),
            );

        $labels = [];
        $interventionsData = [];
        $workedHoursData = [];

        foreach ($this->resolveMonthStarts() as $monthStart) {
            $monthEnd = $monthStart->endOfMonth();
            $labels[] = $monthStart->translatedFormat('M y');

            $interventionsData[] = (clone $baseQuery)
                ->whereRaw(
                    'COALESCE(scheduled_at, started_at, created_at) BETWEEN ? AND ?',
                    [$monthStart->toDateTimeString(), $monthEnd->toDateTimeString()],
                )
                ->count();

            $workedMinutes = (int) (clone $baseQuery)
                ->whereRaw(
                    'COALESCE(scheduled_at, started_at, created_at) BETWEEN ? AND ?',
                    [$monthStart->toDateTimeString(), $monthEnd->toDateTimeString()],
                )
                ->whereRaw('work_minutes > 0')
                ->sum('work_minutes');

            $workedHoursData[] = round(
                $workedMinutes / 60,
                1,
            );
        }

        return [
            'datasets' => [
                [
                    'label' => __('dashboard.charts.datasets.interventions'),
                    'data' => $interventionsData,
                    'backgroundColor' => '#3b82f6',
                    'borderColor' => '#2563eb',
                ],
                [
                    'label' => __('dashboard.charts.datasets.worked_hours'),
                    'data' => $workedHoursData,
                    'backgroundColor' => '#f97316',
                    'borderColor' => '#ea580c',
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
