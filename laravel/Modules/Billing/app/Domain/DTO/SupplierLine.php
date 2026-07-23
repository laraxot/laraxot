<?php

declare(strict_types=1);

namespace Modules\Billing\Domain\DTO;

/**
 * Riga di DDT o di fattura fornitore, normalizzata per il confronto.
 */
final readonly class SupplierLine
{
    public function __construct(
        public string $description,
        public float $quantity,
        public float $unitPrice,
    ) {}
}
