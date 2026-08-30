<?php

declare(strict_types=1);

namespace Modules\Customer\Tests\Feature;

use Illuminate\Support\Facades\DB;
use Modules\Customer\Actions\UpdateCustomerAction;
use Modules\Customer\Tests\TestCase;

uses(\Modules\Customer\Tests\TestCase::class);

it('updates a customer', function (): void {
    $customer = customerCreate([
        'legal_name' => 'Old name',
        'is_active' => 1,
    ]);

    app(UpdateCustomerAction::class)->execute($customer, [
        'legal_name' => 'New name',
        'email' => 'new@acme.test',
    ]);

    $customer->refresh();
    expect($customer->legal_name)->toBe('New name');
    expect(DB::table('customers')->where('id', $customer->id)->value('email'))->toBe('new@acme.test');
});
