<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Modules\Platform\Filament\Pages\GatedXotBasePage;
use Modules\Wts\Actions\GenerateWtsUnitLabelPdfAction;
use Modules\Wts\Actions\MoveWtsUnitAction;
use Modules\Wts\Models\WtsUnit;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class WtsMaterialDetailPage extends GatedXotBasePage
{
    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-cube';

    protected static ?string $navigationLabel = 'UI-06 - Dettaglio collo da giacenza';

    protected static ?string $title = 'UI-06 - Dettaglio collo da giacenza';

    protected static ?string $slug = 'material-detail';

    protected static ?int $navigationSort = 6;

    protected string $view = 'wts::filament.pages.material-detail';

    public int|string|null $unitId = null;

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessWtsPage(Auth::user(), self::class);
    }

    public function mount(): void
    {
        $requestedUnitId = (int) request()->query('unit', 0);
        $this->unitId = $requestedUnitId > 0 ? $requestedUnitId : null;
    }

    public function moveToArea(string $areaCode, ?string $locationLabel = null): void
    {
        $unit = $this->selectedUnit();
        if (! $unit instanceof WtsUnit) {
            return;
        }

        try {
            app(MoveWtsUnitAction::class)->execute($unit, $areaCode, $locationLabel);
        } catch (InvalidArgumentException $exception) {
            Notification::make()
                ->title($exception->getMessage())
                ->warning()
                ->send();

            return;
        }

        Notification::make()
            ->title('Scheda collo aggiornata')
            ->success()
            ->send();
    }

    /**
     * Ristampa l'etichetta del collo corrente (funzione assorbita dalla ex UI-04).
     */
    public function reprintUnitLabel(): ?StreamedResponse
    {
        $unit = $this->selectedUnit();
        if (! $unit instanceof WtsUnit) {
            return null;
        }

        $authId = Auth::id();

        $unit->labelPrints()->create([
            'label_code' => $unit->unit_code.'-R'.str_pad((string) ($unit->labelPrints->count() + 1), 2, '0', STR_PAD_LEFT),
            'qr_payload' => $unit->qr_code,
            'print_reason_code' => 'reprint',
            'printed_by_user_id' => is_int($authId) || is_string($authId) ? $authId : null,
            'printed_at' => now(),
        ]);

        Notification::make()
            ->title('Etichetta ristampata')
            ->success()
            ->send();

        $pdf = app(GenerateWtsUnitLabelPdfAction::class)->execute($unit);

        return response()->streamDownload(
            static function () use ($pdf): void {
                echo $pdf;
            },
            sprintf('etichetta-%s.pdf', $unit->unit_code),
            ['Content-Type' => 'application/pdf'],
        );
    }

    /**
     * @return array{selectedUnit: WtsUnit|null, stockViews: array<string, string>}
     */
    protected function getViewData(): array
    {
        return [
            'selectedUnit' => $this->selectedUnit(),
            'stockViews' => $this->stockViews(),
        ];
    }

    private function selectedUnit(): ?WtsUnit
    {
        if ($this->unitId === null) {
            return null;
        }

        /** @var WtsUnit|null $unit */
        $unit = WtsUnit::query()
            ->with([
                'inboundLot.inboundRecord.technicalSheetSnapshot',
                'processingSteps.derivedMaterials',
                'labelPrints',
            ])
            ->find($this->unitId);

        return $unit;
    }

    /**
     * @return array<string, string>
     */
    private function stockViews(): array
    {
        $stockViews = config('wts.stock_views', []);

        if (! is_array($stockViews)) {
            return [];
        }

        $normalized = [];

        foreach ($stockViews as $key => $label) {
            if (! is_string($key)) {
                continue;
            }

            $normalized[$key] = is_scalar($label) ? (string) $label : '';
        }

        return $normalized;
    }
}
