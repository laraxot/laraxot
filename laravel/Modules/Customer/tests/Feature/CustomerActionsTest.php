<?php

declare(strict_types=1);

namespace Modules\Customer\Tests\Feature;

use Illuminate\Support\Facades\DB;
use Modules\Customer\Actions\CreateCustomerAction;
use Modules\Customer\Actions\CreateCustomerSiteAction;
use Modules\Customer\Actions\RestoreCustomerCascadeAction;
use Modules\Customer\Actions\SoftDeleteCustomerAction;
use Modules\Customer\Actions\SoftDeleteCustomerSiteAction;
use Modules\Customer\Actions\UpdateCustomerAction;
use Modules\Customer\Actions\UpsertCustomerContactByEmailAction;
use Modules\Customer\Models\Contact;
use Modules\Customer\Models\ContactRole;
use Modules\Customer\Models\Customer;
use Modules\Customer\Models\CustomerSite;
use Modules\Customer\Tests\TestCase;

/*
 * Migrato da gestionale_commesse/tests/Feature/Customers/{CreateCustomerTest,UpdateCustomerTest,
 * DeleteCustomerTest,CreateCustomerSiteTest,DeleteCustomerSiteTest,RestoreCustomerCascadeTest,
 * RestoreCustomerCascadeNoopTest,UpsertCustomerContactByEmailActionMutationTest}.php.
 * Namespace adattato da Modules\Customers\Infrastructure\Models a Modules\Customer\Models,
 * Actions da Modules\Customers\Application\Actions a Modules\Customer\Actions (stessa firma).
 */
uses(\Modules\Customer\Tests\TestCase::class);

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

it('updates a customer', function (): void {
    $customer = Customer::query()->create(['legal_name' => 'Old name', 'is_active' => 1]);

    app(UpdateCustomerAction::class)->execute($customer, [
        'legal_name' => 'New name',
        'email' => 'new@acme.test',
    ]);

    $customer->refresh();
    expect($customer->legal_name)->toBe('New name');
    expect(DB::table('customers')->where('id', $customer->id)->value('email'))->toBe('new@acme.test');
});

it('soft-deletes a customer', function (): void {
    $customer = Customer::query()->create(['legal_name' => 'Acme S.p.A.', 'is_active' => 1]);

    app(SoftDeleteCustomerAction::class)->execute($customer);

    expect($customer->refresh()->trashed())->toBeTrue();
});

