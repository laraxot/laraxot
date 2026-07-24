<?php

declare(strict_types=1);

namespace Modules\Billing\Actions;

use Modules\Billing\Datas\PreInvoiceDraft;
use Modules\Billing\Datas\PreInvoiceLine;
use Spatie\QueueableAction\QueueableAction;

/**
 * Compone la proposta di fattura a partire dalle voci gia' consuntivate.
 *
 * Gli extra preventivo non ancora decisi non entrano nel totale ma vengono
 * esposti a parte: devono essere una scelta esplicita, non una dimenticanza.
 */
final class BuildPreInvoiceDraftAction
{
    use QueueableAction;

    /**
     * @param  array<int, PreInvoiceLine>  $lines
     */
    public function execute(array $lines, float $pendingExtraTotal = 0.0): PreInvoiceDraft
    {
        $total = 0.0;

        foreach ($lines as $line) {
            $total += $line->amount;
        }

        return new PreInvoiceDraft(
            lines: array_values($lines),
            total: round($total, 2),
            pendingExtraTotal: round($pendingExtraTotal, 2),
        );
    }
}
