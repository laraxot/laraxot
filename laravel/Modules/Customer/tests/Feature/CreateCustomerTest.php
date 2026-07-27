<?php

declare(strict_types=1);

namespace Modules\Customer\Tests\Feature;

use Illuminate\Support\Facades\DB;
use Modules\Customer\Actions\CreateCustomerAction;
use Modules\Customer\Tests\TestCase;

uses(TestCase::class);

it('creates a customer', function (): void {
    $customer = app(CreateCustomerAction::class)->execute([
        'legal_name' => 'Acme S.p.A.',
        'vat_number' => 'IT12345678901',
        'tax_code' => 'ABCDEF12G34H567I',
        'email' => 'info@acme.test',
        'phone' => '+39 010 000 0000',
        'address_line1' => 'Via Roma 1',
        'address_line2' => null,
        'city' => 'Genova',
        'postal_code' => '16100',
        'province' => 'GE',
        'country_code' => 'IT',
        'payment_method' => 'bank_transfer',
        'iban' => 'IT60X0542811101000000123456',
        'notes' => null,
        'is_active' => 1,
    ]);

    expect($customer->id)->not->toBeNull();
    expect(DB::table('customers')->where('id', $customer->id)->value('legal_name'))->toBe('Acme S.p.A.');
});
