<?php

declare(strict_types=1);

namespace Modules\Billing\Datas;

/**
 * Differenza fra quello che il fornitore ha consegnato (DDT) e quello che
 * fattura.
 */
final readonly class ReconciliationDiscrepancy
{
    /** Fatturato materiale che non risulta consegnato da nessun DDT. */
    public const TYPE_NOT_DELIVERED = 'not_delivered';

    /** Fatturata una quantita' superiore a quella consegnata. */
    public const TYPE_QUANTITY_OVER = 'quantity_over';

    /** Prezzo fatturato diverso da quello del DDT. */
    public const TYPE_PRICE_MISMATCH = 'price_mismatch';

    /** Consegnato ma mai fatturato: da attendersi in una fattura successiva. */
    public const TYPE_NOT_INVOICED = 'not_invoiced';

    public function __construct(
        public string $typeCode,
        public string $description,
        public float $deliveredQuantity,
        public float $invoicedQuantity,
        public float $deliveredUnitPrice,
        public float $invoicedUnitPrice,
        public float $amountDifference,
    ) {}
}
