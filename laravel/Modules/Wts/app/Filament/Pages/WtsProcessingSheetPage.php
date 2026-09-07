<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Pages;

use App\Support\AccountFeatures;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Modules\Platform\Filament\Concerns\GatesPageNavigation;
use Modules\Xot\Filament\Pages\XotBasePage;
use Modules\Wts\Actions\GenerateWtsProcessingSheetPdfAction;
use Modules\Wts\Models\WtsProcessingStep;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class WtsProcessingSheetPage extends XotBasePage
{
    use GatesPageNavigation;

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Foglio lavorazione';

    protected static ?string $title = 'Foglio lavorazione WTS';

    protected static ?string $slug = 'processing-sheet';

    protected static ?int $navigationSort = 74;

    protected string $view = 'wts::filament.pages.processing-sheet';

    public int|string|null $stepId = null;

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessWtsPage(Auth::user(), self::class);
    }

    public function mount(): void
    {
        $requestedStepId = (int) request()->query('step', '0');
        $this->stepId = $requestedStepId > 0 ? $requestedStepId : null;
    }

    public function downloadSheetPdf(): ?StreamedResponse
    {
        $step = WtsProcessingStep::query()->find($this->stepId);
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

    /**
     * @return array{
     *     steps: Collection<int, WtsProcessingStep>,
     *     selectedStep: WtsProcessingStep|null
     * }
     */
    protected function getViewData(): array
    {
        $steps = WtsProcessingStep::query()
            ->with(['selectedUnits.inboundLot.inboundRecord.technicalSheetSnapshot', 'derivedMaterials'])
            ->latest('executed_at')
            ->get();

        /** @var WtsProcessingStep|null $selectedStep */
        $selectedStep = $steps->firstWhere('id', $this->stepId);

        return [
            'steps' => $steps,
            'selectedStep' => $selectedStep,
        ];
    }
}
