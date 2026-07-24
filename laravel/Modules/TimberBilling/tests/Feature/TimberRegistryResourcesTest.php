<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Tests\Feature;

use Modules\TimberBilling\Tests\TestCase;

uses(TestCase::class);

use Modules\TimberBilling\Filament\Resources\TimberAgentResource\Pages\CreateTimberAgent;
use Modules\TimberBilling\Filament\Resources\TimberAgentResource\Pages\ListTimberAgents;
use Modules\TimberBilling\Filament\Resources\TimberSupplierResource\Pages\CreateTimberSupplier;
use Modules\TimberBilling\Filament\Resources\TimberSupplierResource\Pages\ListTimberSuppliers;
use Modules\TimberBilling\Filament\Resources\TimberTransporterResource\Pages\CreateTimberTransporter;
use Modules\TimberBilling\Filament\Resources\TimberTransporterResource\Pages\ListTimberTransporters;
use Modules\User\Models\User;
use Modules\TimberBilling\Models\TimberAgent;
use Modules\TimberBilling\Models\TimberSupplier;
use Modules\TimberBilling\Models\TimberTransporter;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    Role::findOrCreate('timber_admin');
    filament()->setCurrentPanel('timber');
});

it('lists and creates suppliers as a timber_admin', function (): void {
    $admin = User::factory()->create();
    $admin->syncRoles(['timber_admin']);

    TimberSupplier::factory()->create(['legal_name' => 'Fornitore Visibile Srl']);

    Livewire::actingAs($admin)
        ->test(ListTimberSuppliers::class)
        ->assertOk()
        ->assertSee('Fornitore Visibile Srl');

    Livewire::actingAs($admin)
        ->test(CreateTimberSupplier::class)
        ->fillForm(['legal_name' => 'Nuovo Fornitore Srl'])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(TimberSupplier::query()->where('legal_name', 'Nuovo Fornitore Srl')->exists())->toBeTrue();
});

it('lists and creates transporters as a timber_admin', function (): void {
    $admin = User::factory()->create();
    $admin->syncRoles(['timber_admin']);

    Livewire::actingAs($admin)
        ->test(ListTimberTransporters::class)
        ->assertOk();

    Livewire::actingAs($admin)
        ->test(CreateTimberTransporter::class)
        ->fillForm(['legal_name' => 'Nuovo Trasportatore Srl'])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(TimberTransporter::query()->where('legal_name', 'Nuovo Trasportatore Srl')->exists())->toBeTrue();
});

it('lists and creates agents as a timber_admin', function (): void {
    $admin = User::factory()->create();
    $admin->syncRoles(['timber_admin']);

    Livewire::actingAs($admin)
        ->test(ListTimberAgents::class)
        ->assertOk();

    Livewire::actingAs($admin)
        ->test(CreateTimberAgent::class)
        ->fillForm(['full_name' => 'Nuovo Agente'])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(TimberAgent::query()->where('full_name', 'Nuovo Agente')->exists())->toBeTrue();
});
