<?php

declare(strict_types=1);

namespace Modules\Quotation\Domain\DTO;

/**
 * A single quotation line coming from a voice dictation or cloned from a source
 * quotation. Immutable; starts "free" and can be enriched with a catalog match
 * that fills the price so the material is priced against the listino/products.
 */
final readonly class DictatedQuotationItem
{
    public function __construct(
        public string $description,
        public float $quantity,
        public ?float $unitPrice = null,
        public ?int $matchedProductId = null,
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
        );
    }

    /** @return array<string, mixed> */
    public function toArray(): array
    {
        return [
            'description' => $this->description,
            'quantity' => $this->quantity,
            'unit_price' => $this->unitPrice,
            'matched_product_id' => $this->matchedProductId,
            'line_total' => $this->lineTotal(),
            'is_matched' => $this->isMatched(),
        ];
    }
}
