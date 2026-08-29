<?php

declare(strict_types=1);

namespace Modules\Timber\Actions;

use Illuminate\Support\Facades\DB;
use Modules\Customer\Models\Customer;
use Modules\Timber\Legacy\EasyfattGateway;

final readonly class SyncEasyfattCustomersAction
{
    public function __construct(
        private EasyfattGateway $gateway,
    ) {}

    /**
     * @return array{created: int, updated: int}
     */
    public function execute(): array
    {
        $created = 0;
        $updated = 0;

        DB::transaction(function () use (&$created, &$updated): void {
            foreach ($this->gateway->fetchCustomers() as $customerData) {
                if ($customerData->customerName === '') {
                    continue;
                }

                $customer = Customer::query()->firstOrNew([
                    'vat_number' => $customerData->vatNumber ?? $customerData->customerCode,
                ]);

                $wasRecentlyCreated = ! $customer->exists;

                $customer->fill([
                    'legal_name' => $customerData->customerName,
                    'vat_number' => $customerData->vatNumber ?? $customerData->customerCode,
                    'is_active' => true,
                ])->save();

                $wasRecentlyCreated ? $created++ : $updated++;
            }
        });

        return ['created' => $created, 'updated' => $updated];
    }
}
