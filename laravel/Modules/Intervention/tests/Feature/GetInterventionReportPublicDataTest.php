<?php

declare(strict_types=1);

uses(TestCase::class);

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Modules\Document\Models\Media;
use Modules\Intervention\Actions\GetInterventionReportPublicDataAction;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Tests\TestCase;
use Modules\Signature\Actions\StoreSignatureForModelAction;
use Modules\User\Models\User;

const PUB_TINY_PNG = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

function pubCustomerId(string $name): int
{
    return (int) DB::table('customers')->insertGetId([
        'legal_name' => $name, 'is_active' => 1, 'created_at' => now(), 'updated_at' => now(),
    ]);
}

function pubIntervention(array $overrides = []): Intervention
{
    $attributes = array_merge([
        'work_order_id' => null,
        'customer_id' => null,
        'performed_by_user_id' => User::factory()->create(['name' => 'Operatore'])->id,
        'scheduled_at' => CarbonImmutable::parse('2026-05-25 09:30'),
        'started_at' => CarbonImmutable::now(),
        'work_minutes' => 90,
        'notes' => 'Note del lavoro',
        'outcome_code' => 'completed',
        'materials_total' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ], $overrides);

    $id = DB::table('interventions')->insertGetId($attributes);

    return Intervention::query()->findOrFail($id);
}

beforeEach(fn () => Storage::fake('public'));

it('builds full report data: work-order customer wins, operators, materials, photo, signature', function (): void {
    $workOrderCustomer = pubCustomerId('Cliente Commessa');
    $status = DB::table('work_order_statuses')->insertGetId([
        'code' => 'in_progress', 'name' => 'In lavorazione', 'color' => 'amber',
        'is_terminal' => 0, 'sort_order' => 30, 'created_at' => now(), 'updated_at' => now(),
    ]);
    $workOrderId = DB::table('work_orders')->insertGetId([
        'code' => 'WO-1', 'customer_id' => $workOrderCustomer, 'status_id' => $status,
        'title' => 'Commessa', 'opened_at' => now(), 'created_at' => now(), 'updated_at' => now(),
    ]);

    // Direct customer set too and DIFFERENT → the work-order customer must win.
    $intervention = pubIntervention([
        'work_order_id' => $workOrderId,
        'customer_id' => pubCustomerId('Cliente Diretto'),
        'work_minutes' => 75,
        'total_amount' => 120,
    ]);

    // Assigned operators take precedence over the performer.
    $mario = User::factory()->create(['name' => 'Mario']);
    $luigi = User::factory()->create(['name' => 'Luigi']);
    $intervention->assignedUsers()->attach([$mario->id, $luigi->id]);

    $intervention->materialLines()->create(['description' => 'Valvola', 'quantity' => 2.5, 'unit' => 'pz', 'unit_price' => 7.5, 'line_total' => 18.75]);
    $intervention->materialLines()->create(['description' => 'Tubo', 'quantity' => 3, 'unit' => 'm', 'unit_price' => 2, 'line_total' => 6]);
    $intervention->materialLines()->create(['description' => 'Raccordo', 'quantity' => 1.25, 'unit' => 'pz', 'unit_price' => 1.25, 'line_total' => 1.25]);

    Storage::disk('public')->put('media/report-photo.jpg', 'binary');
    Media::query()->create(['mediable_type' => Intervention::class, 'mediable_id' => $intervention->id, 'kind' => 'photo', 'disk' => 'public', 'path' => 'media/report-photo.jpg', 'mime_type' => 'image/jpeg', 'meta' => []]);
    // Photo whose file is missing → skipped.
    Media::query()->create(['mediable_type' => Intervention::class, 'mediable_id' => $intervention->id, 'kind' => 'photo', 'disk' => 'public', 'path' => 'media/report-missing.jpg', 'mime_type' => 'image/jpeg', 'meta' => []]);
    // Non-photo media → skipped.
    Media::query()->create(['mediable_type' => Intervention::class, 'mediable_id' => $intervention->id, 'kind' => 'document', 'disk' => 'public', 'path' => 'media/report-photo.jpg', 'mime_type' => 'image/jpeg', 'meta' => []]);

    app(StoreSignatureForModelAction::class)->execute(
        dataUrl: PUB_TINY_PNG,
        signableType: Intervention::class,
        signableId: (int) $intervention->id,
        userId: (int) $mario->id,
    );

    $data = app(GetInterventionReportPublicDataAction::class)->execute($intervention);

    expect($data['id'])->toBe((int) $intervention->id)
        ->and($data['customer'])->toBe('Cliente Commessa')
        ->and($data['operator'])->toBe('Mario, Luigi')
        ->and($data['scheduled_at'])->toBe('25/05/2026 09:30')
        ->and($data['work_minutes'])->toBe(75)
        ->and($data['outcome_code'])->toBe('completed')
        ->and($data['outcome_label'])->toBe('Concluso')
        ->and($data['notes'])->toBe('Note del lavoro')
        ->and($data['materials'])->toHaveCount(3)
        ->and($data['materials'][0])->toMatchArray(['description' => 'Valvola', 'quantity' => '2,5', 'unit' => 'pz', 'unit_price' => '7,50', 'line_total' => '18,75'])
        ->and($data['materials'][1])->toMatchArray(['quantity' => '3', 'unit' => 'm', 'unit_price' => '2,00', 'line_total' => '6,00'])
        ->and($data['materials'][2]['quantity'])->toBe('1,25')
        ->and($data['materials_total'])->toBe('26,00')
        ->and($data['photos'])->toHaveCount(1)
        ->and($data['photos'][0])->toContain('report-photo.jpg')
        ->and($data['signature'])->not->toBeNull()
        ->and($data['total'])->toBe('120,00')
        ->and($data['total_is_payable'])->toBeTrue()
        ->and($data['has_signature'])->toBeTrue()
        ->and($data['is_paid'])->toBeFalse()
        ->and($data['paid_at'])->toBeNull()
        ->and($data['payments_enabled'])->toBeFalse();
});

