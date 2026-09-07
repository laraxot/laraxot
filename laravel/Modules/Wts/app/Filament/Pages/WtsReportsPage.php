<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Pages;

use App\Support\AccountFeatures;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Modules\Wts\Actions\GenerateWtsProcessingReportPdfAction;
use Modules\Wts\Actions\GenerateWtsProcessingSheetPdfAction;
use Modules\Wts\Models\WtsDerivedMaterial;
use Modules\Wts\Models\WtsProcessingStep;
use Modules\Wts\Queries\GetWtsReportOverviewQuery;
use Modules\Xot\Filament\Pages\XotBasePage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class WtsReportsPage extends XotBasePage
{

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chart-bar-square';

    protected static ?string $navigationLabel = 'UI-11 - Report lavorazioni';

    protected static ?string $title = 'UI-11 - Report lavorazioni';

    protected static ?string $slug = 'reports';

    protected static ?int $navigationSort = 10;

    protected string $view = 'wts::filament.pages.reports';

    public static function canAccess(): bool
    {
        return AccountFeatures::isWtsAdmin(Auth::user());
    }

    public function downloadStepPdf(int|string $stepId): ?StreamedResponse
    {
        $step = WtsProcessingStep::query()->find($stepId);
        if (! $step instanceof WtsProcessingStep) {
            return null;
        }

        $pdf = app(GenerateWtsProcessingSheetPdfAction::class)->execute($step);

        return response()->streamDownload(
            static function () use ($pdf): void {
                echo $pdf;
            },
            sprintf('foglio-lavorazione-%s.pdf', $step->work_sheet_code),
            ['Content-Type' => 'application/pdf'],
        );
    }

    public function downloadFullReportPdf(): StreamedResponse
    {
        $pdf = app(GenerateWtsProcessingReportPdfAction::class)->execute();

        return response()->streamDownload(
            static function () use ($pdf): void {
                echo $pdf;
            },
            sprintf('report-lavorazioni-wts-%s.pdf', now()->format('Y-m-d')),
            ['Content-Type' => 'application/pdf'],
        );
    }

    /**
     * @return array{
     *     stats: array<string, int|float>,
     *     steps: Collection<int, WtsProcessingStep>,
     *     materials: Collection<int, WtsDerivedMaterial>
     * }
     */
    protected function getViewData(): array
    {
        return app(GetWtsReportOverviewQuery::class)->execute();
    }
}