it('creates a customer site', function (): void {
    $customer = Customer::query()->create(['legal_name' => 'Acme S.p.A.', 'is_active' => 1]);

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

it('soft-deletes a customer site', function (): void {
    $customer = Customer::query()->create(['legal_name' => 'Acme S.p.A.', 'is_active' => 1]);
    $site = $customer->sites()->create(['name' => 'HQ']);

    app(SoftDeleteCustomerSiteAction::class)->execute($site);

    expect($site->refresh()->trashed())->toBeTrue();
});

it('does nothing when restoring a non-trashed customer', function (): void {
    $customer = Customer::query()->create(['legal_name' => 'Noop Customer', 'is_active' => true]);

    expect($customer->trashed())->toBeFalse();

    app(RestoreCustomerCascadeAction::class)->execute($customer);

    expect($customer->refresh()->trashed())->toBeFalse();
});

it('restores a customer and restores related sites and contacts', function (): void {
    $customer = Customer::query()->create(['legal_name' => 'Acme S.p.A.', 'country_code' => 'IT', 'is_active' => true]);

    $site = CustomerSite::query()->create([
        'customer_id' => $customer->id,
        'name' => 'HQ',
        'country_code' => 'IT',
    ]);

    $contact = Contact::query()->create([
        'customer_id' => $customer->id,
        'customer_site_id' => $site->id,
        'full_name' => 'Mario Rossi',
    ]);

    $customer->delete();
    $site->delete();
    $contact->delete();

    expect($customer->refresh()->trashed())->toBeTrue();
    expect(CustomerSite::query()->onlyTrashed()->whereKey($site->id)->exists())->toBeTrue();
    expect(Contact::query()->onlyTrashed()->whereKey($contact->id)->exists())->toBeTrue();

    app(RestoreCustomerCascadeAction::class)->execute($customer->refresh());

    expect($customer->refresh()->trashed())->toBeFalse();
    expect(CustomerSite::query()->whereKey($site->id)->exists())->toBeTrue();
    expect(Contact::query()->whereKey($contact->id)->exists())->toBeTrue();
});

it('does not create a contact role when position is empty or whitespace', function (): void {
    $customer = Customer::query()->create(['legal_name' => 'Acme S.p.A.', 'is_active' => 1]);

    $contact1 = app(UpsertCustomerContactByEmailAction::class)->execute(
        customerId: $customer->id,
        fullName: 'Mario Rossi',
        email: 'mario.rossi@acme.test',
        position: '',
    );

    expect($contact1->contact_role_id)->toBeNull();
    expect(ContactRole::query()->count())->toBe(0);

    $contact2 = app(UpsertCustomerContactByEmailAction::class)->execute(
        customerId: $customer->id,
        fullName: 'Luigi Bianchi',
        email: 'luigi.bianchi@acme.test',
        position: '   ',
    );

    expect($contact2->contact_role_id)->toBeNull();
    expect(ContactRole::query()->count())->toBe(0);
});

it('upserts contacts by email per customer (does not overwrite different email)', function (): void {
    $customer = Customer::query()->create(['legal_name' => 'Acme S.p.A.', 'is_active' => 1]);

    $first = app(UpsertCustomerContactByEmailAction::class)->execute(
        customerId: $customer->id,
        fullName: 'Mario Rossi',
        email: 'mario.rossi@acme.test',
    );

    $second = app(UpsertCustomerContactByEmailAction::class)->execute(
        customerId: $customer->id,
        fullName: 'Luigi Bianchi',
        email: 'luigi.bianchi@acme.test',
    );

    expect($first->id)->not->toBeNull();
    expect($second->id)->not->toBeNull();
    expect($first->id)->not->toBe($second->id);
    expect(Contact::query()->where('customer_id', $customer->id)->count())->toBe(2);
});

it('trims position before creating contact roles', function (): void {
    $customer = Customer::query()->create(['legal_name' => 'Acme S.p.A.', 'is_active' => 1]);

    $contact = app(UpsertCustomerContactByEmailAction::class)->execute(
        customerId: $customer->id,
        fullName: 'Mario Rossi',
        email: 'mario.rossi@acme.test',
        position: ' Manager ',
    );

    expect($contact->contact_role_id)->not->toBeNull();
    expect(ContactRole::query()->count())->toBe(1);
    expect(ContactRole::query()->value('name'))->toBe('Manager');
});

it('persists and updates phone and notes when upserting by email', function (): void {
    $customer = Customer::query()->create(['legal_name' => 'Acme S.p.A.', 'is_active' => 1]);

    $contact = app(UpsertCustomerContactByEmailAction::class)->execute(
        customerId: $customer->id,
        fullName: 'Mario Rossi',
        email: 'mario.rossi@acme.test',
        phone: '+39 010 000 0000',
        notes: 'Initial notes',
    );

    expect(Contact::query()->where('id', $contact->id)->value('phone'))->toBe('+39 010 000 0000');
    expect(Contact::query()->where('id', $contact->id)->value('notes'))->toBe('Initial notes');

    $updated = app(UpsertCustomerContactByEmailAction::class)->execute(
        customerId: $customer->id,
        fullName: 'Mario Rossi',
        email: 'mario.rossi@acme.test',
        phone: '+39 010 111 1111',
        notes: 'Updated notes',
    );

    expect($updated->id)->toBe($contact->id);
    expect(Contact::query()->where('id', $contact->id)->value('phone'))->toBe('+39 010 111 1111');
    expect(Contact::query()->where('id', $contact->id)->value('notes'))->toBe('Updated notes');
});
