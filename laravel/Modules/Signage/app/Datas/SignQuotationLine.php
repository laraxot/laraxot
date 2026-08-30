<?php

declare(strict_types=1);

namespace Modules\Signage\Datas;

/**
 * One priced line of a Signage quotation: a component matched to a price-list
 * item, with quantity and line total. `needsInput` flags lines the operator
 * still has to complete (no item matched, or a quantity that can't be derived
 * from the drawing geometry — e.g. milling metres or labour minutes).
 */
final readonly class SignQuotationLine
{
    public function __construct(
        public string $role,
        public string $label,
        public ?string $priceListItemCode,
        public ?string $priceListItemName,
        public ?string $category,
        public ?string $unit,
        public ?float $quantity,
        public ?float $unitCost,
        public ?float $lineTotal,
        public bool $needsInput,
        public ?string $note = null,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'role' => $this->role,
            'label' => $this->label,
            'price_list_item_code' => $this->priceListItemCode,
            'price_list_item_name' => $this->priceListItemName,
            'category' => $this->category,
            'unit' => $this->unit,
            'quantity' => $this->quantity,
            'unit_cost' => $this->unitCost,
            'line_total' => $this->lineTotal,
            'needs_input' => $this->needsInput,
            'note' => $this->note,
        ];
    }
}
