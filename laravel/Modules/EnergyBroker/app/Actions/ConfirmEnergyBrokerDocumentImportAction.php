<?php

declare(strict_types=1);

namespace Modules\EnergyBroker\Actions;

use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Facades\DB;
use Modules\Customer\Actions\CreateCustomerAction;
use Modules\Customer\Actions\CreateCustomerSiteAction;
use Modules\Customer\Actions\UpdateCustomerAction;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\EnergyBroker\Models\EnergyBrokerCustomerMatch;
use Modules\EnergyBroker\Models\EnergyBrokerDocument;
use Modules\EnergyBroker\Models\EnergyBrokerSupplyHistory;
use Modules\EnergyBroker\Models\EnergyBrokerSupplyPoint;
use Spatie\QueueableAction\QueueableAction;

use function Safe\preg_match;
use function Safe\preg_replace;

final class ConfirmEnergyBrokerDocumentImportAction
{
    use QueueableAction;

    public function __construct(
        private CreateCustomerAction $createCustomer,
        private CreateCustomerSiteAction $createCustomerSite,
        private UpdateCustomerAction $updateCustomer,
    ) {}

    /**
     * @return array{status: string, customer_id?: int}
     */
    public function execute(EnergyBrokerDocument $document): array
    {
        /** @var array<string, mixed> $payload */
        $payload = is_array($document->normalized_payload) ? $document->normalized_payload : [];
        /** @var array<string, mixed> $customerData */
        $customerData = is_array($payload['customer'] ?? null) ? $payload['customer'] : [];
        /** @var array<string, mixed> $supplyData */
        $supplyData = is_array($payload['supply'] ?? null) ? $payload['supply'] : [];
        /** @var array<string, mixed> $contractData */
        $contractData = is_array($payload['contract'] ?? null) ? $payload['contract'] : [];
        /** @var array<string, mixed> $documentData */
        $documentData = is_array($payload['document'] ?? null) ? $payload['document'] : [];

        $strongCustomer = $this->findStrongCustomerMatch($customerData);
        if ($strongCustomer instanceof Customer) {
            return DB::transaction(function () use ($document, $strongCustomer, $customerData, $supplyData, $contractData, $documentData): array {
                $customer = $this->updateCustomer->execute($strongCustomer, $this->customerAttributes($customerData, mergeOnlyMissing: true));
                $site = $this->firstOrCreateSite($customer, $customerData, $supplyData);
                $this->syncSupplyPoints($customer, $site, $document, $supplyData, $contractData, $documentData);

                $document->forceFill([
                    'customer_id' => $customer->id,
                    'analysis_status' => 'imported',
                    'imported_at' => now(),
                ])->save();

                return [
                    'status' => 'imported',
                    'customer_id' => $customer->id,
                ];
            });
        }

        $candidates = $this->findReviewCandidates($customerData, $supplyData);
        if ($candidates->isNotEmpty()) {
            foreach ($candidates as $candidate) {
                EnergyBrokerCustomerMatch::query()->create([
                    'energy_broker_document_id' => $document->id,
                    'customer_id' => $candidate->id,
                    'score' => $this->candidateScore($candidate, $customerData),
                    'status' => 'needs_review',
                    'matched_on' => [
                        'legal_name' => $candidate->legal_name === ($customerData['legal_name'] ?? null),
                        'address_line1' => $candidate->getAttribute('address_line1') === ($customerData['address_line1'] ?? null),
                        'tax_code_equal' => $candidate->tax_code !== null && $candidate->tax_code === ($customerData['tax_code'] ?? null),
                    ],
                    'reason' => 'Anagrafica simile ma non abbastanza forte per merge automatico.',
                ]);
            }

            $document->forceFill([
                'analysis_status' => 'needs_review',
            ])->save();

            return ['status' => 'needs_review'];
        }

        return DB::transaction(function () use ($document, $customerData, $supplyData, $contractData, $documentData): array {
            $customer = $this->createCustomer->execute($this->customerAttributes($customerData));
            $site = $this->firstOrCreateSite($customer, $customerData, $supplyData);
            $this->syncSupplyPoints($customer, $site, $document, $supplyData, $contractData, $documentData);

            $document->forceFill([
                'customer_id' => $customer->id,
                'analysis_status' => 'imported',
                'imported_at' => now(),
            ])->save();

            return [
                'status' => 'imported',
                'customer_id' => $customer->id,
            ];
        });
    }

    /**
     * @param  array<string, mixed>  $customerData
     */
    private function findStrongCustomerMatch(array $customerData): ?Customer
    {
        $taxCode = $this->normalizedString($customerData['tax_code'] ?? null);
        if ($taxCode !== null) {
            /** @var Customer|null $customer */
            $customer = Customer::query()->where('tax_code', $taxCode)->first();

            return $customer;
        }

        return null;
    }

