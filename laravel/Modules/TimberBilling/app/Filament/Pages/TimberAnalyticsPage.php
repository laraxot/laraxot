<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Modules\TimberBilling\Queries\GetTimberBusinessAnalyticsQuery;
use Modules\TimberBilling\Domain\DTO\TimberAnalyticsSummary;

final class TimberAnalyticsPage extends Page
{
    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar';

    protected static ?string $navigationLabel = 'Analisi vendite e acquisti';

    protected static ?string $title = 'Analisi vendite, acquisti e flussi';

    protected static ?string $slug = 'analytics';

    protected static ?int $navigationSort = 25;

    protected string $view = 'timberbilling::filament.analytics';

    public string $fromDate = '';

    public string $toDate = '';

    public static function getNavigationGroup(): ?string
    {
        return 'Analisi';
    }

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessPanel(Auth::user(), 'timber');
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
