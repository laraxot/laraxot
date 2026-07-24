<?php

declare(strict_types=1);

namespace Modules\Customer\Actions;

use Modules\Customer\Lookup\CompanyLookupProviderInterface;
use Modules\Customer\Lookup\CompanyLookupResult;
use Spatie\QueueableAction\QueueableAction;

final class SearchCompanyOnlineAction
{
    use QueueableAction;

    private const MAX_RESULTS = 5;

    public function __construct(
        private readonly CompanyLookupProviderInterface $provider,
    ) {}

    /**
     * @return list<CompanyLookupResult>
     */
    public function execute(string $query): array
    {
        if (trim($query) === '') {
            return [];
        }

        $results = [];
        foreach ($this->provider->search($query) as $candidate) {
            if (! is_array($candidate)) {
                continue;
            }

            $legalName = $this->str($candidate['legal_name'] ?? '');
            if ($legalName === '') {
                continue;
            }

            $results[] = new CompanyLookupResult(
                legalName: $legalName,
                vatNumber: $this->nullableStr($candidate['vat_number'] ?? null),
                taxCode: $this->nullableStr($candidate['tax_code'] ?? null),
                addressLine1: $this->nullableStr($candidate['address_line1'] ?? null),
                city: $this->nullableStr($candidate['city'] ?? null),
                postalCode: $this->nullableStr($candidate['postal_code'] ?? null),
                province: $this->nullableStr($candidate['province'] ?? null),
                phone: $this->nullableStr($candidate['phone'] ?? null),
                email: $this->nullableStr($candidate['email'] ?? null),
            );

            if (count($results) >= self::MAX_RESULTS) {
                break;
            }
        }

        return $results;
    }

    private function str(mixed $value): string
    {
        return is_string($value) ? trim($value) : '';
    }

    private function nullableStr(mixed $value): ?string
    {
        $string = $this->str($value);

        return $string === '' ? null : $string;
    }
}
