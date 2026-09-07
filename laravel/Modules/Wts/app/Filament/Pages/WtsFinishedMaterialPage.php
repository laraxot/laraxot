<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Modules\Wts\Actions\GenerateWtsOutputLabelPdfAction;
use Modules\Wts\Models\WtsDerivedMaterial;
use Modules\Xot\Filament\Pages\XotBasePage;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class WtsFinishedMaterialPage extends XotBasePage
{

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-check-badge';

    protected static ?string $navigationLabel = 'UI-09 - Materiale finito';

    protected static ?string $title = 'UI-09 - Dettaglio materiale finito e tracciabilita';

    protected static ?string $slug = 'finished-material';

    protected static ?int $navigationSort = 9;

    protected string $view = 'wts::filament.pages.finished-material';

    public int|string|null $materialId = null;

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessWtsPage(Auth::user(), self::class);
    }

    public function mount(): void
    {
        $requestedMaterialId = (int) request()->query('material', '0');
        $this->materialId = $requestedMaterialId > 0 ? $requestedMaterialId : null;
    }

    public function reprintOutputLabel(): ?StreamedResponse
    {
        $material = $this->selectedMaterial();
        if (! $material instanceof WtsDerivedMaterial) {
            return null;
        }

        $authId = Auth::id();
        $material->labelPrints()->create([
            'label_code' => $material->output_code.'-R'.str_pad((string) ($material->labelPrints->count() + 1), 2, '0', STR_PAD_LEFT),
            'qr_payload' => $material->output_code,
            'print_reason_code' => 'output_reprint',
            'printed_by_user_id' => is_int($authId) || is_string($authId) ? $authId : null,
            'printed_at' => now(),
        ]);

        Notification::make()
            ->title('Etichetta output ristampata')
            ->success()
            ->send();

        $pdf = app(GenerateWtsOutputLabelPdfAction::class)->execute($material);

        return response()->streamDownload(
            static function () use ($pdf): void {
                echo $pdf;
            },
            sprintf('etichetta-output-%s.pdf', $material->output_code),
            ['Content-Type' => 'application/pdf'],
        );
    }

    /**
     * @return array{selectedMaterial: WtsDerivedMaterial|null}
     */
    protected function getViewData(): array
    {
        return [
            'selectedMaterial' => $this->selectedMaterial(),
        ];
    }

    private function selectedMaterial(): ?WtsDerivedMaterial
    {
        if ($this->materialId === null) {
            return null;
        }

        /** @var WtsDerivedMaterial|null $material */
        $material = WtsDerivedMaterial::query()
            ->with([
                'processingStep.selectedUnits.inboundLot.inboundRecord.technicalSheetSnapshot',
                'processingStep.executedBy',
                'technicalSheetSnapshot',
                'labelPrints',
            ])
            ->find($this->materialId);

        return $material;
    }
}