it('exposes payment flags for a paid report with payments enabled', function (): void {
    config(['cashier.secret' => 'sk_test_dummy']);

    $intervention = pubIntervention([
        'total_amount' => 0,
        'report_paid_at' => CarbonImmutable::parse('2026-05-26 10:00'),
    ]);

    $data = app(GetInterventionReportPublicDataAction::class)->execute($intervention);

    expect($data['payments_enabled'])->toBeTrue()
        ->and($data['total_is_payable'])->toBeFalse()
        ->and($data['has_signature'])->toBeFalse()
        ->and($data['is_paid'])->toBeTrue()
        ->and($data['paid_at'])->toBe('26/05/2026');
});

it('falls back to the performer and direct customer, suspended, no attachments', function (): void {
    $performer = User::factory()->create(['name' => 'Solo Operatore']);
    $intervention = pubIntervention([
        'customer_id' => pubCustomerId('Cliente Diretto'),
        'performed_by_user_id' => $performer->id,
        'outcome_code' => 'suspended',
        'notes' => null,
        'work_minutes' => 45,
        'scheduled_at' => null,
    ]);

    $data = app(GetInterventionReportPublicDataAction::class)->execute($intervention);

    expect($data['customer'])->toBe('Cliente Diretto')
        ->and($data['operator'])->toBe('Solo Operatore')
        ->and($data['outcome_label'])->toBe('Sospeso')
        ->and($data['notes'])->toBe('')
        ->and($data['work_minutes'])->toBe(45)
        ->and($data['scheduled_at'])->toBeNull()
        ->and($data['materials'])->toBe([])
        ->and($data['materials_total'])->toBe('0,00')
        ->and($data['photos'])->toBe([])
        ->and($data['signature'])->toBeNull();
});

it('shows a dash for an unresolvable customer and maps the quotation outcome', function (): void {
    $intervention = pubIntervention(['outcome_code' => 'quotation']);

    $data = app(GetInterventionReportPublicDataAction::class)->execute($intervention);

    expect($data['customer'])->toBe('—')
        ->and($data['outcome_label'])->toBe('Preventivo');
});

it('maps a custom outcome code verbatim and an empty outcome to a dash', function (): void {
    $custom = pubIntervention(['outcome_code' => 'riparato']);
    $empty = pubIntervention(['outcome_code' => null]);

    expect(app(GetInterventionReportPublicDataAction::class)->execute($custom)['outcome_label'])->toBe('riparato')
        ->and(app(GetInterventionReportPublicDataAction::class)->execute($empty)['outcome_label'])->toBe('—')
        ->and(app(GetInterventionReportPublicDataAction::class)->execute($empty)['outcome_code'])->toBe('');
});
