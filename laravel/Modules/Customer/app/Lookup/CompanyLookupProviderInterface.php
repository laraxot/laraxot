<?php

declare(strict_types=1);

namespace Modules\Customer\Lookup;

interface CompanyLookupProviderInterface
{
    public function search(string $query): array;
}
