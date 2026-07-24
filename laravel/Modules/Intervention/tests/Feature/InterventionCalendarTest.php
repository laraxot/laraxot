<?php

declare(strict_types=1);

uses(\Modules\Intervention\Tests\TestCase::class);

use Modules\User\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Modules\Intervention\Filament\Pages\InterventionCalendar;
use Modules\Intervention\Models\Intervention;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

function makeCalendarWorkOrderId(string $code, string $title, string $customerName): int
{
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => $customerName,
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return DB::table('work_orders')->insertGetId([
        'code' => $code,
        'customer_id' => $customerId,
        'title' => $title,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

function calendarColors(): array
{
    // Deve restare allineata alla palette di InterventionCalendar
    return [
        '#dc2626',
        '#2563eb',
        '#16a34a',
        '#d97706',
        '#7c3aed',
        '#ea580c',
        '#0f766e',
        '#db2777',
        '#1d4ed8',
        '#166534',
    ];
}

function calendarTextColor(string $hex): string
{
    $normalized = ltrim($hex, '#');

    $red = hexdec(substr($normalized, 0, 2));
    $green = hexdec(substr($normalized, 2, 2));
    $blue = hexdec(substr($normalized, 4, 2));
    $luminance = (0.299 * $red) + (0.587 * $green) + (0.114 * $blue);

    return $luminance < 140 ? '#ffffff' : '#111827';
}

it('returns calendar events with expected props', function (): void {
    // Arrange
    filament()->setCurrentPanel(null);

    $operatorRole = Role::findOrCreate('operator');
    $operator = User::factory()->create(['name' => 'Operatore Calendario']);
    $operator->syncRoles([$operatorRole]);

    $workOrderId = makeCalendarWorkOrderId('WO-CAL-0001', 'Impianto citofonico', 'Condominio Test');

    $scheduled = CarbonImmutable::parse('2026-01-10 09:00:00');
    $intervention = Intervention::query()->create([
        'work_order_id' => $workOrderId,
        'performed_by_user_id' => $operator->id,
        'scheduled_at' => $scheduled,
        'started_at' => $scheduled,
        'ended_at' => $scheduled->addMinutes(60),
        'work_minutes' => 60,
        'notes' => 'Sostituzione timer luci scale',
        'outcome_code' => 'completed',
        'amount' => 100.00,
    ]);

    // Act
    /** @var \Livewire\Features\SupportTesting\Testable<InterventionCalendar> $component */
    $component = Livewire::test(InterventionCalendar::class);
    $events = $component->instance()->getCalendarEvents('2026-01-01', '2026-01-31');

    // Assert
    expect($events)->toBeArray()->and(count($events))->toBe(1);
    expect($events[0]['id'])->toBe($intervention->id);
    expect($events[0]['title'])->toContain('Condominio Test');
    // The event chip must carry the full intervention text, not just the time.
    expect($events[0]['title'])->toContain('Impianto citofonico');
    expect($events[0]['start'])->toBeString();
    expect($events[0]['extendedProps']['operator_name'])->toBe('Operatore Calendario');
    expect($events[0]['extendedProps']['notes'])->toContain('Sostituzione timer');

    $palette = calendarColors();
    $expectedColor = $palette[$operator->id % count($palette)];
    $expectedTextColor = calendarTextColor($expectedColor);
    expect($events[0]['backgroundColor'])->toBe($expectedColor);
    expect($events[0]['textColor'])->toBe($expectedTextColor);
    expect($events[0]['extendedProps']['color'])->toBe($expectedColor);
    expect($events[0]['extendedProps']['text_color'])->toBe($expectedTextColor);
});

it('filters calendar events by operator when in operator panel', function (): void {
    // Arrange
    $operatorRole = Role::findOrCreate('operator');

    $currentOperator = User::factory()->create(['name' => 'Operatore A']);
    $currentOperator->syncRoles([$operatorRole]);

    $otherOperator = User::factory()->create(['name' => 'Operatore B']);
    $otherOperator->syncRoles([$operatorRole]);

    $workOrderId = makeCalendarWorkOrderId('WO-CAL-0002', 'Allarme - verifica sensori', 'Cliente Calendario');

    $day = CarbonImmutable::parse('2026-02-02 10:00:00');
    $mine = Intervention::query()->create([
        'work_order_id' => $workOrderId,
        'performed_by_user_id' => $currentOperator->id,
        'scheduled_at' => $day,
        'started_at' => $day,
        'ended_at' => $day->addMinutes(30),
        'work_minutes' => 30,
    ]);

    Intervention::query()->create([
        'work_order_id' => $workOrderId,
        'performed_by_user_id' => $otherOperator->id,
        'scheduled_at' => $day->addHours(2),
        'started_at' => $day->addHours(2),
        'ended_at' => $day->addHours(2)->addMinutes(30),
        'work_minutes' => 30,
    ]);

    filament()->setCurrentPanel('operator');
    actingAs($currentOperator);

    // Act
    /** @var \Livewire\Features\SupportTesting\Testable<InterventionCalendar> $component */
    $component = Livewire::test(InterventionCalendar::class);
    $events = $component->instance()->getCalendarEvents('2026-02-01', '2026-02-28');

    // Assert
    expect(array_column($events, 'id'))->toBe([$mine->id]);
});

it('keeps gerardo operator calendar limited to his own interventions', function (): void {
    $adminRole = Role::findOrCreate('admin');
    $operatorRole = Role::findOrCreate('operator');

    $gerardo = User::factory()->create([
        'name' => 'Gerardo Demo',
        'email' => 'info@gsbuildingservice.it',
    ]);
    $gerardo->syncRoles([$adminRole, $operatorRole]);

    $apprentice = User::factory()->create(['name' => 'Apprendista Demo']);
    $apprentice->syncRoles([$operatorRole]);

    $outsider = User::factory()->create(['name' => 'Operatore Esterno']);
    $outsider->syncRoles([$operatorRole]);

    $assignedWorkOrderId = makeCalendarWorkOrderId('WO-CAL-0002A', 'Manutenzione team Gerardo', 'Cliente Team');
    $externalWorkOrderId = makeCalendarWorkOrderId('WO-CAL-0002B', 'Intervento esterno', 'Cliente Esterno');

    DB::table('work_order_assignments')->insert([
        'work_order_id' => $assignedWorkOrderId,
        'user_id' => $gerardo->id,
        'role' => 'lead-technician',
        'assigned_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $day = CarbonImmutable::parse('2026-02-03 08:30:00');
    Intervention::query()->create([
        'work_order_id' => $assignedWorkOrderId,
        'performed_by_user_id' => $apprentice->id,
        'scheduled_at' => $day,
        'started_at' => $day,
        'ended_at' => $day->addMinutes(45),
        'work_minutes' => 45,
    ]);

    Intervention::query()->create([
        'work_order_id' => $externalWorkOrderId,
        'performed_by_user_id' => $outsider->id,
        'scheduled_at' => $day->addHour(),
        'started_at' => $day->addHour(),
        'ended_at' => $day->addHour()->addMinutes(45),
        'work_minutes' => 45,
    ]);

    filament()->setCurrentPanel('operator');
    actingAs($gerardo);

    /** @var \Livewire\Features\SupportTesting\Testable<InterventionCalendar> $component */
    $component = Livewire::test(InterventionCalendar::class);
    $events = $component->instance()->getCalendarEvents('2026-02-01', '2026-02-28');
    $legend = $component->instance()->getOperatorLegend();

    expect($events)->toBeArray()->toBeEmpty()
        ->and($legend)->toBeArray()->toBeEmpty();
});

it('shows gerardo the interventions of his assigned team in admin calendar', function (): void {
    $adminRole = Role::findOrCreate('admin');
    $operatorRole = Role::findOrCreate('operator');

    $gerardo = User::factory()->create([
        'name' => 'Gerardo Demo',
        'email' => 'info@gsbuildingservice.it',
    ]);
    $gerardo->syncRoles([$adminRole, $operatorRole]);

    $apprentice = User::factory()->create(['name' => 'Apprendista Demo']);
    $apprentice->syncRoles([$operatorRole]);

    $outsider = User::factory()->create(['name' => 'Operatore Esterno']);
    $outsider->syncRoles([$operatorRole]);

    $assignedWorkOrderId = makeCalendarWorkOrderId('WO-CAL-0002C', 'Manutenzione team Gerardo', 'Cliente Team');
    $externalWorkOrderId = makeCalendarWorkOrderId('WO-CAL-0002D', 'Intervento esterno', 'Cliente Esterno');

    DB::table('work_order_assignments')->insert([
        'work_order_id' => $assignedWorkOrderId,
        'user_id' => $gerardo->id,
        'role' => 'lead-technician',
        'assigned_at' => now(),
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $day = CarbonImmutable::parse('2026-02-04 08:30:00');
    $teamIntervention = Intervention::query()->create([
        'work_order_id' => $assignedWorkOrderId,
        'performed_by_user_id' => $apprentice->id,
        'scheduled_at' => $day,
        'started_at' => $day,
        'ended_at' => $day->addMinutes(45),
        'work_minutes' => 45,
    ]);

    Intervention::query()->create([
        'work_order_id' => $externalWorkOrderId,
        'performed_by_user_id' => $outsider->id,
        'scheduled_at' => $day->addHour(),
        'started_at' => $day->addHour(),
        'ended_at' => $day->addHour()->addMinutes(45),
        'work_minutes' => 45,
    ]);

    // Enable the assigned-perimeter feature for this account so the admin
    // calendar is scoped to the assigned team, regardless of the production default.
    $accounts = config('account_features.accounts');
    $accounts['info@gsbuildingservice.it']['features']['admin.assigned_perimeter'] = true;
    config(['account_features.accounts' => $accounts]);

    filament()->setCurrentPanel('admin');
    actingAs($gerardo);

    /** @var \Livewire\Features\SupportTesting\Testable<InterventionCalendar> $component */
    $component = Livewire::test(InterventionCalendar::class);
    $events = $component->instance()->getCalendarEvents('2026-02-01', '2026-02-28');
    $legend = $component->instance()->getOperatorLegend();

    expect(array_column($events, 'id'))->toContain($teamIntervention->id)
        ->and(array_column($legend, 'name'))->toContain('Apprendista Demo')
        ->and(array_column($legend, 'name'))->not->toContain('Operatore Esterno');
});

it('opens and closes the intervention detail modal', function (): void {
    // Arrange
    filament()->setCurrentPanel(null);

    $operatorRole = Role::findOrCreate('operator');
    $operator = User::factory()->create(['name' => 'Operatore Dettaglio']);
    $operator->syncRoles([$operatorRole]);

    $workOrderId = makeCalendarWorkOrderId('WO-CAL-0003', 'Videosorveglianza - installazione', 'Condominio Dettagli');

    $scheduled = CarbonImmutable::parse('2026-03-03 14:00:00');
    $intervention = Intervention::query()->create([
        'work_order_id' => $workOrderId,
        'performed_by_user_id' => $operator->id,
        'scheduled_at' => $scheduled,
        'started_at' => $scheduled,
        'ended_at' => $scheduled->addMinutes(90),
        'work_minutes' => 90,
        'notes' => 'Installazione NVR + 4 telecamere',
        'outcome_code' => 'completed',
        'amount' => 195.00,
    ]);

    /** @var \Livewire\Features\SupportTesting\Testable<InterventionCalendar> $component */
    $component = Livewire::test(InterventionCalendar::class);

    // Act + Assert (open)
    $component->call('openInterventionDetail', $intervention->id)
        ->assertSet('showModal', true)
        ->assertSet('selectedIntervention.id', $intervention->id)
        ->assertSet('selectedIntervention.operator', 'Operatore Dettaglio')
        ->assertSet('selectedIntervention.customer', 'Condominio Dettagli')
        ->assertSet('selectedIntervention.notes', 'Installazione NVR + 4 telecamere');

    // Act + Assert (close)
    $component->call('closeModal')
        ->assertSet('showModal', false)
        ->assertSet('selectedIntervention', []);
});

it('returns operator legend and respects operator panel filtering', function (): void {
    // Arrange
    $operatorRole = Role::findOrCreate('operator');

    $op1 = User::factory()->create(['name' => 'Op Legend 1']);
    $op1->syncRoles([$operatorRole]);

    $op2 = User::factory()->create(['name' => 'Op Legend 2']);
    $op2->syncRoles([$operatorRole]);

    $workOrderId = makeCalendarWorkOrderId('WO-CAL-0004', 'Rete dati - cablaggio', 'Cliente Legenda');
    $day = CarbonImmutable::parse('2026-03-05 09:00:00');

    Intervention::query()->create([
        'work_order_id' => $workOrderId,
        'performed_by_user_id' => $op1->id,
        'scheduled_at' => $day,
        'started_at' => $day,
        'ended_at' => $day->addMinutes(60),
        'work_minutes' => 60,
    ]);
    Intervention::query()->create([
        'work_order_id' => $workOrderId,
        'performed_by_user_id' => $op2->id,
        'scheduled_at' => $day->addHours(2),
        'started_at' => $day->addHours(2),
        'ended_at' => $day->addHours(3),
        'work_minutes' => 60,
    ]);

    // Act (admin / no panel filter)
    filament()->setCurrentPanel(null);
    /** @var \Livewire\Features\SupportTesting\Testable<InterventionCalendar> $component */
    $component = Livewire::test(InterventionCalendar::class);
    $legend = $component->instance()->getOperatorLegend();

    // Assert
    expect($legend)->toBeArray();
    expect(count($legend))->toBeGreaterThanOrEqual(2);
    expect(array_column($legend, 'name'))->toContain('Op Legend 1', 'Op Legend 2');

    // Act (operator panel filter)
    filament()->setCurrentPanel('operator');
    actingAs($op1);
    $component2 = Livewire::test(InterventionCalendar::class);
    $legend2 = $component2->instance()->getOperatorLegend();

    // Assert
    expect(array_column($legend2, 'name'))->toContain('Op Legend 1');
    expect(array_column($legend2, 'name'))->not->toContain('Op Legend 2');
});

it('keeps calendar creation disabled for the gerardo operator context', function (): void {
    $permission = Permission::findOrCreate('Create:Intervention');
    $role = Role::findOrCreate('operator');
    $user = User::factory()->create([
        'email' => 'info@gsbuildingservice.it',
    ]);
    $user->syncRoles([$role]);
    $user->givePermissionTo($permission);

    filament()->setCurrentPanel('operator');
    actingAs($user);

    /** @var \Livewire\Features\SupportTesting\Testable<InterventionCalendar> $component */
    $component = Livewire::test(InterventionCalendar::class);
    $url = $component->instance()->getCreateInterventionUrl('2026-04-12', true, 'timeGridWeek', '2026-04-07');

    expect($url)->toBe('');
});

it('builds create url from a timed calendar click when operator calendar creation is enabled', function (): void {
    $permission = Permission::findOrCreate('Create:Intervention');
    $role = Role::findOrCreate('admin');
    $user = User::factory()->create([
        'email' => 'enabled-calendar-create@test.local',
    ]);
    $user->syncRoles([$role]);
    $user->givePermissionTo($permission);

    filament()->setCurrentPanel('operator');
    actingAs($user);

    /** @var \Livewire\Features\SupportTesting\Testable<InterventionCalendar> $component */
    $component = Livewire::test(InterventionCalendar::class);
    $url = $component->instance()->getCreateInterventionUrl('2026-04-12T14:30:00', false, 'timeGridDay', '2026-04-12');

    expect($url)->toContain('/operator/interventions/create')
        ->and(urldecode($url))->toContain('scheduled_at=2026-04-12 14:30:00')
        ->and(urldecode($url))->toContain('return_to=http://localhost/operator/intervention-calendar?calendar_view=timeGridDay&calendar_date=2026-04-12');
});

it('normalizes timed calendar clicks to all-day scheduling for Gerardo', function (): void {
    $permission = Permission::findOrCreate('Create:Intervention');
    $role = Role::findOrCreate('admin');
    $user = User::factory()->create([
        'email' => 'info@gsbuildingservice.it',
    ]);
    $user->syncRoles([$role]);
    $user->givePermissionTo($permission);

    // Enable the assigned-perimeter feature for this account so timed clicks
    // are normalized to all-day scheduling, regardless of the production default.
    $accounts = config('account_features.accounts');
    $accounts['info@gsbuildingservice.it']['features']['admin.assigned_perimeter'] = true;
    config(['account_features.accounts' => $accounts]);

    filament()->setCurrentPanel('admin');
    actingAs($user);

    /** @var \Livewire\Features\SupportTesting\Testable<InterventionCalendar> $component */
    $component = Livewire::test(InterventionCalendar::class);
    $url = $component->instance()->getCreateInterventionUrl('2026-04-12T14:30:00', false, 'timeGridDay', '2026-04-12');

    expect($url)->toContain('/admin/interventions/create')
        ->and(urldecode($url))->toContain('scheduled_at=2026-04-12 08:00:00')
        ->and(urldecode($url))->toContain('return_to=http://localhost/admin/intervention-calendar?calendar_view=timeGridDay&calendar_date=2026-04-12');
});

it('adds a visual end time for scheduled interventions without an actual end', function (): void {
    filament()->setCurrentPanel(null);

    $operatorRole = Role::findOrCreate('operator');
    $operator = User::factory()->create(['name' => 'Operatore Pianificato']);
    $operator->syncRoles([$operatorRole]);

    $workOrderId = makeCalendarWorkOrderId('WO-CAL-0005', 'Impianto luci cortile', 'Condominio Pianificato');
    $scheduled = CarbonImmutable::parse('2026-03-17 07:45:00');

    $intervention = Intervention::query()->create([
        'work_order_id' => $workOrderId,
        'performed_by_user_id' => $operator->id,
        'scheduled_at' => $scheduled,
        'started_at' => null,
        'ended_at' => null,
        'work_minutes' => 0,
        'notes' => 'Intervento pianificato non ancora eseguito',
        'outcome_code' => null,
        'amount' => null,
    ]);

    /** @var \Livewire\Features\SupportTesting\Testable<InterventionCalendar> $component */
    $component = Livewire::test(InterventionCalendar::class);
    $events = $component->instance()->getCalendarEvents('2026-03-01', '2026-03-31');

    expect($events)->toHaveCount(1)
        ->and($events[0]['id'])->toBe($intervention->id)
        ->and($events[0]['start'])->toBe($scheduled->toIso8601String())
        ->and($events[0]['end'])->toBe($scheduled->addHour()->toIso8601String());
});

it('stores a calendar-aware view url when opening intervention detail', function (): void {
    filament()->setCurrentPanel('operator');

    $operatorRole = Role::findOrCreate('operator');
    $operator = User::factory()->create(['name' => 'Operatore Return To']);
    $operator->syncRoles([$operatorRole]);

    actingAs($operator);

    $workOrderId = makeCalendarWorkOrderId('WO-CAL-0006', 'Impianto luci scale', 'Condominio Return');
    $scheduled = CarbonImmutable::parse('2026-04-15 11:00:00');

    $intervention = Intervention::query()->create([
        'work_order_id' => $workOrderId,
        'performed_by_user_id' => $operator->id,
        'scheduled_at' => $scheduled,
        'started_at' => $scheduled,
        'ended_at' => $scheduled->addMinutes(30),
        'work_minutes' => 30,
    ]);

    /** @var \Livewire\Features\SupportTesting\Testable<InterventionCalendar> $component */
    $component = Livewire::test(InterventionCalendar::class);

    $component->call('openInterventionDetail', $intervention->id, 'timeGridWeek', '2026-04-13')
        ->assertSet('selectedIntervention.id', $intervention->id);

    $viewUrl = $component->get('selectedIntervention')['view_url'];

    expect($viewUrl)->toContain('/operator/interventions/'.$intervention->id)
        ->and(urldecode($viewUrl))->toContain('return_to=http://localhost/operator/intervention-calendar?calendar_view=timeGridWeek&calendar_date=2026-04-13');
});
