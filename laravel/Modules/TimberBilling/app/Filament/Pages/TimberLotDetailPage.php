<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Modules\TimberBilling\Actions\BuildTimberQrPayloadAction;
use Modules\TimberBilling\Actions\GenerateTimberLabelPdfAction;
use Modules\TimberBilling\Actions\MoveTimberMaterialAction;
use Modules\TimberBilling\Filament\Pages\Concerns\NormalizesTimberConfigMaps;
use Modules\TimberBilling\Models\TimberLot;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class TimberLotDetailPage extends Page
{
    use NormalizesTimberConfigMaps;

    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationLabel = 'TB-02 - Dettaglio lotto';

    protected static ?string $title = 'TB-02 - Dettaglio lotto di ingresso';

    protected static ?string $slug = 'lot-detail';

    protected static ?int $navigationSort = 2;

    protected string $view = 'filament.timber.pages.lot-detail';

    public ?int $lotId = null;

    public string $moveToArea = '';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessPanel(Auth::user(), 'timber');
    }

    public function mount(): void
    {
        $requestedLotId = (int) request()->query('lot', 0);
        $this->lotId = $requestedLotId > 0 ? $requestedLotId : null;
    }

    public function moveLot(): void
    {
        $lot = $this->selectedLot();
        if (! $lot instanceof TimberLot) {
            Notification::make()->title('Nessun lotto selezionato')->warning()->send();

            return;
        }

        try {
            $authId = Auth::id();

            app(MoveTimberMaterialAction::class)->execute($lot, $this->moveToArea, is_int($authId) ? $authId : null, null);
        } catch (InvalidArgumentException $exception) {
            Notification::make()->title($exception->getMessage())->warning()->send();

            return;
        }

        Notification::make()->title('Area lotto aggiornata')->success()->send();
    }

    public function printLotLabel(): ?StreamedResponse
    {
        $lot = $this->selectedLot();
        if (! $lot instanceof TimberLot) {
            Notification::make()->title('Nessun lotto selezionato')->warning()->send();

            return null;
        }

        $lot->labelPrints()->create([
            'label_code' => $lot->lot_code,
            'qr_payload' => app(BuildTimberQrPayloadAction::class)->execute($lot),
            'print_reason_code' => 'reprint',
            'printed_by_user_id' => Auth::id(),
            'printed_at' => now(),
        ]);

        $pdf = app(GenerateTimberLabelPdfAction::class)->execute($lot);

        return response()->streamDownload(
            static function () use ($pdf): void {
                echo $pdf;
            },
            sprintf('etichetta-lotto-%s.pdf', $lot->lot_code),
            ['Content-Type' => 'application/pdf'],
        );
    }

    /**
     * @return array{
     *     lots: Collection<int, TimberLot>,
     *     selectedLot: TimberLot|null,
     *     areaCodes: array<string, string>
     * }
     */
    protected function getViewData(): array
    {
        $lots = TimberLot::query()->with(['purchaseRecord.supplier', 'purchaseRecord.transporter'])->latest()->get();

        return [
            'lots' => $lots,
            'selectedLot' => $this->selectedLot($lots),
            'areaCodes' => $this->configMap('timber.area_codes'),
        ];
    }

    /**
     * @param  Collection<int, TimberLot>|null  $lots
     */
    private function selectedLot($lots = null): ?TimberLot
    {
        if ($lots instanceof Collection) {
            /** @var TimberLot|null $lot */
            $lot = $lots->firstWhere('id', $this->lotId);

            return $lot;
        }

        if ($this->lotId === null) {
            return null;
        }

        /** @var TimberLot|null $lot */
        $lot = TimberLot::query()->with([
            'purchaseRecord.supplier',
            'purchaseRecord.transporter',
            'movements' => fn (Relation $query) => $query->latest('moved_at'),
            'processingSteps.derivedMaterials',
            'dryingCycles.humidityChecks',
        ])->find($this->lotId);

        return $lot;
    }
}
