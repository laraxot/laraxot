<?php

declare(strict_types=1);

namespace Modules\Timber\Legacy;

final readonly class EasyfattProductData
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        public string $productCode,
        public string $productName,
        public ?float $unitPrice,
        public array $payload = [],
    ) {}
}