    /**
     * @param  array<string, mixed>  $customerData
     * @param  array<string, mixed>  $supplyData
     * @return EloquentCollection<int, Customer>
     */
    private function findReviewCandidates(array $customerData, array $supplyData): EloquentCollection
    {
        $legalName = $this->normalizedString($customerData['legal_name'] ?? null);
        $pod = $this->normalizedString($supplyData['pod'] ?? null);
        $pdr = $this->normalizedString($supplyData['pdr'] ?? null);

        $query = Customer::query();
        if ($legalName !== null) {
            $query->where('legal_name', $legalName);
        }

        $customers = $query->get();
        if ($customers->isNotEmpty()) {
            return $customers;
        }

        if ($pod === null && $pdr === null) {
            return new EloquentCollection();
        }

        $supplyPointQuery = EnergyBrokerSupplyPoint::query();
        if ($pod !== null) {
            $supplyPointQuery->orWhere('pod', $pod);
        }
        if ($pdr !== null) {
            $supplyPointQuery->orWhere('pdr', $pdr);
        }

        $customerIds = $supplyPointQuery->pluck('customer_id')->filter()->unique()->values();

        return Customer::query()->whereIn('id', $customerIds)->get();
    }

    /**
     * @param  array<string, mixed>  $customerData
     * @return array<string, mixed>
     */
    private function customerAttributes(array $customerData, bool $mergeOnlyMissing = false): array
    {
        $attributes = array_filter([
            'legal_name' => $this->normalizedString($customerData['legal_name'] ?? null),
            'tax_code' => $this->normalizedString($customerData['tax_code'] ?? null),
            'vat_number' => $this->normalizedString($customerData['vat_number'] ?? null),
            'email' => $this->normalizedString($customerData['email'] ?? null),
            'phone' => $this->normalizedString($customerData['phone'] ?? null),
            'payment_method' => $this->normalizedString($customerData['payment_method'] ?? null),
            'iban' => $this->normalizedString($customerData['iban'] ?? null),
            'address_line1' => $this->normalizedString($customerData['address_line1'] ?? null),
            'address_line2' => $this->normalizedString($customerData['address_line2'] ?? null),
            'city' => $this->normalizedString($customerData['city'] ?? null),
            'postal_code' => $this->normalizedString($customerData['postal_code'] ?? null),
            'province' => $this->normalizedString($customerData['province'] ?? null),
            'country_code' => 'IT',
        ], static fn (mixed $value): bool => $value !== null);

        if (! $mergeOnlyMissing) {
            return $attributes;
        }

        return array_filter($attributes, static fn (mixed $value): bool => $value !== null);
    }

    /**
     * @param  array<string, mixed>  $customerData
     * @param  array<string, mixed>  $supplyData
     */
    private function firstOrCreateSite(Customer $customer, array $customerData, array $supplyData): CustomerSite
    {
        $address = $this->normalizedString($supplyData['supply_address'] ?? null)
            ?? $this->normalizedString($customerData['address_line1'] ?? null);

        /** @var CustomerSite|null $site */
        $site = $customer->sites()
            ->where('address_line1', $address)
            ->first();

        if ($site instanceof CustomerSite) {
            return $site;
        }

        return $this->createCustomerSite->execute($customer, [
            'name' => $customer->legal_name.' - Fornitura',
            'address_line1' => $address,
            'city' => $this->normalizedString($customerData['city'] ?? null),
            'postal_code' => $this->normalizedString($customerData['postal_code'] ?? null),
            'province' => $this->normalizedString($customerData['province'] ?? null),
            'country_code' => 'IT',
            'email' => $this->normalizedString($customerData['email'] ?? null),
            'phone' => $this->normalizedString($customerData['phone'] ?? null),
        ]);
    }

