<?php

declare(strict_types=1);

uses(\Modules\Customer\Tests\TestCase::class);

use Modules\Customer\Actions\RestoreCustomerCascadeAction;
use Modules\Customer\Models\Customer;

it('does nothing when restoring a non-trashed customer', function (): void {
    $customer = Customer::query()->create([
        'legal_name' => 'Noop Customer',
        'is_active' => true,
    ]);

    expect($customer->trashed())->toBeFalse();

    app(RestoreCustomerCascadeAction::class)->execute($customer);

    $customer->refresh();
    expect($customer->trashed())->toBeFalse();
});

