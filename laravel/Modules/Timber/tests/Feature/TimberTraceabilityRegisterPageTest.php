<?php

declare(strict_types=1);

uses(\Modules\Timber\Tests\TestCase::class);
use Modules\Timber\Filament\Pages\TimberTraceabilityRegisterPage;
use Modules\Timber\Models\TimberLot;
use Modules\Timber\Models\TimberPurchaseRecord;
use Modules\Timber\Models\TimberSupplier;
use Modules\Timber\Tests\TestCase;

beforeEach(function (): void {
    filament()->setCurrentPanel('timber::admin');
    actingAsTimberOperator();
});

it('renders the traceability register with the ingress data', function (): void {
    $supplier = TimberSupplier::factory()->createOne(['legal_name' => 'Boschi Veneti Srl']);
    $record = TimberPurchaseRecord::factory()->createOne([
        'supplier_id' => $supplier->getKey(),
        'ddt_number' => 'DDT-2026-00777',
        'ddt_date' => '2026-07-02',
    ]);
    TimberLot::factory()->createOne(['purchase_record_id' => $record->getKey()]);

    testTimberLivewire(TimberTraceabilityRegisterPage::class)
        ->assertOk()
        ->assertSee('Boschi Veneti Srl')
        ->assertSee('DDT-2026-00777')
        ->assertSee('Ingresso materia prima')
        ->assertSee('Confezionamento');
});

it('exports the register as a CSV download', function (): void {
    $supplier = TimberSupplier::factory()->createOne(['legal_name' => 'Legnami Alpini']);
    $record = TimberPurchaseRecord::factory()->createOne(['supplier_id' => $supplier->getKey(), 'ddt_number' => 'DDT-2026-00888']);
    TimberLot::factory()->createOne(['purchase_record_id' => $record->getKey()]);

    $response = testTimberLivewire(TimberTraceabilityRegisterPage::class)
        ->call('exportCsv');

    $response->assertOk();
});
