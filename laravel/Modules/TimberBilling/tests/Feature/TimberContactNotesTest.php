<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Tests\Feature;

use Modules\TimberBilling\Tests\TestCase;

uses(TestCase::class);

use Modules\TimberBilling\Filament\Resources\TimberCustomerResource\Pages\EditTimberCustomer;
use Modules\User\Models\User;
use Modules\TimberBilling\Models\TimberCustomer;
use Modules\TimberBilling\Models\TimberSupplier;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

it('relates notes polymorphically to customers and suppliers', function (): void {
    $customer = TimberCustomer::factory()->create();
    $supplier = TimberSupplier::factory()->create();

    $customer->contactNotes()->create(['body' => 'Cliente da richiamare']);
    $supplier->contactNotes()->create(['body' => 'Fornitore affidabile']);

    expect($customer->contactNotes()->count())->toBe(1)
        ->and($customer->contactNotes()->first()->body)->toBe('Cliente da richiamare')
        ->and($supplier->contactNotes()->count())->toBe(1)
        ->and($supplier->contactNotes()->first()->noteable)->toBeInstanceOf(TimberSupplier::class);
});

it('adds a note to a customer via the edit form', function (): void {
    Role::findOrCreate('timber_admin');
    filament()->setCurrentPanel('timber');
    $admin = User::factory()->create();
    $admin->syncRoles(['timber_admin']);

    $customer = TimberCustomer::factory()->create();

    Livewire::actingAs($admin)
        ->test(EditTimberCustomer::class, ['record' => $customer->getKey()])
        ->fillForm(['contactNotes' => [['body' => 'Preferisce consegna al mattino']]])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($customer->contactNotes()->where('body', 'Preferisce consegna al mattino')->exists())->toBeTrue();
});
