<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Legacy;

use Illuminate\Database\ConnectionResolverInterface;

final readonly class ConfiguredEasyfattGateway implements EasyfattGateway
{
    public function __construct(
        private ConnectionResolverInterface $connections,
    ) {}

    /**
     * @return array<int, EasyfattCustomerData>
     */
    public function fetchCustomers(): array
    {
        $customersTable = config('timber.easyfatt.customers_table');
        $rows = $this->fetchFromConfiguredDatabase(is_string($customersTable) ? $customersTable : '')
            ?? $this->fallbackRows('timber.easyfatt.fallback_customers');

        $mapping = $this->columnMapping();
        $customers = [];

        foreach ($rows as $row) {
            $customers[] = new EasyfattCustomerData(
                customerCode: $this->stringValue($row[$mapping['customer_code']] ?? null),
                customerName: $this->stringValue($row[$mapping['customer_name']] ?? null),
                vatNumber: $this->nullableStringValue($row[$mapping['customer_vat_number']] ?? null),
                payload: $row,
            );
        }

        return $customers;
    }

    /**
     * @return array<int, EasyfattProductData>
     */
    public function fetchProducts(): array
    {
        $productsTable = config('timber.easyfatt.products_table');
        $rows = $this->fetchFromConfiguredDatabase(is_string($productsTable) ? $productsTable : '')
            ?? $this->fallbackRows('timber.easyfatt.fallback_products');

        $mapping = $this->columnMapping();
        $products = [];

        foreach ($rows as $row) {
            $unitPriceValue = $row[$mapping['product_unit_price']] ?? null;

            $products[] = new EasyfattProductData(
                productCode: $this->stringValue($row[$mapping['product_code']] ?? null),
                productName: $this->stringValue($row[$mapping['product_name']] ?? null),
                unitPrice: is_numeric($unitPriceValue) ? (float) $unitPriceValue : null,
                payload: $row,
            );
        }

        return $products;
    }

    /**
     * @return array<int, array<string, mixed>>|null
     */
    private function fetchFromConfiguredDatabase(string $table): ?array
    {
        $connectionName = config('timber.easyfatt.connection');
        $enabled = (bool) config('timber.easyfatt.enabled', false);

        if (! $enabled || ! is_string($connectionName) || $connectionName === '' || $table === '') {
            return null;
        }

        $rows = $this->connections->connection($connectionName)->table($table)->get();

        return $rows->map(static fn (object $row): array => (array) $row)->all();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    private function fallbackRows(string $configKey): array
    {
        $rows = config($configKey, []);
        if (! is_array($rows)) {
            return [];
        }

        $normalized = [];
        foreach ($rows as $row) {
            if (is_array($row)) {
                $normalized[] = $row;
            }
        }

        return $normalized;
    }

    /**
     * @return array<string, string>
     */
    private function columnMapping(): array
    {
        /** @var array<string, string> $defaults */
        $defaults = [
            'customer_code' => 'customer_code',
            'customer_name' => 'customer_name',
            'customer_vat_number' => 'vat_number',
            'product_code' => 'product_code',
            'product_name' => 'product_name',
            'product_unit_price' => 'unit_price',
        ];

        $configured = config('timber.easyfatt.columns', []);
        if (! is_array($configured)) {
            return $defaults;
        }

        foreach ($defaults as $key => $default) {
            $value = $configured[$key] ?? null;
            $defaults[$key] = is_string($value) && $value !== '' ? $value : $default;
        }

        return $defaults;
    }

    private function stringValue(mixed $value): string
    {
        return trim(is_scalar($value) ? (string) $value : '');
    }

    private function nullableStringValue(mixed $value): ?string
    {
        $normalized = $this->stringValue($value);

        return $normalized === '' ? null : $normalized;
    }
}
