<?php

declare(strict_types=1);

uses(\Modules\Customer\Tests\TestCase::class);

use Modules\Customer\Actions\SoftDeleteCustomerAction;
use Modules\Customer\Models\Customer;

it('soft-deletes a customer', function (): void {
    // Arrange
    /** @var Customer $customer */
    $customer = Customer::query()->create([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
    ]);

    // Act
    app(SoftDeleteCustomerAction::class)->execute($customer);

    // Assert
    expect($customer->refresh()->trashed())->toBeTrue();
});

