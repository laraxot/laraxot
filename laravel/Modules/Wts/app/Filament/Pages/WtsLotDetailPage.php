<?php

declare(strict_types=1);

namespace Modules\Wts\Filament\Pages;

use App\Support\AccountFeatures;
use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use InvalidArgumentException;
use Modules\Platform\Filament\Pages\GatedXotBasePage;
use Modules\Wts\Actions\EnqueueWtsLabelPrintAction;
use Modules\Wts\Actions\GenerateWtsUnitLabelPdfAction;
use Modules\Wts\Actions\GenerateWtsUnitLabelsBatchPdfAction;
use Modules\Wts\Actions\GenerateWtsUnitSeriesAction;
use Modules\Wts\Actions\ReportWtsNonConformityAction;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsUnit;
use Symfony\Component\HttpFoundation\StreamedResponse;

final class WtsLotDetailPage extends GatedXotBasePage
{
    protected static bool $isDiscovered = false;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-archive-box';

    protected static ?string $navigationLabel = 'UI-03 - Dettaglio lotto';

    protected static ?string $title = 'UI-03 - Dettaglio lotto di ingresso';

    protected static ?string $slug = 'lot-detail';

    protected static ?int $navigationSort = 3;

    protected string $view = 'wts::filament.pages.lot-detail';

    public int|string|null $lotId = null;

    public string $nonConformityNote = '';

    public static function canAccess(): bool
    {
        return AccountFeatures::canAccessWtsPage(Auth::user(), self::class);
    }

    public function mount(): void
    {
        $requestedLotId = (int) request()->query('lot', 0);
        $this->lotId = $requestedLotId > 0 ? $requestedLotId : null;
    }

    public function addUnits(string $unitTypeCode, int $unitsCount = 1): void
    {
        $lot = $this->selectedLot();
        if (! $lot instanceof WtsInboundLot) {
            Notification::make()
                ->title('Nessun lotto selezionato')
                ->warning()
                ->send();

            return;
        }

        try {
            $authId = Auth::id();

            app(GenerateWtsUnitSeriesAction::class)->execute(
                lot: $lot,
                unitTypeCode: $unitTypeCode,
                unitsCount: $unitsCount,
                technicalSheetSnapshot: $lot->inboundRecord?->technicalSheetSnapshot,
                printReasonCode: 'lot_detail_add',
                printedByUserId: is_int($authId) || is_string($authId) ? $authId : null,
            );
        } catch (InvalidArgumentException $exception) {
            Notification::make()
                ->title($exception->getMessage())
                ->warning()
                ->send();

            return;
        }

        Notification::make()
            ->title('Collo aggiunto al lotto')
            ->success()
            ->send();
    }

    public function removeUnit(int|string $unitId): void
    {
        $lot = $this->selectedLot();
        if (! $lot instanceof WtsInboundLot) {
            return;
        }

        /** @var WtsUnit|null $unit */
        $unit = $lot->units()->where('status_code', 'available')->find($unitId);
        if (! $unit instanceof WtsUnit) {
            Notification::make()
                ->title('Solo i colli disponibili possono essere rimossi')
                ->warning()
                ->send();

            return;
        }

        // Soft delete: il collo sparisce dall'operativita' ma resta nel database
        // insieme al suo storico stampe (blindatura tracciabilita').
        $unit->delete();

        $lot->forceFill([
            'initial_units_count' => $lot->units()->count(),
        ])->save();

        Notification::make()
            ->title('Collo rimosso dal lotto')
            ->success()
            ->send();
    }

    /**
     * Cambia il tipo di contenitore di un collo gia' generato.
     */
    public function updateUnitType(int|string $unitId, string $unitTypeCode): void
    {
        $lot = $this->selectedLot();
        if (! $lot instanceof WtsInboundLot) {
            return;
        }

        if (! array_key_exists($unitTypeCode, $this->unitTypeLabels())) {
            Notification::make()
                ->title('Tipo collo non valido')
                ->warning()
                ->send();

            return;
        }

        /** @var WtsUnit|null $unit */
        $unit = $lot->units()->find($unitId);
        if (! $unit instanceof WtsUnit) {
            return;
        }

        $unit->forceFill([
            'unit_type_code' => $unitTypeCode,
        ])->save();
    }

