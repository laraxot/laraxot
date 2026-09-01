<?php

declare(strict_types=1);

uses(\Modules\Intervention\Tests\TestCase::class);

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Modules\Intervention\Data\ExtraMaterialsRecap;
use Modules\Intervention\Filament\Widgets\ExtraMaterialsRecapStats;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Queries\GetExtraMaterialsRecapQuery;
use Modules\Intervention\Tests\TestCase;
use Modules\User\Database\Factories\UserFactory;

use function Pest\Laravel\actingAs;

// Il periodo esaminato va dall'inizio del mese a ora, ma gli interventi vengono
// creati a "inizio mese + 1 giorno": il primo del mese quella data e' nel futuro
// e il recap non trova nulla. Il tempo viene fissato a meta' mese perche' il
// risultato non dipenda dal giorno in cui la suite gira.
beforeEach(function (): void {
    $this->travelTo(CarbonImmutable::create(2026, 6, 15, 10, 0, 0));
});

afterEach(function (): void {
    $this->travelBack();
});

function recapCustomerId(): int
{
    return (int) DB::table('customers')->insertGetId([
        'legal_name' => 'Cliente Extra '.uniqid(),
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

function recapIntervention(string $startedAt): Intervention
{
    $id = DB::table('interventions')->insertGetId([
        'work_order_id' => null,
        'customer_id' => recapCustomerId(),
        'performed_by_user_id' => UserFactory::new()->createOne()->id,
        'scheduled_at' => $startedAt,
        'started_at' => $startedAt,
        'work_minutes' => 60,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return Intervention::query()->findOrFail($id);
}

function recapLine(Intervention $intervention, float $extraAmount, string $status, float $extraQuantity = 1.0): void
{
    $intervention->materialLines()->create([
        'description' => 'Materiale',
        'quantity' => 1,
        'unit' => 'pz',
        'unit_price' => $extraAmount,
        'line_total' => $extraAmount,
        'extra_quantity' => $extraQuantity,
        'extra_amount' => $extraAmount,
        'extra_status_code' => $status,
    ]);
}

function recapThisMonth(): ExtraMaterialsRecap
{
    return app(GetExtraMaterialsRecapQuery::class)->execute(
        CarbonImmutable::now()->startOfMonth(),
        CarbonImmutable::now(),
    );
}

it('splits the extra by what was decided about it', function (): void {
    $intervention = recapIntervention(CarbonImmutable::now()->startOfMonth()->addDay()->toDateTimeString());

    recapLine($intervention, 120.0, 'to_bill');
    recapLine($intervention, 30.0, 'pending');
    recapLine($intervention, 45.5, 'waived');

    $recap = recapThisMonth();

    expect($recap->toBill)->toBe(120.0)
        ->and($recap->pending)->toBe(30.0)
        ->and($recap->waived)->toBe(45.5)
        ->and($recap->intercepted())->toBe(195.5)
        ->and($recap->lineCount)->toBe(3)
        ->and($recap->hasUndecided())->toBeTrue();
});

it('ignores lines that the quotation already covered', function (): void {
    $intervention = recapIntervention(CarbonImmutable::now()->startOfMonth()->addDay()->toDateTimeString());

    // Covered by the quotation: extra_quantity is zero, so it is not extra.
    recapLine($intervention, 0.0, 'pending', extraQuantity: 0.0);
    recapLine($intervention, 80.0, 'to_bill');

    $recap = recapThisMonth();

    expect($recap->lineCount)->toBe(1)
        ->and($recap->intercepted())->toBe(80.0);
});

it('leaves out interventions from other months', function (): void {
    $old = recapIntervention(CarbonImmutable::now()->startOfMonth()->subDays(3)->toDateTimeString());
    recapLine($old, 500.0, 'to_bill');

    expect(recapThisMonth()->intercepted())->toBe(0.0);
});

it('leaves out deleted interventions', function (): void {
    $intervention = recapIntervention(CarbonImmutable::now()->startOfMonth()->addDay()->toDateTimeString());
    recapLine($intervention, 90.0, 'to_bill');
    $intervention->delete();

    expect(recapThisMonth()->intercepted())->toBe(0.0);
});

it('treats an unknown status as still undecided instead of dropping the money', function (): void {
    $intervention = recapIntervention(CarbonImmutable::now()->startOfMonth()->addDay()->toDateTimeString());
    recapLine($intervention, 60.0, 'qualcosa_di_nuovo');

    $recap = recapThisMonth();

    expect($recap->pending)->toBe(60.0)
        ->and($recap->intercepted())->toBe(60.0);
});

it('counts the faults logged on site', function (): void {
    $intervention = recapIntervention(CarbonImmutable::now()->startOfMonth()->addDay()->toDateTimeString());
    $intervention->issueReports()->create(['body' => 'La caldaia ha diciotto anni']);
    $intervention->issueReports()->create(['body' => 'Il collettore perde']);

    expect(recapThisMonth()->issueCount)->toBe(2);
});

it('reports zero on a quiet month without failing', function (): void {
    $recap = recapThisMonth();

    expect($recap->intercepted())->toBe(0.0)
        ->and($recap->lineCount)->toBe(0)
        ->and($recap->issueCount)->toBe(0)
        ->and($recap->hasUndecided())->toBeFalse();
});

it('renders the owner widget with the period figures', function (): void {
    actingAs(UserFactory::new()->createOne());

    $intervention = recapIntervention(CarbonImmutable::now()->startOfMonth()->addDay()->toDateTimeString());
    recapLine($intervention, 120.0, 'to_bill');
    recapLine($intervention, 45.5, 'waived');

    Livewire::test(ExtraMaterialsRecapStats::class)
        ->assertOk()
        ->assertSee('165,50 €')
        ->assertSee('120,00 €')
        ->assertSee('45,50 €');
});
