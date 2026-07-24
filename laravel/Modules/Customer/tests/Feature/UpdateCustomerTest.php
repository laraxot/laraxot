<?php

declare(strict_types=1);

uses(\Modules\Customer\Tests\TestCase::class);

use Illuminate\Support\Facades\DB;
use Modules\Customer\Actions\UpdateCustomerAction;
use Modules\Customer\Models\Customer;

it('updates a customer', function (): void {
    // Arrange
    /** @var Customer $customer */
    $customer = Customer::query()->create([
        'legal_name' => 'Old name',
        'is_active' => 1,
    ]);

    // Act
    app(UpdateCustomerAction::class)->execute($customer, [
        'legal_name' => 'New name',
        'email' => 'new@acme.test',
    ]);

    // Assert
    $customer->refresh();
    expect($customer->legal_name)->toBe('New name');
    expect(DB::table('customers')->where('id', $customer->id)->value('email'))->toBe('new@acme.test');
});

