<?php

declare(strict_types=1);

uses(\Modules\Wts\Tests\TestCase::class);

use Modules\Wts\Actions\EnqueueWtsLabelPrintAction;
use Modules\Wts\Models\WtsInboundLot;
use Modules\Wts\Models\WtsInboundRecord;
use Modules\Wts\Models\WtsPrintJob;
use Modules\Wts\Models\WtsTechnicalSheetSnapshot;
use Modules\Wts\Models\WtsUnit;

function makeLabelUnit(?string $unitCode = null): WtsUnit
{
    $snapshot = WtsTechnicalSheetSnapshot::factory()->create([
        'producer_name' => 'GALVALUX SRL',
        'waste_code' => '110107',
    ]);
    $record = WtsInboundRecord::factory()->create([
        'technical_sheet_snapshot_id' => $snapshot->id,
        'form_number' => 'XCJQH 000141 BV',
        'entry_weight' => 28840,
    ]);
    $lot = WtsInboundLot::factory()->create(['inbound_record_id' => $record->id]);

    return WtsUnit::factory()->create([
        'inbound_lot_id' => $lot->id,
        'technical_sheet_snapshot_id' => $snapshot->id,
        'unit_code' => $unitCode ?? 'LOT-2026-'.fake()->unique()->numerify('####').'-BIG-BAG-01',
    ]);
}

it('enqueues a label print job with the generated ZPL', function (): void {
    $unit = makeLabelUnit('LOT-2026-0012-BIG-BAG-01');

    $job = app(EnqueueWtsLabelPrintAction::class)->execute($unit, 3);

    expect($job->status_code)->toBe('pending')
        ->and($job->copies)->toBe(3)
        ->and($job->label_code)->toBe('LOT-2026-0012-BIG-BAG-01')
        ->and($job->zpl)->toContain('^XA')->toContain('CER 110107')->toContain('Peso: 28.840 kg');
});

it('enqueues many labels at once', function (): void {
    $unitA = makeLabelUnit();
    $unitB = makeLabelUnit();

    $jobs = app(EnqueueWtsLabelPrintAction::class)->executeMany(collect([$unitA, $unitB]));

    expect($jobs)->toHaveCount(2)
        ->and(WtsPrintJob::query()->where('status_code', 'pending')->count())->toBe(2);
});

it('serves pending print jobs to an authorized agent', function (): void {
    config()->set('wts.print_agent_token', 'secret-token');

    WtsPrintJob::factory()->count(2)->create(['status_code' => 'pending']);
    WtsPrintJob::factory()->create(['status_code' => 'printed']);

    $this->withHeaders(['Authorization' => 'Bearer secret-token'])
        ->getJson('/api/wts/print-jobs')
        ->assertOk()
        ->assertJsonCount(2, 'jobs')
        ->assertJsonStructure(['jobs' => [['id', 'label_code', 'copies', 'zpl']]]);
});

it('rejects unauthorized print agents', function (): void {
    config()->set('wts.print_agent_token', 'secret-token');
    WtsPrintJob::factory()->create();

    $this->getJson('/api/wts/print-jobs')->assertStatus(401);
    $this->withHeaders(['Authorization' => 'Bearer wrong'])->getJson('/api/wts/print-jobs')->assertStatus(401);
});

it('rejects the agent when no token is configured', function (): void {
    config()->set('wts.print_agent_token', '');

    $this->withHeaders(['Authorization' => 'Bearer anything'])->getJson('/api/wts/print-jobs')->assertStatus(401);
});

it('lets the agent mark a job printed', function (): void {
    config()->set('wts.print_agent_token', 'secret-token');
    $job = WtsPrintJob::factory()->create(['status_code' => 'pending']);

    $this->withHeaders(['Authorization' => 'Bearer secret-token'])
        ->postJson("/api/wts/print-jobs/{$job->id}/done")
        ->assertOk()
        ->assertJson(['ok' => true]);

    expect($job->fresh()?->status_code)->toBe('printed')
        ->and($job->fresh()?->printed_at)->not->toBeNull()
        ->and($job->fresh()?->attempts)->toBe(1);
});

it('lets the agent mark a job failed with an error', function (): void {
    config()->set('wts.print_agent_token', 'secret-token');
    $job = WtsPrintJob::factory()->create(['status_code' => 'pending']);

    $this->withHeaders(['Authorization' => 'Bearer secret-token'])
        ->postJson("/api/wts/print-jobs/{$job->id}/failed", ['error' => 'stampante offline'])
        ->assertOk();

    expect($job->fresh()?->status_code)->toBe('failed')
        ->and($job->fresh()?->error)->toBe('stampante offline');
});

