<?php

declare(strict_types=1);

namespace Modules\MetalWork\Datas;

/**
 * The costing of one part: what it weighs, what it costs to make, what it sells
 * for, and the two percentages the workshop reads next to the price — margin on
 * the sale price and markup on the cost.
 *
 * `warnings` carries what the estimate could not resolve — no price on the
 * chosen material, no bounding box in the drawing. They are surfaced rather than
 * silently costed at zero: an invisible gap under-quotes the job.
 */
final readonly class MetalPartCosting
{
    /**
     * @param  list<MetalCostLine>  $lines
     * @param  list<string>  $warnings
     */
    public function __construct(
        public string $partCode,
        public string $name,
        public int $quantity,
        public float $netWeightKg,
        public float $grossWeightKg,
        public array $lines,
        public float $unitCost,
        public float $unitSalePrice,
        public bool $isManualPrice,
        public array $warnings,
    ) {}

    public function totalCost(): float
    {
        return round($this->unitCost * $this->quantity, 2);
    }

    public function totalSalePrice(): float
    {
        return round($this->unitSalePrice * $this->quantity, 2);
    }

    public function margin(): float
    {
        return round($this->unitSalePrice - $this->unitCost, 2);
    }

    /** Margin as a share of the sale price. */
    public function marginPercent(): ?float
    {
        if ($this->unitSalePrice === 0.0) {
            return null;
        }

        return round($this->margin() / $this->unitSalePrice * 100, 2);
    }

    /** Margin as a share of the cost. */
    public function markupOnCostPercent(): ?float
    {
        if ($this->unitCost === 0.0) {
            return null;
        }

        return round($this->margin() / $this->unitCost * 100, 2);
    }

    /** Price per kilo — how the customer sanity-checks a steel quotation. */
    public function pricePerKg(): ?float
    {
        if ($this->grossWeightKg <= 0.0) {
            return null;
        }

        return round($this->unitSalePrice / $this->grossWeightKg, 2);
    }

    public function hasWarnings(): bool
    {
        return $this->warnings !== [];
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'part_code' => $this->partCode,
            'name' => $this->name,
            'quantity' => $this->quantity,
            'net_weight_kg' => $this->netWeightKg,
            'gross_weight_kg' => $this->grossWeightKg,
            'lines' => array_map(static fn (MetalCostLine $l): array => $l->toArray(), $this->lines),
            'unit_cost' => $this->unitCost,
            'unit_sale_price' => $this->unitSalePrice,
            'is_manual_price' => $this->isManualPrice,
            'total_cost' => $this->totalCost(),
            'total_sale_price' => $this->totalSalePrice(),
            'margin' => $this->margin(),
            'margin_percent' => $this->marginPercent(),
            'markup_on_cost_percent' => $this->markupOnCostPercent(),
            'price_per_kg' => $this->pricePerKg(),
            'warnings' => $this->warnings,
        ];
    }
}