    /**
     * @param  array<string, mixed>  $supplyData
     * @param  array<string, mixed>  $contractData
     * @param  array<string, mixed>  $documentData
     */
    private function syncSupplyPoints(
        Customer $customer,
        CustomerSite $site,
        EnergyBrokerDocument $document,
        array $supplyData,
        array $contractData,
        array $documentData,
    ): void {
        $serviceType = $this->normalizedString($supplyData['service_type'] ?? null);
        $histories = [];

        if (in_array($serviceType, ['electricity', 'dual'], true) && $this->normalizedString($supplyData['pod'] ?? null) !== null) {
            $supplyPoint = $this->firstOrCreateSupplyPoint(
                customer: $customer,
                site: $site,
                serviceType: 'electricity',
                identifierField: 'pod',
                identifierValue: (string) $supplyData['pod'],
                annualConsumption: $this->normalizedNumber($supplyData['annual_consumption_electricity'] ?? $supplyData['annual_consumption'] ?? null),
                powerKw: $this->normalizedNumber($supplyData['power_kw'] ?? null),
                supplierName: $this->normalizedString($contractData['proposed_supplier'] ?? null) ?? $this->normalizedString($contractData['current_supplier'] ?? null),
                offerName: $this->normalizedString($contractData['offer_name'] ?? null),
            );

            $histories[] = [
                'supply_point' => $supplyPoint,
                'offer_code' => $this->normalizedString($contractData['offer_code_electricity'] ?? null),
            ];
        }

        if (in_array($serviceType, ['gas', 'dual'], true) && $this->normalizedString($supplyData['pdr'] ?? null) !== null) {
            $supplyPoint = $this->firstOrCreateSupplyPoint(
                customer: $customer,
                site: $site,
                serviceType: 'gas',
                identifierField: 'pdr',
                identifierValue: (string) $supplyData['pdr'],
                annualConsumption: $this->normalizedNumber($supplyData['annual_consumption_gas'] ?? $supplyData['annual_consumption'] ?? null),
                powerKw: null,
                supplierName: $this->normalizedString($contractData['proposed_supplier'] ?? null) ?? $this->normalizedString($contractData['current_supplier'] ?? null),
                offerName: $this->normalizedString($contractData['offer_name'] ?? null),
            );

            $histories[] = [
                'supply_point' => $supplyPoint,
                'offer_code' => $this->normalizedString($contractData['offer_code_gas'] ?? null),
            ];
        }

        foreach ($histories as $history) {
            /** @var EnergyBrokerSupplyPoint $supplyPoint */
            $supplyPoint = $history['supply_point'];

            EnergyBrokerSupplyHistory::query()->firstOrCreate([
                'energy_broker_supply_point_id' => $supplyPoint->id,
                'energy_broker_document_id' => $document->id,
                'supplier_name' => (string) ($supplyPoint->supplier_name ?? ''),
            ], [
                'offer_name' => $supplyPoint->offer_name,
                'offer_code' => $history['offer_code'],
                'status' => $this->normalizedString($contractData['proposed_supplier'] ?? null) !== null ? 'proposed' : 'current',
                'started_at' => $this->normalizedDate($contractData['switch_requested_at'] ?? $documentData['issued_at'] ?? null),
                'meta' => [
                    'source_document_number' => $documentData['document_number'] ?? null,
                ],
            ]);
        }
    }

    private function firstOrCreateSupplyPoint(
        Customer $customer,
        CustomerSite $site,
        string $serviceType,
        string $identifierField,
        string $identifierValue,
        ?string $annualConsumption,
        ?string $powerKw,
        ?string $supplierName,
        ?string $offerName,
    ): EnergyBrokerSupplyPoint {
        /** @var EnergyBrokerSupplyPoint|null $existing */
        $existing = EnergyBrokerSupplyPoint::query()
            ->where('customer_id', $customer->id)
            ->where($identifierField, $identifierValue)
            ->first();

        if ($existing instanceof EnergyBrokerSupplyPoint) {
            $existing->forceFill([
                'customer_site_id' => $site->id,
                'service_type' => $serviceType,
                'annual_consumption' => $annualConsumption,
                'power_kw' => $powerKw,
                'supplier_name' => $supplierName,
                'offer_name' => $offerName,
            ])->save();

            return $existing;
        }

        /** @var EnergyBrokerSupplyPoint $supplyPoint */
        $supplyPoint = EnergyBrokerSupplyPoint::query()->create([
            'customer_id' => $customer->id,
            'customer_site_id' => $site->id,
            'service_type' => $serviceType,
            'pod' => $identifierField === 'pod' ? $identifierValue : null,
            'pdr' => $identifierField === 'pdr' ? $identifierValue : null,
            'annual_consumption' => $annualConsumption,
            'power_kw' => $powerKw,
            'supplier_name' => $supplierName,
            'offer_name' => $offerName,
        ]);

        return $supplyPoint;
    }

    /**
     * @param  array<string, mixed>  $customerData
     */
    private function candidateScore(Customer $customer, array $customerData): float
    {
        $score = 0.0;

        if ($customer->legal_name === ($customerData['legal_name'] ?? null)) {
            $score += 60.0;
        }

        $addressLine1 = $customer->getAttribute('address_line1');
        if ($addressLine1 !== null && $addressLine1 === ($customerData['address_line1'] ?? null)) {
            $score += 20.0;
        }

        $postalCode = $customer->getAttribute('postal_code');
        if ($postalCode !== null && $postalCode === ($customerData['postal_code'] ?? null)) {
            $score += 10.0;
        }

        $province = $customer->getAttribute('province');
        if ($province !== null && $province === ($customerData['province'] ?? null)) {
            $score += 10.0;
        }

        return $score;
    }

    private function normalizedString(mixed $value): ?string
    {
        if (! is_scalar($value)) {
            return null;
        }

        $normalized = trim((string) $value);

        return $normalized !== '' ? $normalized : null;
    }

    private function normalizedNumber(mixed $value): ?string
    {
        $string = $this->normalizedString($value);
        if ($string === null) {
            return null;
        }

        $string = str_replace('.', '', $string);
        $string = str_replace(',', '.', $string);
        $string = preg_replace('/[^0-9.]+/', '', $string);

        return $string !== '' ? $string : null;
    }

    private function normalizedDate(mixed $value): ?string
    {
        $string = $this->normalizedString($value);
        if ($string === null) {
            return null;
        }

        return preg_match('/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/', $string) === 1 ? $string : null;
    }
}
