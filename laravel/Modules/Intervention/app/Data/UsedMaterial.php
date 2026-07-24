<?php

declare(strict_types=1);

namespace Modules\Intervention\Data;

/**
 * Materiale effettivamente impiegato durante l'intervento.
 */
final readonly class UsedMaterial
{
    public function __construct(
        public int $materialLineId,
        public string $description,
        public float $quantity,
        public float $unitPrice,
    ) {}
}
