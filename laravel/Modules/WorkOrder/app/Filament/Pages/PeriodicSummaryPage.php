<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Filament\Pages;

use App\Support\AccountFeatures;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Auth;
use Modules\Platform\Filament\Concerns\GatesPageNavigation;
use Modules\WorkOrder\Actions\BuildPeriodicSummaryAction;
use Modules\WorkOrder\Actions\GeneratePeriodicSummaryPdfAction;
use Modules\Xot\Filament\Pages\XotBasePage;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Single screen answering "how did the period go?": customers, work orders,
 * interventions, materials and quotations aggregated over a date range, with a
 * one-click PDF of the same figures.
 */
final class PeriodicSummaryPage extends XotBasePage
{
    use GatesPageNavigation;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';

    protected static ?string $slug = 'periodic-summary';

    protected static ?int $navigationSort = 80;

    protected string $view = 'workorder::filament.pages.periodic-summary';

    public string $from = '';

    public string $to = '';

    public static function canAccess(): bool
    {
        return AccountFeatures::currentPanelAllowsFeature(
            Auth::user(),
            adminFeature: 'admin.periodic_summary',
            operatorFeature: null,
            adminDefault: true,
            operatorDefault: false,
            fallback: false,
        );
    }

    public static function getNavigationGroup(): string
    {
        return (string) __('workorder::work_orders.navigation');
    }

    public static function getNavigationLabel(): string
    {
        return (string) __('workorder::work_orders.periodic_summary.title');
    }

    public function getTitle(): string
    {
        return (string) __('workorder::work_orders.periodic_summary.title');
    }

    public function mount(): void
    {
        $this->from = now()->startOfMonth()->toDateString();
        $this->to = now()->endOfMonth()->toDateString();
    }

    public function applyPreset(string $preset): void
    {
        $today = CarbonImmutable::now();

        [$from, $to] = match ($preset) {
            'last_month' => [$today->subMonth()->startOfMonth(), $today->subMonth()->endOfMonth()],
            'this_quarter' => [$today->startOfQuarter(), $today->endOfQuarter()],
            'this_year' => [$today->startOfYear(), $today->endOfYear()],
            default => [$today->startOfMonth(), $today->endOfMonth()],
        };

        $this->from = $from->toDateString();
        $this->to = $to->toDateString();
    }

    public function downloadPdf(): StreamedResponse
    {
        $result = app(GeneratePeriodicSummaryPdfAction::class)->execute($this->fromDate(), $this->toDate());

        return response()->streamDownload(
            static function () use ($result): void {
                echo $result['content'];
            },
            $result['filename'],
            ['Content-Type' => $result['mime']],
        );
    }

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return app(BuildPeriodicSummaryAction::class)->execute($this->fromDate(), $this->toDate());
    }

    private function fromDate(): CarbonImmutable
    {
        return $this->parseDate($this->from, CarbonImmutable::now()->startOfMonth())->startOfDay();
    }

    private function toDate(): CarbonImmutable
    {
        return $this->parseDate($this->to, CarbonImmutable::now()->endOfMonth())->endOfDay();
    }

    private function parseDate(string $value, CarbonImmutable $fallback): CarbonImmutable
    {
        if ($value === '') {
            return $fallback;
        }

        try {
            return CarbonImmutable::parse($value);
        } catch (\Throwable) {
            return $fallback;
        }
    }
}
