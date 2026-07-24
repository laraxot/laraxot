<?php

declare(strict_types=1);

uses(TestCase::class);

// NOTE: contains Platform deps — individual tests may need skip until Platform replica ready.

use Illuminate\Support\Facades\DB;
use Modules\Intervention\Actions\CreateInterventionAction;
use Modules\Intervention\Filament\Resources\InterventionResource;
use Modules\Intervention\Tests\TestCase;
use Modules\Platform\Infrastructure\Models\PlatformSetting;
use Modules\User\Models\User;

use function Pest\Laravel\actingAs;

it('creates an intervention', function (): void {
    // Arrange
    $user = User::factory()->create();

    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $workOrderId = DB::table('work_orders')->insertGetId([
        'code' => 'WO-INT-0001',
        'customer_id' => $customerId,
        'title' => 'Test work order',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Act
    $intervention = app(CreateInterventionAction::class)->execute([
        'work_order_id' => $workOrderId,
        'performed_by_user_id' => $user->id,
        'started_at' => now()->startOfDay()->addHours(9),
        'ended_at' => now()->startOfDay()->addHours(10),
        'travel_km' => 10,
        'work_minutes' => 60,
        'notes' => 'Test',
    ]);

    // Assert
    expect($intervention->id)->not->toBeNull();
    expect((int) $intervention->work_order_id)->toBe((int) $workOrderId);
});

it('normalizes Gerardo scheduled date-only input', function (): void {
    filament()->setCurrentPanel('admin');

    $user = User::factory()->create([
        'email' => 'info@gsbuildingservice.it',
    ]);

    // Direct-customer work mode forces date-only scheduling to 08:00.
    PlatformSetting::query()->updateOrCreate(
        ['key' => 'interventions.work_mode'],
        ['value_json' => ['value' => 'customer']],
    );

    actingAs($user);

    $data = InterventionResource::normalizeFormData([
        'assigned_user_ids' => [$user->id],
        'scheduled_date' => '2026-04-12',
        'work_hours' => 0,
        'amount' => 0,
        'callout_fee' => 0,
        'manual_material_lines' => [],
        'notes_html' => '<p><strong>Test</strong> <span style="color: #2563eb">blu</span></p>',
    ]);

    expect($data)
        ->toHaveKey('scheduled_at', '2026-04-12 08:00:00')
        ->toHaveKey('notes', 'Test blu')
        ->toHaveKey('notes_html', '<p><strong>Test</strong> <span style="color: #2563eb">blu</span></p>')
        ->not->toHaveKey('scheduled_date');
});

it('strips non database form fields before persistence', function (): void {
    $data = InterventionResource::extractPersistableData([
        'work_order_id' => 10,
        'performed_by_user_id' => 5,
        'scheduled_at' => '2026-04-12 08:00:00',
        'notes' => 'test',
        'notes_html' => '<p>test</p>',
        'work_minutes' => 120,
        'amount' => 10,
        'callout_fee' => 5,
        'materials_total' => 7,
        'total_amount' => 22,
        'assigned_user_ids' => [5, 6],
        'notes_photo_paths' => ['media/interventions/1/note-photos/test.jpg'],
        'work_hours' => 2,
        'manual_material_lines' => [['description' => 'Lampada']],
        'scheduled_date' => '2026-04-12',
        'allDay' => true,
    ]);

    expect($data)
        ->toHaveKey('work_order_id', 10)
        ->toHaveKey('performed_by_user_id', 5)
        ->toHaveKey('scheduled_at', '2026-04-12 08:00:00')
        ->toHaveKey('notes_html', '<p>test</p>')
        ->not->toHaveKey('assigned_user_ids')
        ->not->toHaveKey('notes_photo_paths')
        ->not->toHaveKey('work_hours')
        ->not->toHaveKey('manual_material_lines')
        ->not->toHaveKey('scheduled_date')
        ->not->toHaveKey('allDay');
});
