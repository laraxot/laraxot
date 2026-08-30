<?php

declare(strict_types=1);

namespace Modules\Customer\Tests\Feature;

use Modules\Customer\Models\Customer;
use Modules\Customer\Tests\TestCase;

/*
 * Migrato da gestionale_commesse/tests/Feature/Customers/CustomerResourceTest.php.
 */
uses(\Modules\Customer\Tests\TestCase::class);

it('lists customers in the database', function (): void {
    Customer::query()->create(['legal_name' => 'Acme S.p.A.', 'is_active' => 1]);

    expect(Customer::query()->count())->toBeGreaterThan(0);
});
