<?php

declare(strict_types=1);

namespace Modules\Signage\Datas;

/**
 * One row of a catalog item's distinta, costed and marked up. Keeps both halves
 * visible because the customer wants the calculation of every single line on
 * screen, not just the final price.
 */
final readonly class SignCatalogCostLine
{
    public function __construct(
        public int $position,
        public string $workCenterCode,
        public ?string $supplierItemCode,
        public ?string $supplierName,
        public string $description,
        public string $unitCode,
        public float $quantity,
        public ?float $widthMm,
        public ?float $heightMm,
        public float $unitCost,
        public float $lineCost,
        public float $markupPercent,
        public float $linePrice,
        /** Where the markup came from: item|work_center|price_list|config. */
        public string $markupSourceCode,
    ) {}

    public function lineMargin(): float
    {
        return round($this->linePrice - $this->lineCost, 2);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'position' => $this->position,
            'work_center_code' => $this->workCenterCode,
            'supplier_item_code' => $this->supplierItemCode,
            'supplier_name' => $this->supplierName,
            'description' => $this->description,
            'unit_code' => $this->unitCode,
            'quantity' => $this->quantity,
            'width_mm' => $this->widthMm,
            'height_mm' => $this->heightMm,
            'unit_cost' => $this->unitCost,
            'line_cost' => $this->lineCost,
            'markup_percent' => $this->markupPercent,
            'line_price' => $this->linePrice,
            'markup_source_code' => $this->markupSourceCode,
            'line_margin' => $this->lineMargin(),
        ];
    }
}
