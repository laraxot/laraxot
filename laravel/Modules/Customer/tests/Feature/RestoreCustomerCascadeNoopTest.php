<?php

declare(strict_types=1);

namespace Modules\Customer\Tests\Feature;

use Modules\Customer\Actions\RestoreCustomerCascadeAction;
use Modules\Customer\Models\Customer;
use Modules\Customer\Tests\TestCase;

uses(TestCase::class);

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
