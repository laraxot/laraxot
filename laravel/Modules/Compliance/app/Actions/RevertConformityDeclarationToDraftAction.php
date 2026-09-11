<?php

declare(strict_types=1);

namespace Modules\Compliance\Actions;

use Modules\Compliance\Models\ConformityDeclaration;
use Spatie\QueueableAction\QueueableAction;

/**
 * Riporta in bozza una dichiarazione gia' emessa.
 *
 * Serve, perche' un refuso su un atto va corretto. Ma non e' un pulsante neutro:
 * la copia consegnata al committente e quella eventualmente depositata allo
 * Sportello Unico restano quelle di prima, e correggere qui non le tocca. Per
 * questo la schermata blocca la modifica finche' non si passa di qui, e per
 * questo il documento nel fascicolo della commessa torna "non valido": la spia
 * della commessa deve tornare rossa, altrimenti resta verde su una dichiarazione
 * che in questo momento non esiste piu' come atto emesso.
 */
final class RevertConformityDeclarationToDraftAction
{
    use QueueableAction;

    public function __construct(
        private readonly EvaluateWorkOrderReadinessAction $readiness,
    ) {}

    public function execute(ConformityDeclaration $declaration): ConformityDeclaration
    {
        if (! $declaration->isIssued()) {
            return $declaration;
        }

        $declaration->forceFill([
            'status' => ConformityDeclaration::BOZZA,
            'issued_at' => null,
        ])->save();

        $documento = $declaration->complianceDocument;

        if ($documento !== null) {
            $documento->forceFill([
                'status' => 'missing',
                'notes' => trim((string) $documento->notes."\nRiportata in bozza: la dichiarazione va riemessa."),
            ])->save();
        }

        $workOrder = $declaration->workOrder;

        if ($workOrder !== null) {
            $this->readiness->execute($workOrder);
        }

        return $declaration->refresh();
    }
}
