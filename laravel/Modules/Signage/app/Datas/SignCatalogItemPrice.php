<?php

declare(strict_types=1);

namespace Modules\Signage\Datas;

/**
 * The costing of one catalog item: what the distinta costs, what it sells for,
 * and the two percentages the customer's gestionale shows next to the price —
 * margin on the sale price (their "MAG") and markup on the cost.
 *
 * `isManualPrice` says the sale price was typed by hand and won over anything
 * computed; the percentages are then derived from it.
 */
final readonly class SignCatalogItemPrice
{
    /**
     * @param  list<SignCatalogCostLine>  $lines
     */
    public function __construct(
        public string $code,
        public string $name,
        public array $lines,
        public float $totalCost,
        public float $salePrice,
        public bool $isManualPrice,
    ) {}

    public function margin(): float
    {
        return round($this->salePrice - $this->totalCost, 2);
    }

    /** Margin as a share of the sale price — the customer's "MAG %". */
    public function marginPercent(): ?float
    {
        if ($this->salePrice === 0.0) {
            return null;
        }

        return round($this->margin() / $this->salePrice * 100, 2);
    }

    /** Margin as a share of the cost — the customer's second percentage. */
    public function markupOnCostPercent(): ?float
    {
        if ($this->totalCost === 0.0) {
            return null;
        }

        return round($this->margin() / $this->totalCost * 100, 2);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'name' => $this->name,
            'lines' => array_map(static fn (SignCatalogCostLine $l): array => $l->toArray(), $this->lines),
            'total_cost' => $this->totalCost,
            'sale_price' => $this->salePrice,
            'is_manual_price' => $this->isManualPrice,
            'margin' => $this->margin(),
            'margin_percent' => $this->marginPercent(),
            'markup_on_cost_percent' => $this->markupOnCostPercent(),
        ];
    }
}
