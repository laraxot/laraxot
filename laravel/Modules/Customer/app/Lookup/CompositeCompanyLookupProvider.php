<?php

declare(strict_types=1);

namespace Modules\Customer\Lookup;

final class CompositeCompanyLookupProvider implements CompanyLookupProviderInterface
{
    /** @var list<CompanyLookupProviderInterface> */
    private readonly array $providers;

    /**
     * @param  list<CompanyLookupProviderInterface>  $providers
     */
    public function __construct(
        array $providers,
    ) {
        $this->providers = array_values($providers);
    }

    /**
     * @return list<array<string, mixed>>
     */
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
