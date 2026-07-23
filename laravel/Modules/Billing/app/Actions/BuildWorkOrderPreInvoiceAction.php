<?php

declare(strict_types=1);

namespace Modules\Billing\Actions;

use Modules\Billing\Domain\Actions\BuildPreInvoiceDraftAction;
use Modules\Billing\Domain\DTO\PreInvoiceDraft;
use Modules\Billing\Domain\DTO\PreInvoiceLine;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\InterventionMaterialLine;
use Modules\WorkOrder\Models\WorkOrder;

/**
 * Pre-fatturazione: raccoglie tutto il consuntivo di una commessa e ne propone
 * le righe da fatturare, prima che qualcuno emetta la fattura.
 *
 * E' il momento in cui l'extra preventivo puo' ancora essere incassato: dopo
 * l'emissione e' regalato.
 */
final readonly class BuildWorkOrderPreInvoiceAction
{
    public function __construct(
        private BuildPreInvoiceDraftAction $buildPreInvoiceDraft,
    ) {}

    public function execute(WorkOrder $workOrder): PreInvoiceDraft
    {
        $interventions = $workOrder->interventions()->with('materialLines')->get();

        $lines = [];
        $pendingExtraTotal = 0.0;

        foreach ($interventions as $intervention) {
            $lines = [...$lines, ...$this->interventionLines($intervention)];

            foreach ($intervention->materialLines as $materialLine) {
                $extraAmount = (float) $materialLine->extra_amount;

                if ($extraAmount <= 0.0) {
                    continue;
                }

                // Solo gli extra decisi "da fatturare" entrano nel totale.
                // I 'pending' restano fuori ma vengono segnalati, i 'waived'
                // sono stati regalati consapevolmente.
                if ($materialLine->extra_status_code === 'to_bill') {
                    $lines[] = new PreInvoiceLine(
                        description: sprintf('Extra preventivo: %s', (string) $materialLine->description),
                        amount: round($extraAmount, 2),
                        sourceCode: PreInvoiceLine::SOURCE_EXTRA,
                    );

                    continue;
                }

                if ($materialLine->extra_status_code === 'pending') {
                    $pendingExtraTotal += $extraAmount;
                }
            }
        }

        return $this->buildPreInvoiceDraft->execute($lines, $pendingExtraTotal);
    }

    /**
     * @return array<int, PreInvoiceLine>
     */
    private function interventionLines(Intervention $intervention): array
    {
        $lines = [];

        $labor = is_numeric($intervention->amount) ? (float) $intervention->amount : 0.0;
        if ($labor > 0.0) {
            $lines[] = new PreInvoiceLine(
                description: sprintf('Manodopera intervento #%d', $intervention->id),
                amount: round($labor, 2),
                sourceCode: PreInvoiceLine::SOURCE_LABOR,
            );
        }

        $callout = is_numeric($intervention->callout_fee) ? (float) $intervention->callout_fee : 0.0;
        if ($callout > 0.0) {
            $lines[] = new PreInvoiceLine(
                description: sprintf('Diritto di chiamata intervento #%d', $intervention->id),
                amount: round($callout, 2),
                sourceCode: PreInvoiceLine::SOURCE_CALLOUT,
            );
        }

        // Materiale coperto dal preventivo: si fattura al netto della parte extra,
        // che ha un trattamento suo.
        $coveredMaterials = $intervention->materialLines
            ->sum(static fn (InterventionMaterialLine $line): float => round(
                (float) $line->line_total - (float) $line->extra_amount,
                2,
            ));

        if ($coveredMaterials > 0.0) {
            $lines[] = new PreInvoiceLine(
                description: sprintf('Materiali intervento #%d', $intervention->id),
                amount: round($coveredMaterials, 2),
                sourceCode: PreInvoiceLine::SOURCE_MATERIAL,
            );
        }

        return $lines;
    }
}
