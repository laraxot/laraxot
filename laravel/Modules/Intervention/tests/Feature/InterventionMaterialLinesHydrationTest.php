<?php

declare(strict_types=1);

uses(\Modules\Intervention\Tests\TestCase::class);

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Modules\Intervention\Filament\Resources\InterventionResource;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Tests\TestCase;
use Modules\User\Database\Factories\UserFactory;

function hydrationIntervention(): Intervention
{
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Cliente Idratazione',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $id = DB::table('interventions')->insertGetId([
        'work_order_id' => null,
        'customer_id' => $customerId,
        'performed_by_user_id' => UserFactory::new()->createOne()->id,
        'scheduled_at' => CarbonImmutable::now(),
        'started_at' => CarbonImmutable::now(),
        'work_minutes' => 90,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return Intervention::query()->findOrFail($id);
}

it('turns the saved material lines back into repeater rows', function (): void {
    // Regression: the repeater was populated only through ->default(), which
    // does not run when the form is filled from an existing record because
    // `manual_material_lines` is not a column. It opened blank on edit, and
    // saving rebuilds the lines from scratch — so opening an intervention and
    // pressing save deleted every line the voice report had just written.
    $intervention = hydrationIntervention();

    $intervention->materialLines()->create([
        'description' => 'Scaldabagno',
        'quantity' => 1,
        'unit' => 'pz',
        'unit_price' => 210.0,
        'line_total' => 210.0,
        'extra_quantity' => 1,
        'extra_amount' => 210.0,
        'extra_status_code' => 'to_bill',
    ]);
    $intervention->materialLines()->create([
        'description' => 'Tubo multistrato',
        'quantity' => 3,
        'unit' => 'm',
        'unit_price' => 4.0,
        'line_total' => 12.0,
    ]);

    $rows = InterventionResource::materialLinesFormState($intervention->refresh());

    expect($rows)->toHaveCount(2)
        ->and($rows[0]['description'])->toBe('Scaldabagno')
        ->and($rows[0]['unit'])->toBe('pz')
        ->and($rows[0]['unit_price'])->toBe(210.0)
        ->and($rows[0]['extra_status_code'])->toBe('to_bill')
        // The dictated unit has to survive the round trip, or "three metres of
        // pipe" comes back as three pieces the first time anybody saves.
        ->and($rows[1]['unit'])->toBe('m')
        ->and($rows[1]['quantity'])->toBe(3.0)
        ->and($rows[1]['extra_status_code'])->toBe('pending');
});

it('gives back nothing for an intervention with no lines, and for no record', function (): void {
    expect(InterventionResource::materialLinesFormState(hydrationIntervention()))->toBe([])
        ->and(InterventionResource::materialLinesFormState(null))->toBe([]);
});
