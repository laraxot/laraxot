<?php

declare(strict_types=1);

namespace Modules\Timber\Filament\Pages;

use App\Support\AccountFeatures;
use Modules\Xot\Filament\Pages\XotBasePage;
use Illuminate\Support\Facades\Auth;
use Modules\Timber\Queries\GetTimberBusinessAnalyticsQuery;
use Modules\Timber\Domain\DTO\TimberAnalyticsSummary;

final class TimberAnalyticsPage extends XotBasePage
{
    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Analisi vendite e acquisti';

    protected static ?string $title = 'Analisi vendite, acquisti e flussi';

    protected static ?string $slug = 'analytics';

    protected static ?int $navigationSort = 25;

    protected string $view = 'timber::filament.analytics';

    public string $fromDate = '';

    public string $toDate = '';

    public static function getNavigationGroup(): string
    {
        return 'Analisi';
    }

    public static function canAccess(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.timber',
            operatorFeature: 'operator.timber',
            adminDefault: true,
            operatorDefault: true,
            fallback: false,
        );
    }

    public function mount(): void
    {
        $this->fromDate = now()->startOfYear()->toDateString();
        $this->toDate = now()->endOfYear()->toDateString();
    }

    public function summary(): TimberAnalyticsSummary
    {
        return app(GetTimberBusinessAnalyticsQuery::class)->execute($this->fromDate, $this->toDate);
    }

    /**
     * @return array{summary: TimberAnalyticsSummary}
     */
    protected function getViewData(): array
    {
        return [
            'summary' => $this->summary(),
        ];
    }
}
