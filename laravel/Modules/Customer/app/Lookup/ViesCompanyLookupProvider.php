<?php

declare(strict_types=1);

namespace Modules\Customer\Lookup;

use Illuminate\Support\Facades\Http;

use function Safe\preg_match;
use function Safe\preg_replace;

final class ViesCompanyLookupProvider implements CompanyLookupProviderInterface
{
    /** @var string */
    private const ENDPOINT = 'https://ec.europa.eu/taxation_customs/vies/rest-api/ms/IT/vat/';

    /**
     * @return list<array<string, mixed>>
     */
    public function search(string $query): array
    {
        $vat = $this->extractItalianVat($query);
        if ($vat === null) {
            return [];
        }

        $timeout = config('company_lookup.vies_timeout', 8);

        try {
            $response = Http::acceptJson()
                ->timeout(is_numeric($timeout) ? (int) $timeout : 8)
                ->get(self::ENDPOINT.$vat);
        } catch (\Throwable) {
            return [];
        }

        if (! $response->successful()) {
            return [];
        }

        $data = $response->json();
        if (! is_array($data) || ($data['isValid'] ?? null) !== true) {
            return [];
        }

        $nameValue = $data['name'] ?? null;
        $name = is_string($nameValue) ? trim($nameValue) : '';
        if ($name === '' || $name === '---') {
            return [];
        }

        $addressValue = $data['address'] ?? null;
        [$street, $city, $postalCode, $province] = $this->parseAddress(is_string($addressValue) ? $addressValue : '');

        return [[
            'legal_name' => $name,
            'vat_number' => 'IT'.$vat,
            'tax_code' => $vat,
            'address_line1' => $street,
            'city' => $city,
            'postal_code' => $postalCode,
            'province' => $province,
            'phone' => null,
            'email' => null,
        ]];
    }

    private function extractItalianVat(string $query): ?string
    {
        $normalized = preg_replace('/\s+/', '', $query);
        if (! is_string($normalized)) {
            return null;
        }
        $matches = [];
        if (preg_match('/(?:IT)?(\d{11})/i', $normalized, $matches) === 1
            && is_string($matches[1] ?? null)
        ) {
            return $matches[1];
        }

        return null;
    }

    /**
     * @return array{0: ?string, 1: ?string, 2: ?string, 3: ?string}
     */
    private function parseAddress(string $address): array
    {
        $replaced = preg_replace('/\s+/', ' ', str_replace(["\n", "\r"], ' ', $address));
        if (! is_string($replaced)) {
            return [null, null, null, null];
        }
        $flat = trim($replaced);
        if ($flat === '') {
            return [null, null, null, null];
        }

        $m = [];
        if (preg_match('/^(.*?)\s(\d{5})\s(.+?)(?:\s([A-Z]{2}))?$/', $flat, $m) === 1) {
            $streetRaw = isset($m[1]) && is_string($m[1]) ? trim($m[1]) : '';
            $postalCode = isset($m[2]) && is_string($m[2]) ? $m[2] : null;
            $cityRaw = isset($m[3]) && is_string($m[3]) ? trim($m[3]) : '';
            $province = isset($m[4]) && is_string($m[4]) ? $m[4] : null;

            return [
                $streetRaw !== '' ? $streetRaw : null,
                $cityRaw !== '' ? $cityRaw : null,
                $postalCode,
                $province,
            ];
        }

        return [$flat, null, null, null];
    }
}