    /**
     * Stampa/ristampa l'etichetta di un singolo collo, direttamente dalla riga
     * del collo nel dettaglio lotto (UI-04 assorbita qui).
     */
    public function reprintUnitLabel(int|string $unitId): ?StreamedResponse
    {
        $lot = $this->selectedLot();
        if (! $lot instanceof WtsInboundLot) {
            return null;
        }

        /** @var WtsUnit|null $unit */
        $unit = $lot->units()->with('labelPrints')->find($unitId);
        if (! $unit instanceof WtsUnit) {
            return null;
        }

        $this->recordLabelPrint($unit, 'reprint');

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
     * Stampa tutte le etichette del lotto in un unico PDF.
     */
    public function reprintAllLabels(): ?StreamedResponse
    {
        $lot = $this->selectedLot();
        if (! $lot instanceof WtsInboundLot) {
            return null;
        }

        $lot->load('units.labelPrints');

        if ($lot->units->isEmpty()) {
            Notification::make()
                ->title('Nessun collo da stampare')
                ->warning()
                ->send();

            return null;
        }

        foreach ($lot->units as $unit) {
            $this->recordLabelPrint($unit, 'bulk_reprint');
        }

        Notification::make()
            ->title('Etichette ristampate')
            ->success()
            ->send();

        $pdf = app(GenerateWtsUnitLabelsBatchPdfAction::class)->execute($lot->units);

        return response()->streamDownload(
            static function () use ($pdf): void {
                echo $pdf;
            },
            sprintf('etichette-lotto-%s.pdf', $lot->lot_code),
            ['Content-Type' => 'application/pdf'],
        );
    }

    /**
     * Segnalazione non conformita' del lotto (funzione assorbita dalla ex UI-04).
     */
    public function reportNonConformity(): void
    {
        $lot = $this->selectedLot();
        if (! $lot instanceof WtsInboundLot) {
            Notification::make()
                ->title('Nessun lotto selezionato')
                ->warning()
                ->send();

            return;
        }

        try {
            $authId = Auth::id();

            $report = app(ReportWtsNonConformityAction::class)->execute(
                lot: $lot,
                note: $this->nonConformityNote,
                reportedByUserId: is_int($authId) || is_string($authId) ? $authId : null,
            );
        } catch (InvalidArgumentException $exception) {
            Notification::make()
                ->title($exception->getMessage())
                ->warning()
                ->send();

            return;
        }

        $this->nonConformityNote = '';
        Notification::make()
            ->title('Non conformita segnalata')
            ->body('Segnalazione salvata per '.($report->recipient_email ?? 'referente qualita'))
            ->success()
            ->send();
    }

    /**
     * Invia l'etichetta di un collo direttamente all'etichettatrice (coda ZPL).
     */
    public function queueUnitLabelPrint(int|string $unitId): void
    {
        $lot = $this->selectedLot();
        $unit = $lot instanceof WtsInboundLot ? $lot->units()->find($unitId) : null;
        if (! $unit instanceof WtsUnit) {
            Notification::make()->title('Collo non trovato')->warning()->send();

            return;
        }

        $authId = Auth::id();
        app(EnqueueWtsLabelPrintAction::class)->execute($unit, printedByUserId: is_int($authId) || is_string($authId) ? $authId : null);

        Notification::make()->title('Etichetta inviata alla stampante')->success()->send();
    }

    /**
     * Invia tutte le etichette del lotto all'etichettatrice (coda ZPL).
     */
    public function queueAllLabelsPrint(): void
    {
        $lot = $this->selectedLot();
        if (! $lot instanceof WtsInboundLot) {
            return;
        }

        $lot->load('units');
        if ($lot->units->isEmpty()) {
            Notification::make()->title('Nessun collo da stampare')->warning()->send();

            return;
        }

        $authId = Auth::id();
        $jobs = app(EnqueueWtsLabelPrintAction::class)->executeMany($lot->units, printedByUserId: is_int($authId) || is_string($authId) ? $authId : null);

        Notification::make()->title($jobs->count().' etichette inviate alla stampante')->success()->send();
    }

    private function recordLabelPrint(WtsUnit $unit, string $reasonCode): void
    {
        $authId = Auth::id();

        $unit->labelPrints()->create([
            'label_code' => $unit->unit_code.'-R'.str_pad((string) ($unit->labelPrints->count() + 1), 2, '0', STR_PAD_LEFT),
            'qr_payload' => $unit->qr_code,
            'print_reason_code' => $reasonCode,
            'printed_by_user_id' => is_int($authId) || is_string($authId) ? $authId : null,
            'printed_at' => now(),
        ]);
    }

    /**
     * @return array{
     *     selectedLot: WtsInboundLot|null,
     *     lots: Collection<int, WtsInboundLot>,
     *     unitTypeLabels: array<string, string>
     * }
     */
    protected function getViewData(): array
    {
        $lots = WtsInboundLot::query()
            ->with(['inboundRecord.technicalSheetSnapshot', 'units.labelPrints'])
            ->latest()
            ->get();

        return [
            'selectedLot' => $this->selectedLot($lots),
            'lots' => $lots,
            'unitTypeLabels' => $this->unitTypeLabels(),
        ];
    }

    /**
     * @param  Collection<int, WtsInboundLot>|null  $lots
     */
    private function selectedLot($lots = null): ?WtsInboundLot
    {
        if ($lots instanceof Collection) {
            /** @var WtsInboundLot|null $lot */
            $lot = $lots->firstWhere('id', $this->lotId);

            return $lot;
        }

        if ($this->lotId === null) {
            return null;
        }

        /** @var WtsInboundLot|null $lot */
        $lot = WtsInboundLot::query()
            ->with(['inboundRecord.technicalSheetSnapshot', 'units.labelPrints'])
            ->find($this->lotId);

        return $lot;
    }

    /**
     * @return array<string, string>
     */
    private function unitTypeLabels(): array
    {
        $unitTypeLabels = config('wts.unit_types', []);

        if (! is_array($unitTypeLabels)) {
            return [];
        }

        $normalized = [];

        foreach ($unitTypeLabels as $code => $label) {
            if (! is_string($code)) {
                continue;
            }

            $normalized[$code] = is_scalar($label) ? (string) $label : '';
        }

        return $normalized;
    }
}
