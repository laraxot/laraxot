<?php

declare(strict_types=1);

namespace Modules\Customer\Lookup;

interface CompanyLookupProviderInterface
{
    /**
     * @return list<array<string, mixed>>
     */
    public function search(string $query): array;
}
