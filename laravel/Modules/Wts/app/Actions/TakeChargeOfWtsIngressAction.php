<?php

declare(strict_types=1);

namespace Modules\Wts\Actions;

use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsInboundRecord;
use Illuminate\Support\Facades\DB;
use InvalidArgumentException;

final class TakeChargeOfWtsIngressAction
{
    public function __construct(
        private readonly GenerateWtsUnitSeriesAction $generateUnitSeries,
    ) {}

    public function execute(
        WtsInboundRecord $inboundRecord,
        string $formNumber,
        float $grossQuantity,
        ?float $tareWeight,
        ?float $entryWeight,
        string $acceptanceStatus,
        ?string $notes,
        int|string|null $userId,
    ): WtsInboundLot {
        $normalizedFormNumber = trim($formNumber);
        $normalizedAcceptanceStatus = trim($acceptanceStatus);

        if ($normalizedFormNumber === '') {
            throw new InvalidArgumentException('Il formulario e obbligatorio.');
        }

        if ($grossQuantity <= 0) {
            throw new InvalidArgumentException('La quantita iniziale deve essere positiva.');
        }

        if ($entryWeight !== null && $entryWeight < 0) {
            throw new InvalidArgumentException('Il peso in ingresso non puo essere negativo.');
        }

        if ($tareWeight !== null && $tareWeight < 0) {
            throw new InvalidArgumentException('La tara non puo essere negativa.');
        }

        if ($normalizedAcceptanceStatus === '') {
            throw new InvalidArgumentException('L esito accettazione e obbligatorio.');
        }

        // Blindatura tracciabilita': la presa in carico e' un'accettazione
        // formale e non e' ripetibile. Formulario e pesi accettati non devono
        // essere sovrascrivibili rieseguendo questa azione (es. via URL diretto).
        if ($inboundRecord->taken_charge_at !== null || $inboundRecord->lots()->exists()) {
            throw new InvalidArgumentException('Ingresso gia preso in carico: i dati accettati non sono modificabili.');
        }

        return DB::transaction(function () use (
            $inboundRecord,
            $normalizedFormNumber,
            $grossQuantity,
            $tareWeight,
            $entryWeight,
            $normalizedAcceptanceStatus,
            $notes,
            $userId,
        ): WtsInboundLot {
            $inboundRecord->forceFill([
                'form_number' => $normalizedFormNumber,
                'gross_quantity' => $grossQuantity,
                'tare_weight' => $tareWeight,
                'entry_weight' => $entryWeight,
                'net_weight' => $entryWeight,
                'acceptance_status' => $normalizedAcceptanceStatus,
                'status_code' => 'taken_charge',
                'taken_charge_at' => now(),
                'created_by_user_id' => $inboundRecord->created_by_user_id ?? $userId,
                'notes' => trim((string) $notes) !== '' ? trim((string) $notes) : null,
            ])->save();

            /** @var WtsInboundLot $lot */
            $lot = $inboundRecord->lots()->create([
                'lot_code' => $this->nextLotCode(),
                'status_code' => 'registered',
                'current_area_code' => 'incoming',
            ]);

            // Colli dichiarati da ECOS (movimento registrato): se presenti li
            // generiamo in automatico con la tipologia di default, cosi' il
            // dettaglio lotto (UI-03) mostra subito i colli attesi. L'operatore
            // puo' correggere tipo e numero in UI-03. Senza colli il lotto resta
            // vuoto e la generazione avviene manualmente. GenerateWtsUnitSeries
            // allinea da solo initial_units_count al numero di colli creati.
            $ecosUnitsCount = (int) ($inboundRecord->ecos_units_count ?? 0);
            if ($ecosUnitsCount > 0) {
                $this->generateUnitSeries->execute(
                    lot: $lot,
                    unitTypeCode: $this->defaultUnitType(),
                    unitsCount: $ecosUnitsCount,
                    technicalSheetSnapshot: $inboundRecord->technicalSheetSnapshot,
                    printReasonCode: 'ecos_take_charge_auto',
                    printedByUserId: $userId,
                );
            }

            return $lot->refresh();
        });
    }

    /**
     * Tipologia collo usata per la generazione automatica quando ECOS non
     * fornisce la tipologia. Fallback a big_bag se la config non e' valida.
     */
    private function defaultUnitType(): string
    {
        $configuredType = config('wts.default_unit_type', 'big_bag');

        if (is_string($configuredType) && trim($configuredType) !== '') {
            return $configuredType;
        }

        return 'big_bag';
    }

    private function nextLotCode(): string
    {
        // withTrashed: i lotti soft-deleted mantengono il lot_code (unique),
        // quindi numerazione e controllo collisioni devono includerli.
        $sequence = WtsInboundLot::query()->withTrashed()->count() + 1;

        do {
            $lotCode = sprintf('LOT-%s-%04d', now()->format('Y'), $sequence);
            $sequence++;
        } while (WtsInboundLot::query()->withTrashed()->where('lot_code', $lotCode)->exists());

        return $lotCode;
    }
}
