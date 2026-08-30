<?php

declare(strict_types=1);

namespace Modules\MetalWork\Datas;

/**
 * A supplier invoice reduced to what the price list needs: who sold, when, and
 * the material rows. Accessory rows (carriage, collection fees) are dropped by
 * the parser — they are not materials and would pollute the price list.
 */
final readonly class SupplierInvoice
{
    /**
     * @param  list<SupplierInvoiceLine>  $lines
     */
    public function __construct(
        public string $supplierName,
        public ?string $invoiceNumber,
        public ?string $issuedOn,
        public string $sourceFile,
        public array $lines,
    ) {}

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'supplier_name' => $this->supplierName,
            'invoice_number' => $this->invoiceNumber,
            'issued_on' => $this->issuedOn,
            'source_file' => $this->sourceFile,
            'lines' => array_map(static fn (SupplierInvoiceLine $l): array => $l->toArray(), $this->lines),
        ];
    }
}
