<?php

declare(strict_types=1);

namespace Modules\MetalWork\Datas;

/**
 * One purchased raw-material row read off a supplier invoice.
 *
 * `code` is the supplier's own article code — the only identifier that survives
 * across invoices, since the descriptions are re-typed and their numbers arrive
 * broken by the PDF kerning ("TUBO QUADRO 100X1 00X6").
 */
final readonly class SupplierInvoiceLine
{
    public function __construct(
        public string $code,
        public string $description,
        public string $familyCode,
        public string $unitCode,
        public float $quantity,
        public float $unitPrice,
        public float $lineTotal,
        public ?string $steelGrade,
        public ?string $deliveryNoteNumber,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'code' => $this->code,
            'description' => $this->description,
            'family_code' => $this->familyCode,
            'unit_code' => $this->unitCode,
            'quantity' => $this->quantity,
            'unit_price' => $this->unitPrice,
            'line_total' => $this->lineTotal,
            'steel_grade' => $this->steelGrade,
            'delivery_note_number' => $this->deliveryNoteNumber,
        ];
    }
}
