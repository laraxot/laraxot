<?php

declare(strict_types=1);

namespace Modules\Customer\Lookup;

use Illuminate\Support\Facades\Http;

final class OpenApiCompanyLookupProvider implements CompanyLookupProviderInterface
{
    private const MAX_RESULTS = 5;

    public function search(string $query): array
    {
        $token = config('company_lookup.openapi_token');
        $token = is_string($token) ? trim($token) : '';
        $name = trim($query);
        if ($token === '' || mb_strlen($name) < 2) {
            return [];
        }

        $base = config('company_lookup.openapi_base_url', 'https://company.openapi.com');
        $base = is_string($base) && trim($base) !== '' ? rtrim($base, '/') : 'https://company.openapi.com';
        $timeout = config('company_lookup.timeout', 15);
        $timeout = is_numeric($timeout) ? (int) $timeout : 15;

        $vat = $this->extractItalianVat($name);

        try {
            $request = Http::withToken($token)->acceptJson()->timeout($timeout);
            $response = $vat !== null
                ? $request->get($base.'/IT-advanced/'.rawurlencode($vat))
                : $request->get($base.'/IT-search', ['companyName' => $name, 'dataEnrichment' => 'start', 'limit' => self::MAX_RESULTS]);
        } catch (\Throwable) {
            return [];
        }

        if (! $response->successful()) {
            return [];
        }

        $rows = $response->json('data');
        if (! is_array($rows)) {
            return [];
        }

        $out = [];
        foreach ($rows as $row) {
            if (! is_array($row)) {
                continue;
            }
            $candidate = $this->mapRow($row);
            if ($candidate !== null) {
                $out[] = $candidate;
            }
            if (count($out) >= self::MAX_RESULTS) {
                break;
            }
        }

        return $out;
    }

    private function mapRow(array $row): ?array
    {
        $legalName = $this->nullableString($row['companyName'] ?? null);
        if ($legalName === null) {
            return null;
        }

        $address = is_array($row['address'] ?? null) ? $row['address'] : [];
        $office = is_array($address['registeredOffice'] ?? null) ? $address['registeredOffice'] : [];
        $contacts = is_array($row['contacts'] ?? null) ? $row['contacts'] : [];
        $mail = is_array($row['mail'] ?? null) ? $row['mail'] : [];

        $phone = $this->nullableString($contacts['telephoneNumber'] ?? null)
            ?? $this->nullableString($contacts['phone'] ?? null);
        $email = $this->nullableString($mail['email'] ?? null)
            ?? $this->nullableString($row['pec'] ?? null);

        return [
            'legal_name' => $legalName,
            'vat_number' => $this->nullableString($row['vatCode'] ?? null),
            'tax_code' => $this->nullableString($row['taxCode'] ?? null),
            'address_line1' => $this->street($office),
            'city' => $this->nullableString($office['town'] ?? null),
            'postal_code' => $this->nullableString($office['zipCode'] ?? null),
            'province' => $this->nullableString($office['province'] ?? null),
            'phone' => $phone,
            'email' => $email,
        ];
    }

    private function extractItalianVat(string $query): ?string
    {
        $normalized = preg_replace('/\s+/', '', $query) ?? '';
        if (preg_match('/(?:IT)?(\d{11})/i', $normalized, $matches) === 1) {
            return $matches[1];
        }

        return null;
    }

    private function street(array $office): ?string
    {
        $parts = array_filter([
            $this->nullableString($office['streetName'] ?? null),
            $this->nullableString($office['streetNumber'] ?? null),
        ], static fn (?string $v): bool => $v !== null);

        $line = trim(implode(' ', $parts));

        return $line !== '' ? $line : null;
    }

    private function nullableString(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }
        $trimmed = trim($value);

        return $trimmed !== '' ? $trimmed : null;
    }
}
