<?php

declare(strict_types=1);

namespace Modules\Billing\Domain\DTO;

/**
 * Proposta di fattura per una commessa: cosa fatturare, quanto, e cosa resta da
 * decidere prima di emettere.
 */
final readonly class PreInvoiceDraft
{
    /**
     * @param  array<int, PreInvoiceLine>  $lines
     */
    public function __construct(
        public array $lines,
        public float $total,
        public float $pendingExtraTotal,
    ) {}

    /**
     * Ci sono extra preventivo su cui nessuno ha ancora deciso se fatturare o
     * regalare: emettere adesso significa regalarli per omissione.
     */
    public function hasUndecidedExtras(): bool
    {
        return $this->pendingExtraTotal > 0.0;
    }
}
