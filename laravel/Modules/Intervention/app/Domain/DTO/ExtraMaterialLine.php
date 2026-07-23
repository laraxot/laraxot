<?php

declare(strict_types=1);

namespace Modules\Intervention\Domain\DTO;

/**
 * Esito del confronto fra usato e preventivato per una singola riga materiale.
 */
final readonly class ExtraMaterialLine
{
    public function __construct(
        public int $materialLineId,
        public ?int $quotationItemId,
        public float $extraQuantity,
        public float $extraAmount,
    ) {}

    public function isExtra(): bool
    {
        return $this->extraQuantity > 0.0;
    }
}
