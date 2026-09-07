<?php

declare(strict_types=1);

namespace Modules\Customer\Lookup;

use Illuminate\Support\Facades\Http;

final class OpenCorporatesCompanyLookupProvider implements CompanyLookupProviderInterface
{
    private const string ENDPOINT = 'https://api.opencorporates.com/v0.4/companies/search';

    private const int MAX_RESULTS = 5;

    /**
     * @return list<array<string, mixed>>
     */
    public function search(string $query): array
    {
        $name = trim($query);
        if ($name === '' || mb_strlen($name) < 2) {
            return [];
        }

        $params = ['q' => $name, 'jurisdiction_code' => 'it', 'per_page' => self::MAX_RESULTS];
        $token = config('company_lookup.opencorporates_token');
        if (is_string($token) && trim($token) !== '') {
            $params['api_token'] = trim($token);
        }

        $timeout = config('company_lookup.timeout', 15);

        try {
            $response = Http::acceptJson()
                ->timeout(is_numeric($timeout) ? (int) $timeout : 15)
                ->get(self::ENDPOINT, $params);
        } catch (\Throwable) {
            return [];
        }

        if (! $response->successful()) {
            return [];
        }

        $companies = $response->json('results.companies');
        if (! is_array($companies)) {
            return [];
        }

        $out = [];
        foreach ($companies as $entry) {
            $company = is_array($entry) ? ($entry['company'] ?? null) : null;
            if (! is_array($company)) {
                continue;
            }
            $legalName = $this->nullableString($company['name'] ?? null);
            if ($legalName === null) {
                continue;
            }

            $out[] = [
                'legal_name' => $legalName,
                'vat_number' => null,
                'tax_code' => null,
                'address_line1' => $this->nullableString($company['registered_address_in_full'] ?? null),
                'city' => null,
                'postal_code' => null,
                'province' => null,
                'phone' => null,
                'email' => null,
            ];

            if (count($out) >= self::MAX_RESULTS) {
                break;
            }
        }

        return $out;
    }

    private function nullableString(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }
        $trimmed = trim(str_replace(["\n", "\r"], ' ', $value));

        return $trimmed !== '' ? $trimmed : null;
    }
}
