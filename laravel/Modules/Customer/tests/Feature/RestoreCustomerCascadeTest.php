<?php

declare(strict_types=1);

namespace Modules\Customer\Tests\Feature;

use Modules\Customer\Actions\RestoreCustomerCascadeAction;
use Modules\Customer\Models\Contact;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\Customer\Tests\TestCase;

uses(\Modules\Customer\Tests\TestCase::class);

it('restores a customer and restores related sites and contacts', function (): void {
    // Arrange
    $customer = Customer::query()->create([
        'legal_name' => 'Acme S.p.A.',
        'vat_number' => null,
        'tax_code' => null,
        'email' => null,
        'phone' => null,
        'address_line1' => null,
        'address_line2' => null,
        'city' => null,
        'postal_code' => null,
        'province' => null,
        'country_code' => 'IT',
        'payment_method' => null,
        'iban' => null,
        'notes' => null,
        'is_active' => true,
    ]);

    $site = CustomerSite::query()->create([
        'customer_id' => $customer->id,
        'name' => 'HQ',
        'address_line1' => null,
        'address_line2' => null,
        'city' => null,
        'postal_code' => null,
        'province' => null,
        'country_code' => 'IT',
        'email' => null,
        'phone' => null,
        'notes' => null,
    ]);

    $contact = Contact::query()->create([
        'customer_id' => $customer->id,
        'customer_site_id' => $site->id,
        'contact_role_id' => null,
        'full_name' => 'Mario Rossi',
        'email' => null,
        'phone' => null,
        'notes' => null,
    ]);

    $customer->delete();
    $site->delete();
    $contact->delete();

    expect($customer->refresh()->trashed())->toBeTrue();
    expect(CustomerSite::query()->onlyTrashed()->whereKey($site->id)->exists())->toBeTrue();
    expect(Contact::query()->onlyTrashed()->whereKey($contact->id)->exists())->toBeTrue();

    // Act
    app(RestoreCustomerCascadeAction::class)->execute($customer->refresh());

    // Assert
    expect($customer->refresh()->trashed())->toBeFalse();
    expect(CustomerSite::query()->whereKey($site->id)->exists())->toBeTrue();
    expect(Contact::query()->whereKey($contact->id)->exists())->toBeTrue();
});
