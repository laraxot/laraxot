<?php

declare(strict_types=1);

uses(\Modules\Customer\Tests\TestCase::class);

use Modules\Customer\Models\Customer;

it('lists customers in the database', function (): void {
    // Arrange
    Customer::query()->create(['legal_name' => 'Acme S.p.A.', 'is_active' => 1]);

    // Assert
    expect(Customer::query()->count())->toBeGreaterThan(0);
});

