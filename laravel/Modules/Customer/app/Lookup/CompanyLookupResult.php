<?php

declare(strict_types=1);

namespace Modules\Customer\Lookup;

final readonly class CompanyLookupResult
{
    public function __construct(
        public string $legalName,
        public ?string $vatNumber = null,
        public ?string $taxCode = null,
        public ?string $addressLine1 = null,
        public ?string $city = null,
        public ?string $postalCode = null,
        public ?string $province = null,
        public ?string $phone = null,
        public ?string $email = null,
    ) {}

    /**
     * @return array<string, string|null>
     */
    public function toArray(): array
    {
        return [
            'legal_name' => $this->legalName,
            'vat_number' => $this->vatNumber,
            'tax_code' => $this->taxCode,
            'address_line1' => $this->addressLine1,
            'city' => $this->city,
            'postal_code' => $this->postalCode,
            'province' => $this->province,
            'phone' => $this->phone,
            'email' => $this->email,
        ];
    }
}
