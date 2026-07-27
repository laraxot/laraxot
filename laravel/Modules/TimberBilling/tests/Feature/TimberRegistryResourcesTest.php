<?php

declare(strict_types=1);
use Modules\TimberBilling\Filament\Resources\TimberAgentResource\Pages\CreateTimberAgent;
use Modules\TimberBilling\Filament\Resources\TimberAgentResource\Pages\ListTimberAgents;
use Modules\TimberBilling\Filament\Resources\TimberSupplierResource\Pages\CreateTimberSupplier;
use Modules\TimberBilling\Filament\Resources\TimberSupplierResource\Pages\ListTimberSuppliers;
use Modules\TimberBilling\Filament\Resources\TimberTransporterResource\Pages\CreateTimberTransporter;
use Modules\TimberBilling\Filament\Resources\TimberTransporterResource\Pages\ListTimberTransporters;
use Modules\TimberBilling\Models\TimberAgent;
use Modules\TimberBilling\Models\TimberSupplier;
use Modules\TimberBilling\Models\TimberTransporter;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    Role::findOrCreate('timber_admin');
    filament()->setCurrentPanel('timber');
});

it('lists and creates suppliers as a timber_admin', function (): void {
    actingAsTimberAdmin();

    TimberSupplier::factory()->createOne(['legal_name' => 'Fornitore Visibile Srl']);

    testTimberLivewire(ListTimberSuppliers::class)
        ->assertOk()
        ->assertSee('Fornitore Visibile Srl');

    testTimberLivewire(CreateTimberSupplier::class)
        ->fillForm(['legal_name' => 'Nuovo Fornitore Srl'])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(TimberSupplier::query()->where('legal_name', 'Nuovo Fornitore Srl')->exists())->toBeTrue();
});

it('lists and creates transporters as a timber_admin', function (): void {
    actingAsTimberAdmin();

    testTimberLivewire(ListTimberTransporters::class)
        ->assertOk();

    testTimberLivewire(CreateTimberTransporter::class)
        ->fillForm(['legal_name' => 'Nuovo Trasportatore Srl'])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(TimberTransporter::query()->where('legal_name', 'Nuovo Trasportatore Srl')->exists())->toBeTrue();
});

it('lists and creates agents as a timber_admin', function (): void {
    actingAsTimberAdmin();

    testTimberLivewire(ListTimberAgents::class)
        ->assertOk();

    testTimberLivewire(CreateTimberAgent::class)
        ->fillForm(['full_name' => 'Nuovo Agente'])
        ->call('create')
        ->assertHasNoFormErrors();

    expect(TimberAgent::query()->where('full_name', 'Nuovo Agente')->exists())->toBeTrue();
});
