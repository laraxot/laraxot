<?php

declare(strict_types=1);

namespace Modules\TimberBilling\Tests\Feature;

use Modules\TimberBilling\Tests\TestCase;

uses(TestCase::class);

use Modules\TimberBilling\Filament\Pages\TimberTraceabilityRegisterPage;
use Modules\User\Models\User;
use Modules\TimberBilling\Models\TimberLot;
use Modules\TimberBilling\Models\TimberPurchaseRecord;
use Modules\TimberBilling\Models\TimberSupplier;
use Livewire\Livewire;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    Role::findOrCreate('timber_operator');
    $this->user = User::factory()->create();
    $this->user->syncRoles(['timber_operator']);
    filament()->setCurrentPanel('timber');
});

it('renders the traceability register with the ingress data', function (): void {
    $supplier = TimberSupplier::factory()->create(['legal_name' => 'Boschi Veneti Srl']);
    $record = TimberPurchaseRecord::factory()->create([
        'supplier_id' => $supplier->getKey(),
        'ddt_number' => 'DDT-2026-00777',
        'ddt_date' => '2026-07-02',
    ]);
    TimberLot::factory()->create(['purchase_record_id' => $record->getKey()]);

    Livewire::actingAs($this->user)
        ->test(TimberTraceabilityRegisterPage::class)
        ->assertOk()
        ->assertSee('Boschi Veneti Srl')
        ->assertSee('DDT-2026-00777')
        ->assertSee('Ingresso materia prima')
        ->assertSee('Confezionamento');
});

it('exports the register as a CSV download', function (): void {
    $supplier = TimberSupplier::factory()->create(['legal_name' => 'Legnami Alpini']);
    $record = TimberPurchaseRecord::factory()->create(['supplier_id' => $supplier->getKey(), 'ddt_number' => 'DDT-2026-00888']);
    TimberLot::factory()->create(['purchase_record_id' => $record->getKey()]);

    $response = Livewire::actingAs($this->user)
        ->test(TimberTraceabilityRegisterPage::class)
        ->call('exportCsv');

    $response->assertOk();
});
