<?php

declare(strict_types=1);

uses(TestCase::class);

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Modules\Intervention\Actions\ApplyInterventionReportDraftAction;
use Modules\Intervention\Data\DictatedMaterialLine;
use Modules\Intervention\Data\InterventionReportDraft;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Queries\GetPastInterventionNotesForReportQuery;
use Modules\Intervention\Tests\TestCase;
use Modules\User\Models\User;

function makeReportCustomerId(string $name = 'Cliente Report S.r.l.'): int
{
    return (int) DB::table('customers')->insertGetId([
        'legal_name' => $name,
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

function makeIntervention(array $overrides = []): Intervention
{
    $attributes = array_merge([
        'work_order_id' => null,
        'customer_id' => null,
        'performed_by_user_id' => User::factory()->create()->id,
        'scheduled_at' => CarbonImmutable::now(),
        'started_at' => CarbonImmutable::now(),
        'work_minutes' => 0,
        'notes' => null,
        'outcome_code' => null,
        'materials_total' => 0,
        'created_at' => now(),
        'updated_at' => now(),
    ], $overrides);

    $id = DB::table('interventions')->insertGetId($attributes);

    return Intervention::query()->findOrFail($id);
}

// ── ApplyInterventionReportDraftAction ────────────────────────────────────────

it('applies a confirmed draft: notes, minutes, outcome and material lines', function (): void {
    $intervention = makeIntervention(['customer_id' => makeReportCustomerId()]);

    $draft = new InterventionReportDraft(
        notes: 'Sostituita valvola e sistemato quadro',
        workMinutes: 95,
        outcomeCode: 'completed',
        materials: [
            new DictatedMaterialLine('Valvola', 2.0, 5.0, matchedProductId: 1),
            new DictatedMaterialLine('Raccordo libero', 3.0, 1.5),
        ],
    );

    $result = app(ApplyInterventionReportDraftAction::class)->execute($intervention, $draft);

    expect($result->notes)->toBe('Sostituita valvola e sistemato quadro')
        ->and($result->notes_html)->not->toBeNull()
        ->and((int) $result->work_minutes)->toBe(95)
        ->and($result->outcome_code)->toBe('completed')
        ->and((float) $result->materials_total)->toBe(14.5);

    $lines = $result->materialLines()->orderBy('id')->get();
    expect($lines)->toHaveCount(2)
        ->and((float) $lines[0]->line_total)->toBe(10.0)
        ->and($lines[0]->unit)->toBe('pz')
        ->and((float) $lines[1]->line_total)->toBe(4.5);
});

it('appends dictated materials to existing lines and recomputes the total', function (): void {
    $intervention = makeIntervention(['customer_id' => makeReportCustomerId()]);
    $intervention->materialLines()->create([
        'description' => 'Materiale preesistente',
        'quantity' => 1,
        'unit' => 'pz',
        'unit_price' => 10.0,
        'line_total' => 10.0,
    ]);

    $draft = new InterventionReportDraft(
        notes: '',
        workMinutes: null,
        outcomeCode: null,
        materials: [new DictatedMaterialLine('Guarnizione', 4.0, 2.0)],
    );

    $result = app(ApplyInterventionReportDraftAction::class)->execute($intervention, $draft);

    expect($result->materialLines()->count())->toBe(2)
        ->and((float) $result->materials_total)->toBe(18.0);
});

it('does not overwrite scalar fields when the draft carries no values', function (): void {
    $intervention = makeIntervention([
        'customer_id' => makeReportCustomerId(),
        'notes' => 'Nota originale',
        'work_minutes' => 40,
        'outcome_code' => 'suspended',
    ]);

    $draft = new InterventionReportDraft(
        notes: '',
        workMinutes: null,
        outcomeCode: null,
        materials: [],
    );

    $result = app(ApplyInterventionReportDraftAction::class)->execute($intervention, $draft);

    expect($result->notes)->toBe('Nota originale')
        ->and((int) $result->work_minutes)->toBe(40)
        ->and($result->outcome_code)->toBe('suspended')
        ->and((float) $result->materials_total)->toBe(0.0)
        ->and($result->materialLines()->count())->toBe(0);
});

// ── GetPastInterventionNotesForReportQuery ────────────────────────────────────

it('returns notes of past validated interventions for the same direct customer', function (): void {
    $customerId = makeReportCustomerId();
    $current = makeIntervention(['customer_id' => $customerId]);

    makeIntervention([
        'customer_id' => $customerId,
        'notes' => 'Vecchio intervento A',
        'validated_at' => now(),
        'scheduled_at' => CarbonImmutable::now()->subDays(2),
    ]);
    makeIntervention([
        'customer_id' => $customerId,
        'notes' => 'Vecchio intervento B',
        'validated_at' => now(),
        'scheduled_at' => CarbonImmutable::now()->subDay(),
    ]);
    // Not validated → excluded.
    makeIntervention(['customer_id' => $customerId, 'notes' => 'Bozza']);
    // Empty notes → excluded.
    makeIntervention(['customer_id' => $customerId, 'notes' => '', 'validated_at' => now()]);

    $result = app(GetPastInterventionNotesForReportQuery::class)->execute($current);

    expect($result)->toBe([
        ['notes' => 'Vecchio intervento B'],
        ['notes' => 'Vecchio intervento A'],
    ]);
});

it('finds past interventions through the work order customer and respects the limit', function (): void {
    $customerId = makeReportCustomerId();
    $status = DB::table('work_order_statuses')->insertGetId([
        'code' => 'in_progress', 'name' => 'In lavorazione', 'color' => 'amber',
        'is_terminal' => 0, 'sort_order' => 30, 'created_at' => now(), 'updated_at' => now(),
    ]);
    $workOrderId = DB::table('work_orders')->insertGetId([
        'code' => 'WO-1', 'customer_id' => $customerId, 'status_id' => $status,
        'title' => 'Commessa', 'opened_at' => now(), 'created_at' => now(), 'updated_at' => now(),
    ]);

    $current = makeIntervention(['work_order_id' => $workOrderId]);

    foreach (range(1, 4) as $i) {
        makeIntervention([
            'work_order_id' => $workOrderId,
            'notes' => "Nota {$i}",
            'validated_at' => now(),
            'scheduled_at' => CarbonImmutable::now()->subDays(10 - $i),
        ]);
    }

    $result = app(GetPastInterventionNotesForReportQuery::class)->execute($current, limit: 2);

    expect($result)->toHaveCount(2)
        ->and($result[0]['notes'])->toBe('Nota 4');
});

it('returns no examples when the intervention has no resolvable customer', function (): void {
    $current = makeIntervention();

    expect(app(GetPastInterventionNotesForReportQuery::class)->execute($current))->toBe([]);
});
