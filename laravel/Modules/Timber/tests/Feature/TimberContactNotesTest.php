<?php

declare(strict_types=1);

use Modules\Timber\Filament\Resources\TimberCustomerResource\Pages\EditTimberCustomer;
use Modules\Timber\Models\TimberContactNote;
use Modules\Timber\Models\TimberCustomer;
use Modules\Timber\Models\TimberSupplier;
use Modules\Timber\Tests\TestCase;
use Spatie\Permission\Models\Role;

uses(TestCase::class);

it('relates notes polymorphically to customers and suppliers', function (): void {
    $customer = TimberCustomer::factory()->createOne();
    $supplier = TimberSupplier::factory()->createOne();

    $customer->contactNotes()->create(['body' => 'Cliente da richiamare']);
    $supplier->contactNotes()->create(['body' => 'Fornitore affidabile']);

    $customerNote = $customer->contactNotes()->first();
    $supplierNote = $supplier->contactNotes()->first();
    expect($customerNote)->not->toBeNull();
    expect($supplierNote)->not->toBeNull();
    assert($customerNote instanceof TimberContactNote);
    assert($supplierNote instanceof TimberContactNote);

    expect($customer->contactNotes()->count())->toBe(1)
        ->and($customerNote->body)->toBe('Cliente da richiamare')
        ->and($supplier->contactNotes()->count())->toBe(1)
        ->and($supplierNote->noteable)->toBeInstanceOf(TimberSupplier::class);
});

it('adds a note to a customer via the edit form', function (): void {
    Role::findOrCreate('timber_admin');
    filament()->setCurrentPanel('timber::admin');
    actingAsTimberAdmin();

    $customer = TimberCustomer::factory()->createOne();

    testTimberLivewire(EditTimberCustomer::class, ['record' => $customer->id])
        ->fillForm(['contactNotes' => [['body' => 'Preferisce consegna al mattino']]])
        ->call('save')
        ->assertHasNoFormErrors();

    expect($customer->contactNotes()->where('body', 'Preferisce consegna al mattino')->exists())->toBeTrue();
});
