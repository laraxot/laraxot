<?php

declare(strict_types=1);

uses(\Modules\Customer\Tests\TestCase::class);

use Illuminate\Support\Facades\DB;
use Modules\Customer\Actions\CreateCustomerSiteAction;
use Modules\Customer\Models\Customer;

it('creates a customer site', function (): void {
    // Arrange
    /** @var Customer $customer */
    $customer = Customer::query()->create([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
    ]);

    // Act
    $site = app(CreateCustomerSiteAction::class)->execute($customer, [
        'name' => 'HQ',
        'address_line1' => 'Via Roma 1',
        'city' => 'Genova',
        'postal_code' => '16100',
        'province' => 'GE',
        'email' => 'hq@acme.test',
        'phone' => '+39 010 000 0000',
        'notes' => null,
    ]);

    // Assert
    expect($site->id)->not->toBeNull();
    expect((int) $site->customer_id)->toBe((int) $customer->id);
    expect(DB::table('customer_sites')->where('id', $site->id)->value('name'))->toBe('HQ');
});

