<?php

declare(strict_types=1);

namespace Modules\Intervention\Domain\DTO;

/**
 * Riga di materiale coperta dal preventivo della commessa.
 */
final readonly class QuotedMaterial
{
    public function __construct(
        public int $quotationItemId,
        public string $description,
        public float $quantity,
    ) {}
}
