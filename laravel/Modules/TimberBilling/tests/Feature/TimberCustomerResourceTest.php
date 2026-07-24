<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Tests\Feature;

use Modules\TimberBilling\Tests\TestCase;

uses(TestCase::class);

use Modules\TimberBilling\Filament\Resources\TimberCustomerResource\Pages\CreateTimberCustomer;
use Modules\TimberBilling\Filament\Resources\TimberCustomerResource\Pages\EditTimberCustomer;
use Modules\TimberBilling\Filament\Resources\TimberCustomerResource\Pages\ListTimberCustomers;
use Modules\User\Models\User;
use Modules\TimberBilling\Models\TimberCustomer;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    Role::findOrCreate('timber_admin');
    filament()->setCurrentPanel('timber');
});

it('lists and creates customers as a timber_admin', function (): void {
    $admin = User::factory()->create();
    $admin->syncRoles(['timber_admin']);

    TimberCustomer::factory()->create(['business_name' => 'Falegnameria Rossi Srl']);

    Livewire::actingAs($admin)
        ->test(ListTimberCustomers::class)
        ->assertOk()
        ->assertSee('Falegnameria Rossi Srl');

    Livewire::actingAs($admin)
        ->test(CreateTimberCustomer::class)
        ->fillForm([
            'business_name' => 'Legnami Bianchi Snc',
            'vat_number' => 'IT01234567890',
            'is_customer' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(TimberCustomer::query()->where('business_name', 'Legnami Bianchi Snc')->exists())->toBeTrue();
});

it('requires a business name', function (): void {
    $admin = User::factory()->create();
    $admin->syncRoles(['timber_admin']);

    Livewire::actingAs($admin)
        ->test(CreateTimberCustomer::class)
        ->fillForm(['business_name' => null])
        ->call('create')
        ->assertHasFormErrors(['business_name' => 'required']);
});

it('validates the email address on the commercial tab', function (): void {
    $admin = User::factory()->create();
    $admin->syncRoles(['timber_admin']);

    Livewire::actingAs($admin)
        ->test(CreateTimberCustomer::class)
        ->fillForm([
            'business_name' => 'Test Srl',
            'email' => 'not-an-email',
        ])
        ->call('create')
        ->assertHasFormErrors(['email' => 'email']);
});

it('stores the full commercial profile of a supplier customer', function (): void {
    $admin = User::factory()->create();
    $admin->syncRoles(['timber_admin']);

    Livewire::actingAs($admin)
        ->test(CreateTimberCustomer::class)
        ->fillForm([
            'business_name' => 'Iper Mobili Spa',
            'is_customer' => true,
            'is_supplier' => true,
            'sdi_recipient_code' => 'A4707H7',
            'payment_terms' => 'RIBA 90 gg D.F.F.M.',
            'iban' => 'IT60X0542811101000000123456',
            'default_discount_percent' => 5,
            'send_documents_by_email' => true,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $customer = TimberCustomer::query()->where('business_name', 'Iper Mobili Spa')->firstOrFail();

    expect($customer->is_supplier)->toBeTrue()
        ->and($customer->sdi_recipient_code)->toBe('A4707H7')
        ->and($customer->payment_terms)->toBe('RIBA 90 gg D.F.F.M.')
        ->and((float) $customer->default_discount_percent)->toBe(5.0)
        ->and($customer->send_documents_by_email)->toBeTrue();
});

it('edits an existing customer', function (): void {
    $admin = User::factory()->create();
    $admin->syncRoles(['timber_admin']);

    $customer = TimberCustomer::factory()->create(['business_name' => 'Vecchio Nome Srl']);

    Livewire::actingAs($admin)
        ->test(EditTimberCustomer::class, ['record' => $customer->getKey()])
        ->fillForm(['business_name' => 'Nuovo Nome Srl'])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($customer->refresh()->business_name)->toBe('Nuovo Nome Srl');
});
