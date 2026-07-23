<?php

declare(strict_types=1);

namespace Modules\Customer\Lookup;

final class CompositeCompanyLookupProvider implements CompanyLookupProviderInterface
{
    private readonly array $providers;

    public function __construct(
        array $providers,
    ) {
        $this->providers = array_values(array_filter(
            $providers,
            static fn ($p) => $p instanceof CompanyLookupProviderInterface,
        ));
    }

    public function search(string $query): array
    {
        foreach ($this->providers as $provider) {
            $results = $provider->search($query);
            if ($results !== []) {
                return $results;
            }
        }

        return [];
    }
}
