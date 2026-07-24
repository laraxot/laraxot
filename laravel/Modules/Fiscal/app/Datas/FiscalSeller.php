<?php

declare(strict_types=1);

namespace Modules\Fiscal\Datas;

/**
 * Cedente/prestatore (seller) fiscal identity for an e-invoice.
 * Fully resolved by the calling vertical from its own installation config.
 */
final readonly class FiscalSeller
{
    public function __construct(
        public string $country,
        public string $vatNumber,
        public string $name,
        public string $taxRegime,
        public string $address,
        public string $postalCode,
        public string $city,
        public string $province,
    ) {}
}
