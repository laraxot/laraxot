<?php

declare(strict_types=1);

uses(\Modules\Signature\Tests\TestCase::class);

use Modules\User\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Modules\Intervention\Models\Intervention;
use Modules\Signature\Filament\Pages\CaptureSignature;
use Modules\Signature\Filament\Pages\CaptureSketch;
use Spatie\Permission\Models\Role;
use function Pest\Laravel\actingAs;

function createInterventionForSignaturePages(User $user): Intervention
{
    $customerId = (int) DB::table('customers')->insertGetId([
        'legal_name' => 'Cliente Demo Firme S.r.l.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $workOrderId = (int) DB::table('work_orders')->insertGetId([
        'code' => 'WO-SIGN-0001',
        'customer_id' => $customerId,
        'title' => 'Intervento con firma',
        'description' => null,
        'notes' => null,
        'opened_at' => now(),
        'scheduled_at' => null,
        'started_at' => null,
        'completed_at' => null,
        'closed_at' => null,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    return Intervention::query()->create([
        'work_order_id' => $workOrderId,
        'performed_by_user_id' => $user->id,
        'scheduled_at' => now()->addHour(),
        'work_minutes' => 60,
        'notes' => 'Intervento demo per pagina firma/disegno',
    ]);
}

it('renders the signature capture page in admin panel', function (): void {
    Role::findOrCreate('admin');

    $user = User::factory()->create();
    $user->syncRoles(['admin']);

    $intervention = createInterventionForSignaturePages($user);

    actingAs($user)
        ->get('/admin/interventions/' . $intervention->id . '/capture-signature')
        ->assertOk()
        ->assertSee('Firma')
        ->assertSee('Utente')
        ->assertSee('Nominativo firmatario')
        ->assertSee('Cancella')
        ->assertDontSee('InvalidArgumentException')
        ->assertDontSee('No hint path defined for [signatures]')
        ->assertDontSee('Capture signature')
        ->assertDontSee('Clear');
});

it('renders the sketch capture page in admin panel', function (): void {
    Role::findOrCreate('admin');

    $user = User::factory()->create();
    $user->syncRoles(['admin']);

    $intervention = createInterventionForSignaturePages($user);

    actingAs($user)
        ->get('/admin/interventions/' . $intervention->id . '/capture-sketch')
        ->assertOk()
        ->assertSee('Disegno')
        ->assertSee('Utente')
        ->assertSee('Cancella')
        ->assertDontSee('InvalidArgumentException')
        ->assertDontSee('No hint path defined for [signatures]')
        ->assertDontSee('Capture sketch')
        ->assertDontSee('Clear');
});

it('initializes signature page state with the intervention id', function (): void {
    $user = User::factory()->create();
    $intervention = createInterventionForSignaturePages($user);

    Livewire::test(CaptureSignature::class, ['interventionId' => $intervention->id])
        ->assertSet('data.intervention_id', $intervention->id)
        ->assertSet('data.user_id', null);
});

it('initializes sketch page state with the intervention id', function (): void {
    $user = User::factory()->create();
    $intervention = createInterventionForSignaturePages($user);

    Livewire::test(CaptureSketch::class, ['interventionId' => $intervention->id])
        ->assertSet('data.intervention_id', $intervention->id)
        ->assertSet('data.user_id', null);
});
