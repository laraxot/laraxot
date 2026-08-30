<?php

declare(strict_types=1);

namespace Modules\Customer\Tests\Feature;

use Illuminate\Support\Facades\DB;
use Modules\Customer\Actions\CreateCustomerSiteAction;
use Modules\Customer\Tests\TestCase;

uses(\Modules\Customer\Tests\TestCase::class);

it('creates a customer site', function (): void {
    $customer = customerCreate([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
    ]);

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

    expect($site->id)->not->toBeNull();
    expect((int) $site->customer_id)->toBe((int) $customer->id);
    expect(DB::table('customer_sites')->where('id', $site->id)->value('name'))->toBe('HQ');
});
