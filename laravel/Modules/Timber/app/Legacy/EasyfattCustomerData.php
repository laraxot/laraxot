<?php

declare(strict_types=1);

namespace Modules\Timber\Legacy;

final readonly class EasyfattCustomerData
{
    /**
     * @param  array<string, mixed>  $payload
     */
    public function __construct(
        public string $customerCode,
        public string $customerName,
        public ?string $vatNumber,
        public array $payload = [],
    ) {}
}
