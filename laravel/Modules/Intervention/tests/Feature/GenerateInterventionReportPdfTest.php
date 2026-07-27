<?php

declare(strict_types=1);

use Modules\Intervention\Tests\TestCase;

uses(TestCase::class);

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Models\Media;
use Modules\Intervention\Actions\GenerateInterventionReportPdfAction;
use Modules\Intervention\Models\Intervention;
use Modules\Signature\Actions\StoreSignatureForModelAction;
use Modules\User\Database\Factories\UserFactory;

const PDF_TINY_PNG = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

function makePdfCustomerId(string $name = 'Cliente PDF S.r.l.'): int
{
    return (int) DB::table('customers')->insertGetId([
        'legal_name' => $name,
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

/**
 * @param  array<string, mixed>  $overrides
 */
function makePdfIntervention(array $overrides = []): Intervention
{
    $attributes = array_merge([
        'work_order_id' => null,
        'customer_id' => null,
        'performed_by_user_id' => UserFactory::new()->createOne(['name' => 'Operatore PDF'])->id,
        'scheduled_at' => CarbonImmutable::now(),
        'started_at' => CarbonImmutable::now(),
        'work_minutes' => 90,
        'notes' => 'Sostituzione valvola e collaudo impianto.',
        'outcome_code' => 'completed',
        'materials_total' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ], $overrides);

    $id = DB::table('interventions')->insertGetId($attributes);

    return Intervention::query()->findOrFail($id);
}

beforeEach(function (): void {
    Storage::fake('public');
});

it('generates a self-contained PDF with materials, photos and signature', function (): void {
    $intervention = makePdfIntervention(['customer_id' => makePdfCustomerId()]);
    $intervention->materialLines()->create([
        'description' => 'Valvola a sfera', 'quantity' => 2, 'unit' => 'pz', 'unit_price' => 7.5, 'line_total' => 15.0,
    ]);

    Storage::disk('public')->put('media/interventions/photo.jpg', 'binary');
    Media::query()->create([
        'mediable_type' => Intervention::class,
        'mediable_id' => $intervention->id,
        'kind' => 'photo',
        'disk' => 'public',
        'path' => 'media/interventions/photo.jpg',
        'mime_type' => 'image/jpeg',
        'meta' => ['scope' => 'voice_report'],
    ]);

    app(StoreSignatureForModelAction::class)->execute(
        dataUrl: PDF_TINY_PNG,
        signableType: Intervention::class,
        signableId: (int) $intervention->id,
        userId: (int) $intervention->performed_by_user_id,
    );

    $result = app(GenerateInterventionReportPdfAction::class)->execute($intervention);

    expect(substr($result['content'], 0, 4))->toBe('%PDF')
        ->and(strlen($result['content']))->toBeGreaterThan(1000)
        ->and($result['mime'])->toBe('application/pdf')
        ->and($result['filename'])->toBe("rapportino_intervento_{$intervention->id}.pdf");
});

it('generates the PDF for a work-order customer with assigned operators and no attachments', function (): void {
    $customerId = makePdfCustomerId('Cliente Commessa');
    $status = DB::table('work_order_statuses')->insertGetId([
        'code' => 'in_progress', 'name' => 'In lavorazione', 'color' => 'amber',
        'is_terminal' => 0, 'sort_order' => 30, 'created_at' => now(), 'updated_at' => now(),
    ]);
    $workOrderId = DB::table('work_orders')->insertGetId([
        'code' => 'WO-PDF', 'customer_id' => $customerId, 'status_id' => $status,
        'title' => 'Commessa PDF', 'opened_at' => now(), 'created_at' => now(), 'updated_at' => now(),
    ]);

    $intervention = makePdfIntervention(['work_order_id' => $workOrderId, 'outcome_code' => 'suspended', 'notes' => null]);
    $assignee = UserFactory::new()->createOne(['name' => 'Tecnico Assegnato']);
    $intervention->assignedUsers()->attach($assignee->id);

    $result = app(GenerateInterventionReportPdfAction::class)->execute($intervention);

    expect(substr($result['content'], 0, 4))->toBe('%PDF');
});

it('skips photo media whose file is missing from disk', function (): void {
    $intervention = makePdfIntervention(['customer_id' => makePdfCustomerId()]);

    // Media pointing to a non-existent file → skipped, PDF still renders.
    Media::query()->create([
        'mediable_type' => Intervention::class, 'mediable_id' => $intervention->id,
        'kind' => 'photo', 'disk' => 'public', 'path' => 'media/interventions/gone.jpg',
        'mime_type' => 'image/jpeg', 'meta' => ['scope' => 'voice_report'],
    ]);

    $result = app(GenerateInterventionReportPdfAction::class)->execute($intervention);

    expect(substr($result['content'], 0, 4))->toBe('%PDF');
});

it('generates the PDF even with no customer resolvable', function (): void {
    $intervention = makePdfIntervention();

    $result = app(GenerateInterventionReportPdfAction::class)->execute($intervention);

    expect(substr($result['content'], 0, 4))->toBe('%PDF');
});