it('queues a single label to the printer from the lot detail page', function (): void {
    Spatie\Permission\Models\Role::findOrCreate('wts_admin');
    $user = Modules\User\Models\User::factory()->create();
    $user->syncRoles(['wts_admin']);

    $unit = makeLabelUnit('LOT-2026-9001-BIG-BAG-01');
    $lot = $unit->inboundLot;
    assert($lot instanceof \Modules\Wts\Models\WtsInboundLot);
    Livewire\Livewire::actingAs($user);
        /** @var \Livewire\Features\SupportTesting\Testable<\Livewire\Component> $livewire */
        $livewire = Livewire\Livewire::withQueryParams(['lot' => $lot->id])
        ->test(Modules\Wts\Filament\Pages\WtsLotDetailPage::class);
    $livewire->call('queueUnitLabelPrint', $unit->id)
        ->assertHasNoErrors();

    expect(WtsPrintJob::query()->where('wts_unit_id', $unit->id)->where('status_code', 'pending')->count())->toBe(1);

    // collo inesistente: nessun job
    Livewire\Livewire::actingAs($user);
        /** @var \Livewire\Features\SupportTesting\Testable<\Livewire\Component> $livewire */
        $livewire = Livewire\Livewire::withQueryParams(['lot' => $lot->id])
        ->test(Modules\Wts\Filament\Pages\WtsLotDetailPage::class);
    $livewire->call('queueUnitLabelPrint', 999999)
        ->assertHasNoErrors();

    expect(WtsPrintJob::query()->count())->toBe(1);
});

it('queues all lot labels to the printer from the lot detail page', function (): void {
    Spatie\Permission\Models\Role::findOrCreate('wts_admin');
    $user = Modules\User\Models\User::factory()->create();
    $user->syncRoles(['wts_admin']);

    $unit = makeLabelUnit('LOT-2026-9002-BIG-BAG-01');
    $lot = $unit->inboundLot;
    assert($lot instanceof \Modules\Wts\Models\WtsInboundLot);
    WtsUnit::factory()->create([
        'inbound_lot_id' => $lot->id,
        'technical_sheet_snapshot_id' => $unit->technical_sheet_snapshot_id,
        'unit_code' => 'LOT-2026-9002-BIG-BAG-02',
    ]);

    Livewire\Livewire::actingAs($user);
        /** @var \Livewire\Features\SupportTesting\Testable<\Livewire\Component> $livewire */
        $livewire = Livewire\Livewire::withQueryParams(['lot' => $lot->id])
        ->test(Modules\Wts\Filament\Pages\WtsLotDetailPage::class);
    $livewire->call('queueAllLabelsPrint')
        ->assertHasNoErrors();

    expect(WtsPrintJob::query()->where('status_code', 'pending')->count())->toBe(2);
});

it('sizes the ZPL label from the configured format and dpi', function (): void {
    // 100 x 60 mm @ 300 dpi = round(100*300/25.4) x round(60*300/25.4) = 1181 x 709 dot.
    // Senza rotazione, forzando le dimensioni, per verificare il puro dimensionamento.
    config()->set('wts.label_pdf.zpl_force_size', true);
    config()->set('wts.label_pdf.zpl_label_width_dots', 0);
    config()->set('wts.label_pdf.zpl_label_length_dots', 0);
    config()->set('wts.label_pdf.zpl_rotate', 0);
    config()->set('wts.label_pdf.width_mm', 100);
    config()->set('wts.label_pdf.height_mm', 60);
    config()->set('wts.label_pdf.dpi', 300);

    $unit = makeLabelUnit('LOT-2026-7777-BIG-BAG-01');
    $zpl = app(Modules\Wts\Actions\GenerateWtsUnitLabelZplAction::class)->execute($unit);

    expect($zpl)->toContain('^PW1181')->toContain('^LL709');
});

it('lets the printing page enqueue a test label and manage the queue', function (): void {
    Spatie\Permission\Models\Role::findOrCreate('wts_admin');
    $user = Modules\User\Models\User::factory()->create();
    $user->syncRoles(['wts_admin']);

    filament()->setCurrentPanel('wts');

    Livewire\Livewire::actingAs($user);
        Livewire\Livewire::test(Modules\Wts\Filament\Pages\WtsPrintingPage::class)
        ->call('printTest')
        ->assertHasNoErrors();

    $test = WtsPrintJob::query()->where('label_code', 'STAMPA-DI-PROVA')->first();
    expect($test)->not->toBeNull()
        ->and($test?->status_code)->toBe('pending')
        ->and($test?->zpl)->toContain('^XA')->toContain('STAMPA DI PROVA');

    WtsPrintJob::factory()->create(['status_code' => 'failed']);
    WtsPrintJob::factory()->create(['status_code' => 'printed']);

    Livewire\Livewire::actingAs($user);
        Livewire\Livewire::test(Modules\Wts\Filament\Pages\WtsPrintingPage::class)
        ->call('retryFailed')
        ->call('clearPrinted')
        ->assertHasNoErrors();

    expect(WtsPrintJob::query()->where('status_code', 'failed')->count())->toBe(0)
        ->and(WtsPrintJob::query()->where('status_code', 'printed')->count())->toBe(0);
});
