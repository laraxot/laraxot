<?php

declare(strict_types=1);

namespace Modules\Wts\Datas;

use Modules\Wts\Models\WtsInboundRecord;

/**
 * Dati di presa in carico ricavati dal movimento ECOS.
 *
 * Sono gli stessi valori che UI-02 mostrava nel form: da quando la presa in
 * carico parte direttamente da UI-01 servono anche senza passare dal form,
 * quindi vivono qui e non piu' dentro la pagina.
 */
final readonly class WtsTakeChargeDefaults
{
    public function __construct(
        public string $formNumber,
        public float $grossQuantity,
        public ?float $tareWeight,
        public ?float $entryWeight,
        public string $acceptanceStatus,
        public ?string $notes,
    ) {}

    /**
     * Il viaggio e' arrivato e accettabile: ECOS espone formulario e quantita'.
     * Finche' e' falso la presa in carico non e' confermabile (RB-10), quindi
     * UI-01 tiene il tasto bloccato invece di mandare l'operatore altrove.
     */
    public function isReadyForTakeCharge(): bool
    {
        return $this->formNumber !== '' && $this->grossQuantity > 0.0;
    }

    public static function fromRecord(WtsInboundRecord $record): self
    {
        // "Quantita iniziale dichiarata" = quantita' programmata da ECOS
        // (codprgd.prgd_qta_p). "Peso in ingresso rilevato" = peso pesato in
        // impianto (bsmco.mco_qta_p), presente solo dopo l'arrivo del camion.
        // Se la quantita' programmata manca ma il camion e' stato pesato,
        // usiamo il peso reale come quantita' iniziale: RB-10 la richiede
        // positiva, altrimenti la presa in carico resterebbe bloccata.
        // NB: se ECOS debba sempre valorizzare la quantita' programmata e'
        // una domanda operativa aperta per il cliente.
        $grossQuantity = is_numeric($record->gross_quantity) ? (float) $record->gross_quantity : 0.0;
        if ($grossQuantity <= 0.0 && is_numeric($record->entry_weight)) {
            $grossQuantity = (float) $record->entry_weight;
        }

        return new self(
            formNumber: trim((string) ($record->form_number ?? '')),
            grossQuantity: $grossQuantity,
            tareWeight: is_numeric($record->tare_weight) ? (float) $record->tare_weight : null,
            entryWeight: is_numeric($record->entry_weight) ? (float) $record->entry_weight : null,
            acceptanceStatus: trim((string) ($record->acceptance_status ?? '')) !== ''
                ? trim((string) $record->acceptance_status)
                : 'accepted',
            notes: trim((string) ($record->notes ?? '')) !== '' ? trim((string) $record->notes) : null,
        );
    }
}
