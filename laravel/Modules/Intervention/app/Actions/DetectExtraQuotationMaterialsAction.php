<?php

declare(strict_types=1);

namespace Modules\Intervention\Actions;

use Modules\Intervention\Data\ExtraMaterialLine;
use Modules\Intervention\Data\QuotedMaterial;
use Modules\Intervention\Data\UsedMaterial;
use Spatie\QueueableAction\QueueableAction;

use function Safe\preg_replace;

/**
 * Confronta il materiale realmente usato in intervento con quello preventivato e
 * isola l'"extra preventivo": quantita' non coperte dal preventivo.
 *
 * La regola non e' "riga presente o assente" ma per quantita': se il preventivo
 * copre 3 pezzi e in cantiere ne servono 5, l'extra e' 2, non l'intera riga.
 * Righe con la stessa descrizione consumano la stessa disponibilita' preventivata.
 */
final class DetectExtraQuotationMaterialsAction
{
    use QueueableAction;

    /**
     * @param  array<int, UsedMaterial>  $usedMaterials
     * @param  array<int, QuotedMaterial>  $quotedMaterials
     * @return array<int, ExtraMaterialLine>
     */
    public function execute(array $usedMaterials, array $quotedMaterials): array
    {
        $remaining = [];
        $quotationItemIds = [];

        foreach ($quotedMaterials as $quoted) {
            $key = $this->normalize($quoted->description);
            $remaining[$key] = ($remaining[$key] ?? 0.0) + $quoted->quantity;
            // Il primo preventivo che copre quella descrizione da' il riferimento.
            $quotationItemIds[$key] ??= $quoted->quotationItemId;
        }

        $lines = [];

        foreach ($usedMaterials as $used) {
            $key = $this->normalize($used->description);
            $available = $remaining[$key] ?? 0.0;
            $covered = min($used->quantity, $available);
            $extraQuantity = round($used->quantity - $covered, 2);

            if ($available > 0.0) {
                $remaining[$key] = round($available - $covered, 2);
            }

            $lines[] = new ExtraMaterialLine(
                materialLineId: $used->materialLineId,
                quotationItemId: $quotationItemIds[$key] ?? null,
                extraQuantity: $extraQuantity,
                extraAmount: round($extraQuantity * $used->unitPrice, 2),
            );
        }

        return $lines;
    }

    /**
     * Le descrizioni arrivano da preventivo e da cantiere, scritte da persone
     * diverse in momenti diversi: confrontarle alla lettera non aggancerebbe
     * quasi nulla.
     */
    private function normalize(string $description): string
    {
        $normalized = mb_strtolower(trim($description));

        return (string) preg_replace('/\s+/', ' ', $normalized);
    }
}
