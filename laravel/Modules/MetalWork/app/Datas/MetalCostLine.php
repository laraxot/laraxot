<?php

declare(strict_types=1);

namespace Modules\MetalWork\Datas;

/**
 * One row of an estimate — the material, or one machining step — costed and
 * marked up. Both halves stay visible because what the operator checks before
 * sending a quotation is the calculation, not the final number.
 *
 * `driverCode` says which piece of geometry produced the quantity (cut length,
 * hole count, ...), so a row that looks wrong can be traced back to the drawing.
 */
final readonly class MetalCostLine
{
    public function __construct(
        public int $position,
        public string $workCenterCode,
        public string $description,
        public string $unitCode,
        public float $quantity,
        public float $unitCost,
        public float $lineCost,
        public float $markupPercent,
        public float $linePrice,
        /** Where the markup came from: estimate|work_center|config. */
        public string $markupSourceCode,
        public ?string $driverCode,
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
            'description' => $this->description,
            'unit_code' => $this->unitCode,
            'quantity' => $this->quantity,
            'unit_cost' => $this->unitCost,
            'line_cost' => $this->lineCost,
            'markup_percent' => $this->markupPercent,
            'line_price' => $this->linePrice,
            'markup_source_code' => $this->markupSourceCode,
            'driver_code' => $this->driverCode,
            'line_margin' => $this->lineMargin(),
        ];
    }
}
