<?php

declare(strict_types=1);

namespace Modules\Compliance\Actions;

use Illuminate\Support\Carbon;
use Modules\Compliance\Models\ComplianceDocument;
use Modules\Compliance\Models\ConformityAttachment;
use Modules\Compliance\Models\ConformityDeclaration;
use RuntimeException;
use Spatie\QueueableAction\QueueableAction;

/**
 * Emette la dichiarazione, e solo se il fascicolo e' completo.
 *
 * Il valore di questa azione sta nel rifiuto, non nell'emissione. Una
 * dichiarazione incompleta consegnata al cliente e' peggio di una non ancora
 * pronta, perche' sembra fatta: il committente la mette via, e il problema salta
 * fuori mesi dopo, quando la chiede il Comune o quando serve per la vendita
 * dell'immobile.
 *
 * Emettendo si crea anche il documento `dico` nel fascicolo della commessa.
 * Serve perche' EvaluateWorkOrderReadinessAction pretende gia' quel documento
 * fra gli obbligatori: senza questo passaggio l'installatore avrebbe la
 * dichiarazione emessa e la commessa ancora segnata in rosso.
 */
final class IssueConformityDeclarationAction
{
    use QueueableAction;

    public function __construct(
        private readonly DetermineRequiredAttachmentsAction $required,
        private readonly EvaluateWorkOrderReadinessAction $readiness,
    ) {}

    /**
     * @return array{declaration: ConformityDeclaration, warnings: array<int, string>}
     */
    public function execute(ConformityDeclaration $declaration, int|string|null $userId = null): array
    {
        $declaration->loadMissing('attachments');

        $missing = $this->required->missing($declaration);

        if ($missing !== []) {
            $elenco = implode(' ', array_map(
                static fn (string $kind, string $why): string => '- '.(ConformityAttachment::ETICHETTE[$kind] ?? $kind).': '.$why,
                array_keys($missing),
                $missing,
            ));

            throw new RuntimeException(
                'Mancano allegati obbligatori, quindi la dichiarazione non si puo emettere. '.$elenco
            );
        }

        $declaration->forceFill([
            'status' => ConformityDeclaration::EMESSA,
            'issued_at' => Carbon::now(),
            'issued_on' => $declaration->issued_on ?? Carbon::now()->toDateString(),
            'issued_by_user_id' => $userId ?? $declaration->issued_by_user_id,
        ])->save();

        $this->registerInWorkOrderFile($declaration);

        return [
            'declaration' => $declaration->refresh(),
            'warnings' => $this->required->warnings($declaration),
        ];
    }

    /**
     * Mette la dichiarazione nel fascicolo della commessa.
     */
    private function registerInWorkOrderFile(ConformityDeclaration $declaration): void
    {
        if ($declaration->work_order_id === null) {
            return;
        }

        $document = ComplianceDocument::query()->updateOrCreate(
            [
                'work_order_id' => $declaration->work_order_id,
                'type' => 'dico',
            ],
            [
                'title' => 'Dichiarazione di conformita '.$declaration->number,
                'status' => 'valid',
                'is_required' => true,
                'issued_on' => $declaration->issued_on,
                'customer_id' => $declaration->customer_id,
                'notes' => 'Emessa dal gestionale il '.Carbon::now()->format('d/m/Y').'. Fascicolo allegati completo.',
            ],
        );

        $declaration->forceFill(['compliance_document_id' => $document->id])->save();

        // La spia della commessa va aggiornata adesso, non al prossimo giro: chi
        // ha appena emesso vuole vederla diventare verde.
        $workOrder = $declaration->workOrder;

        if ($workOrder !== null) {
            $this->readiness->execute($workOrder);
        }
    }
}
