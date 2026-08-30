<?php

declare(strict_types=1);

use Modules\Intervention\Tests\TestCase;

uses(\Modules\Intervention\Tests\TestCase::class);

use Carbon\CarbonImmutable;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Livewire;
use Modules\Catalog\Models\Product;
use Modules\Intervention\Contracts\InterventionReportExtractorContract;
use Modules\Intervention\Filament\Pages\RecordInterventionReportPage;
use Modules\Intervention\Models\Intervention;
use Modules\Signature\Models\Signature;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

const PAGE_TINY_PNG = 'data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=';

function operatorWithUpdatePermission(): User
{
    $role = Role::findOrCreate('operator');
    Permission::findOrCreate('Update:Intervention');

    $operator = UserFactory::new()->createOne(['name' => 'Operatore Voce']);
    $operator->syncRoles([$role]);
    $operator->givePermissionTo('Update:Intervention');

    return $operator;
}

/**
 * @param  array<string, mixed>  $payload
 */
function bindFakeReportExtractor(array $payload): void
{
    app()->bind(InterventionReportExtractorContract::class, fn (): InterventionReportExtractorContract => new class($payload) implements InterventionReportExtractorContract
    {
        /** @param array<string, mixed> $payload */
        public function __construct(private array $payload) {}

        public function extract(string $transcript, array $pastInterventions): array
        {
            return $this->payload;
        }
    });
}

/**
 * @return array{0: User, 1: Intervention}
 */
function makeOperatorWithIntervention(): array
{
    $operator = operatorWithUpdatePermission();

    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Cliente Voce S.r.l.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $intervention = Intervention::query()->create([
        'work_order_id' => null,
        'customer_id' => $customerId,
        'performed_by_user_id' => $operator->id,
        'scheduled_at' => CarbonImmutable::now(),
        'started_at' => CarbonImmutable::now(),
        'work_minutes' => 0,
        'materials_total' => 0,
    ]);

    return [$operator, $intervention];
}

beforeEach(function (): void {
    filament()->setCurrentPanel('intervention::admin');
});

it('generates a draft from the transcript and exposes it for review', function (): void {
    [$operator, $intervention] = makeOperatorWithIntervention();
    Product::query()->create(['name' => 'Valvola', 'unit_price' => 4.0, 'is_active' => true]);

    bindFakeReportExtractor([
        'notes' => 'Sostituita valvola',
        'work_minutes' => 60,
        'outcome_code' => 'completed',
        'materials' => [
            ['description' => 'Valvola', 'quantity' => 2],
            ['description' => 'Raccordo libero', 'quantity' => 1, 'unit_price' => 2.5],
        ],
    ]);

    actingAs($operator);

    Livewire::test(RecordInterventionReportPage::class)
        ->set('interventionId', $intervention->id)
        ->set('transcript', 'ho sostituito la valvola')
        ->call('generateDraft')
        ->assertSet('hasDraft', true)
        ->assertSet('notes', 'Sostituita valvola')
        ->assertSet('workMinutes', 60)
        ->assertSet('outcomeCode', 'completed')
        ->assertSet('materials.0.is_matched', true)
        ->assertSet('materials.0.unit_price', 4.0)
        ->assertSet('materials.1.is_matched', false);
});

it('keeps the transcript as notes when the AI extracts nothing', function (): void {
    [$operator, $intervention] = makeOperatorWithIntervention();
    bindFakeReportExtractor([]); // AI unavailable / returns nothing.
    actingAs($operator);

    Livewire::test(RecordInterventionReportPage::class)
        ->set('interventionId', $intervention->id)
        ->set('transcript', 'ho sistemato la perdita sotto al lavello')
        ->call('generateDraft')
        ->assertSet('hasDraft', true)
        ->assertSet('notes', 'ho sistemato la perdita sotto al lavello')
        ->assertSet('materials', []);
});

it('warns and does nothing when the transcript is empty', function (): void {
    [$operator, $intervention] = makeOperatorWithIntervention();
    actingAs($operator);

    Livewire::test(RecordInterventionReportPage::class)
        ->set('interventionId', $intervention->id)
        ->set('transcript', '   ')
        ->call('generateDraft')
        ->assertSet('hasDraft', false);
});

it('confirms the draft and persists it with photos and signature', function (): void {
    Storage::fake('public');
    [$operator, $intervention] = makeOperatorWithIntervention();
    actingAs($operator);

    Livewire::test(RecordInterventionReportPage::class)
        ->set('interventionId', $intervention->id)
        ->set('hasDraft', true)
        ->set('notes', 'Lavoro concluso')
        ->set('workMinutes', 45)
        ->set('outcomeCode', 'completed')
        ->set('materials', [
            ['description' => 'Guarnizione', 'quantity' => 3.0, 'unit_price' => 2.0, 'matched_product_id' => null, 'is_matched' => false],
            ['description' => '', 'quantity' => 1.0, 'unit_price' => null, 'matched_product_id' => null, 'is_matched' => false],
        ])
        ->set('photos', [UploadedFile::fake()->image('lavoro.jpg')])
        ->set('signature', PAGE_TINY_PNG)
        ->call('confirm')
        ->assertRedirect();

    $intervention->refresh();
    expect($intervention->notes)->toBe('Lavoro concluso')
        ->and((int) $intervention->work_minutes)->toBe(45)
        ->and($intervention->outcome_code)->toBe('completed')
        ->and($intervention->materialLines()->count())->toBe(1)
        ->and((float) $intervention->materials_total)->toBe(6.0)
        ->and($intervention->media()->where('kind', 'photo')->where('meta->scope', 'voice_report')->count())->toBe(1)
        ->and(Signature::query()->where('signable_type', Intervention::class)->where('signable_id', $intervention->id)->count())->toBe(1);
});

it('confirms without photos or signature', function (): void {
    Storage::fake('public');
    [$operator, $intervention] = makeOperatorWithIntervention();
    actingAs($operator);

    Livewire::test(RecordInterventionReportPage::class)
        ->set('interventionId', $intervention->id)
        ->set('hasDraft', true)
        ->set('notes', 'Solo note')
        ->call('confirm')
        ->assertRedirect();

    expect($intervention->refresh()->notes)->toBe('Solo note')
        ->and(Signature::query()->count())->toBe(0);
});

it('lets the operator add and remove material rows', function (): void {
    [$operator, $intervention] = makeOperatorWithIntervention();
    actingAs($operator);

    Livewire::test(RecordInterventionReportPage::class)
        ->set('interventionId', $intervention->id)
        ->call('addMaterialRow')
        ->assertSet('materials.0.quantity', 1.0)
        ->call('removeMaterial', 0)
        ->assertSet('materials', []);
});

it('forbids acting on an intervention the operator does not own', function (): void {
    [, $intervention] = makeOperatorWithIntervention();

    // Stranger has the permission but does not own the intervention.
    $stranger = operatorWithUpdatePermission();
    actingAs($stranger);

    Livewire::test(RecordInterventionReportPage::class)
        ->set('interventionId', $intervention->id)
        ->set('transcript', 'qualcosa')
        ->call('generateDraft')
        ->assertForbidden();
});
