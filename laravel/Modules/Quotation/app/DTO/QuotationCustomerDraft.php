<?php

declare(strict_types=1);

namespace Modules\Quotation\DTO;

/**
 * Customer data extracted from a voice dictation and/or an online company lookup,
 * used to resolve an existing customer or create a new one for the quotation.
 * Immutable and framework-free.
 */
final readonly class QuotationCustomerDraft
{
    public function __construct(
        public string $legalName,
        public ?string $vatNumber = null,
        public ?string $taxCode = null,
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $addressLine1 = null,
        public ?string $city = null,
        public ?string $postalCode = null,
        public ?string $province = null,
        public ?string $notes = null,
    ) {}

    /**
     * True when there is enough to identify/create a customer.
     */
    public function hasIdentity(): bool
    {
        return trim($this->legalName) !== '' || ($this->vatNumber !== null && trim($this->vatNumber) !== '');
    }

    /**
     * Attributes to persist on a customers row.
     *
     * @return array<string, mixed>
     */
    public function toCustomerAttributes(): array
    {
        return [
            'legal_name' => trim($this->legalName),
            'vat_number' => $this->vatNumber,
            'tax_code' => $this->taxCode,
            'email' => $this->email,
            'phone' => $this->phone,
            'address_line1' => $this->addressLine1,
            'city' => $this->city,
            'postal_code' => $this->postalCode,
            'province' => $this->province,
            'notes' => $this->notes,
        ];
    }
}
