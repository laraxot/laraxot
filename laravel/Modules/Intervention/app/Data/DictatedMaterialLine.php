<?php

declare(strict_types=1);

namespace Modules\Intervention\Data;

/**
 * A single material line dictated by an operator via voice.
 *
 * Immutable. Starts "free" (no catalog match) and can be enriched with a
 * catalog match that fills in the billing price so the extra material gets
 * invoiced instead of gifted.
 */
final readonly class DictatedMaterialLine
{
    /** @var string */
    public const DEFAULT_UNIT = 'pz';

    public function __construct(
        public string $description,
        public float $quantity,
        public ?float $unitPrice = null,
        public ?int $matchedProductId = null,
        public string $unit = self::DEFAULT_UNIT,
    ) {}

    public function isMatched(): bool
    {
        return $this->matchedProductId !== null;
    }

    public function lineTotal(): float
    {
        return round($this->quantity * ($this->unitPrice ?? 0.0), 2);
    }

    /**
     * Returns a copy linked to a catalog product. When the product carries a
     * price it overrides the dictated one; otherwise the dictated price stays.
     */
    public function withCatalogMatch(int $productId, ?float $unitPrice): self
    {
        return new self(
            description: $this->description,
            quantity: $this->quantity,
            unitPrice: $unitPrice ?? $this->unitPrice,
            matchedProductId: $productId,
            unit: $this->unit,
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'description' => $this->description,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'unit_price' => $this->unitPrice,
            'matched_product_id' => $this->matchedProductId,
            'line_total' => $this->lineTotal(),
            'is_matched' => $this->isMatched(),
        ];
    }
}
