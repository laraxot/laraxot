<?php

declare(strict_types=1);

namespace Modules\Customer\Tests\Feature;

use Modules\Customer\Actions\CreateCustomerAction;
use Modules\Customer\Actions\CreateCustomerSiteAction;
use Modules\Customer\Actions\UpsertCustomerContactByEmailAction;
use Modules\Customer\Models\Contact;
use Modules\Customer\Models\ContactRole;
use Modules\Customer\Tests\TestCase;

uses(\Modules\Customer\Tests\TestCase::class);

it('does not create a contact role when position is empty or whitespace', function (): void {
    $customer = app(CreateCustomerAction::class)->execute([
        'legal_name' => 'Acme S.p.A.',
        'vat_number' => null,
        'tax_code' => null,
        'email' => 'info@acme.test',
        'phone' => null,
        'address_line1' => 'Via Roma 1',
        'address_line2' => null,
        'city' => 'Genova',
        'postal_code' => '16100',
        'province' => 'GE',
        'country_code' => 'IT',
        'payment_method' => 'bank_transfer',
        'iban' => null,
        'notes' => null,
        'is_active' => 1,
    ]);

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
    $customer = app(CreateCustomerAction::class)->execute([
        'legal_name' => 'Acme S.p.A.',
        'vat_number' => null,
        'tax_code' => null,
        'email' => 'info@acme.test',
        'phone' => null,
        'address_line1' => 'Via Roma 1',
        'address_line2' => null,
        'city' => 'Genova',
        'postal_code' => '16100',
        'province' => 'GE',
        'country_code' => 'IT',
        'payment_method' => 'bank_transfer',
        'iban' => null,
        'notes' => null,
        'is_active' => 1,
    ]);

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
    expect(Contact::query()->where('customer_id', $customer->id)->pluck('email')->all())
        ->toContain('mario.rossi@acme.test', 'luigi.bianchi@acme.test');

    expect(Contact::query()->where('id', $first->id)->value('full_name'))->toBe('Mario Rossi');
});

it('persists customer site id when provided', function (): void {
    $customer = app(CreateCustomerAction::class)->execute([
        'legal_name' => 'Acme S.p.A.',
        'vat_number' => null,
        'tax_code' => null,
        'email' => 'info@acme.test',
        'phone' => null,
        'address_line1' => 'Via Roma 1',
        'address_line2' => null,
        'city' => 'Genova',
        'postal_code' => '16100',
        'province' => 'GE',
        'country_code' => 'IT',
        'payment_method' => 'bank_transfer',
        'iban' => null,
        'notes' => null,
        'is_active' => 1,
    ]);

    $site = app(CreateCustomerSiteAction::class)->execute($customer, [
        'name' => 'HQ',
        'address_line1' => 'Via Roma 2',
        'city' => 'Genova',
        'postal_code' => '16100',
        'province' => 'GE',
        'email' => 'hq@acme.test',
        'phone' => null,
        'notes' => null,
    ]);

    $contact = app(UpsertCustomerContactByEmailAction::class)->execute(
        customerId: $customer->id,
        fullName: 'Mario Rossi',
        email: 'mario.rossi@acme.test',
        customerSiteId: $site->id,
    );

    expect($contact->customer_site_id)->toBe($site->id);
    $dbValue = Contact::query()->where('id', $contact->id)->value('customer_site_id');
    expect(is_int($dbValue) ? $dbValue : 0)->toBe((int) $site->id);
});

it('trims position before creating contact roles', function (): void {
    $customer = app(CreateCustomerAction::class)->execute([
        'legal_name' => 'Acme S.p.A.',
        'vat_number' => null,
        'tax_code' => null,
        'email' => 'info@acme.test',
        'phone' => null,
        'address_line1' => 'Via Roma 1',
        'address_line2' => null,
        'city' => 'Genova',
        'postal_code' => '16100',
        'province' => 'GE',
        'country_code' => 'IT',
        'payment_method' => 'bank_transfer',
        'iban' => null,
        'notes' => null,
        'is_active' => 1,
    ]);

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

it('persists and updates phone when upserting by email', function (): void {
    $customer = app(CreateCustomerAction::class)->execute([
        'legal_name' => 'Acme S.p.A.',
        'vat_number' => null,
        'tax_code' => null,
        'email' => 'info@acme.test',
        'phone' => null,
        'address_line1' => 'Via Roma 1',
        'address_line2' => null,
        'city' => 'Genova',
        'postal_code' => '16100',
        'province' => 'GE',
        'country_code' => 'IT',
        'payment_method' => 'bank_transfer',
        'iban' => null,
        'notes' => null,
        'is_active' => 1,
    ]);

    $contact = app(UpsertCustomerContactByEmailAction::class)->execute(
        customerId: $customer->id,
        fullName: 'Mario Rossi',
        email: 'mario.rossi@acme.test',
        phone: '+39 010 000 0000',
    );

    expect(Contact::query()->where('id', $contact->id)->value('phone'))->toBe('+39 010 000 0000');

    $updated = app(UpsertCustomerContactByEmailAction::class)->execute(
        customerId: $customer->id,
        fullName: 'Mario Rossi',
        email: 'mario.rossi@acme.test',
        phone: '+39 010 111 1111',
    );

    expect($updated->id)->toBe($contact->id);
    expect(Contact::query()->where('id', $contact->id)->value('phone'))->toBe('+39 010 111 1111');
});

it('persists and updates notes when upserting by email', function (): void {
    $customer = app(CreateCustomerAction::class)->execute([
        'legal_name' => 'Acme S.p.A.',
        'vat_number' => null,
        'tax_code' => null,
        'email' => 'info@acme.test',
        'phone' => null,
        'address_line1' => 'Via Roma 1',
        'address_line2' => null,
        'city' => 'Genova',
        'postal_code' => '16100',
        'province' => 'GE',
        'country_code' => 'IT',
        'payment_method' => 'bank_transfer',
        'iban' => null,
        'notes' => null,
        'is_active' => 1,
    ]);

    $contact = app(UpsertCustomerContactByEmailAction::class)->execute(
        customerId: $customer->id,
        fullName: 'Mario Rossi',
        email: 'mario.rossi@acme.test',
        notes: 'Initial notes',
    );

    expect(Contact::query()->where('id', $contact->id)->value('notes'))->toBe('Initial notes');

    $updated = app(UpsertCustomerContactByEmailAction::class)->execute(
        customerId: $customer->id,
        fullName: 'Mario Rossi',
        email: 'mario.rossi@acme.test',
        notes: 'Updated notes',
    );

    expect($updated->id)->toBe($contact->id);
    expect(Contact::query()->where('id', $contact->id)->value('notes'))->toBe('Updated notes');
});
