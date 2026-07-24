<?php

declare(strict_types=1);

uses(\Modules\AiAssistant\Tests\TestCase::class);

// NOTE: contains Platform deps — individual tests may need skip until Platform replica ready.

use Modules\User\Models\User;
use Modules\AiAssistant\Models\AiActionProposal;
use Modules\AiAssistant\Models\AiThread;
use Modules\Customer\Models\Customer;
use Modules\Intervention\Models\Intervention;
use Modules\Inventory\Models\InventoryMovement;
use Modules\Inventory\Models\Warehouse;
use Modules\Catalog\Models\Product;
use Modules\EnergyBroker\Infrastructure\Models\EnergyBrokerDocument;
use Modules\Platform\Infrastructure\Models\PlatformSetting;
use Modules\Production\Infrastructure\Models\ProductionOrder;
use Modules\Production\Infrastructure\Models\ProductionPhase;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderStatus;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use function Pest\Laravel\actingAs;
use function Pest\Laravel\get;
use function Pest\Laravel\post;

function makeOperatorUser(): User {
    $operatorRole = Role::findOrCreate('operator');
    $operatorRole->givePermissionTo([
        Permission::findOrCreate('Update:Intervention'),
        Permission::findOrCreate('Update:WorkOrder'),
        Permission::findOrCreate('Create:DailyNote'),
        Permission::findOrCreate('Update:DailyNote'),
        Permission::findOrCreate('View:Intervention'),
        Permission::findOrCreate('ViewAny:Intervention'),
        Permission::findOrCreate('View:WorkOrder'),
        Permission::findOrCreate('ViewAny:WorkOrder'),
        Permission::findOrCreate('Create:InventoryMovement'),
        Permission::findOrCreate('ViewAny:InventoryMovement'),
        Permission::findOrCreate('Create:ProductionOrder'),
        Permission::findOrCreate('ViewAny:ProductionOrder'),
        Permission::findOrCreate('Update:ProductionPhase'),
        Permission::findOrCreate('ViewAny:ProductionPhase'),
    ]);

    $user = User::factory()->create();
    $user->syncRoles(['operator']);

    return $user;
}

function makeWorkOrder(): WorkOrder {
    /** @var Customer $customer */
    $customer = Customer::query()->create([
        'legal_name' => 'ACME Srl',
    ]);

    /** @var WorkOrderStatus $status */
    $status = WorkOrderStatus::query()->create([
        'code' => 'in_progress',
        'name' => 'In corso',
        'sort_order' => 1,
    ]);

    /** @var WorkOrder $workOrder */
    $workOrder = WorkOrder::query()->create([
        'customer_id' => $customer->id,
        'status_id' => $status->id,
        'title' => 'Test work order',
    ]);

    return $workOrder;
}

it('does not write domain data without explicit confirm', function (): void {
    $user = makeOperatorUser();
    actingAs($user);

    $workOrder = makeWorkOrder();

    /** @var Intervention $intervention */
    $intervention = Intervention::query()->create([
        'work_order_id' => $workOrder->id,
        'performed_by_user_id' => $user->id,
    ]);

    $thread = AiThread::query()->create([
        'public_id' => (string) str()->uuid(),
        'created_by_user_id' => $user->id,
        'panel_id' => 'operator',
    ]);

    /** @var AiActionProposal $proposal */
    $proposal = AiActionProposal::query()->create([
        'public_id' => (string) str()->uuid(),
        'ai_thread_id' => $thread->id,
        'proposed_by_user_id' => $user->id,
        'type' => 'start_time_punch',
        'payload' => ['intervention_id' => $intervention->id],
        'preview' => 'Avvio timbratura',
        'status' => 'pending',
    ]);

    expect($intervention->timePunches()->count())->toBe(0);

    post("/aiassistant/actions/{$proposal->public_id}/confirm")
        ->assertOk()
        ->assertJsonPath('ok', true);

    expect($intervention->refresh()->timePunches()->count())->toBe(1);
});

it('lists pending proposals for the current thread', function (): void {
    $user = makeOperatorUser();
    actingAs($user);

    $thread = AiThread::query()->create([
        'public_id' => (string) str()->uuid(),
        'created_by_user_id' => $user->id,
        'panel_id' => 'operator',
    ]);

    AiActionProposal::query()->create([
        'public_id' => (string) str()->uuid(),
        'ai_thread_id' => $thread->id,
        'proposed_by_user_id' => $user->id,
        'type' => 'upsert_daily_note',
        'payload' => ['note_date' => '2026-02-25', 'body' => 'nota'],
        'preview' => 'Nota giornaliera',
        'status' => 'pending',
    ]);

    get("/aiassistant/threads/{$thread->public_id}/proposals")
        ->assertOk()
        ->assertJsonPath('ok', true)
        ->assertJsonPath('proposals.0.type_label', 'Nota giornaliera')
        ->assertJsonCount(1, 'proposals');
});

it('blocks confirming someone else proposal', function (): void {
    $owner = makeOperatorUser();
    $other = makeOperatorUser();

    $thread = AiThread::query()->create([
        'public_id' => (string) str()->uuid(),
        'created_by_user_id' => $owner->id,
        'panel_id' => 'operator',
    ]);

    $proposal = AiActionProposal::query()->create([
        'public_id' => (string) str()->uuid(),
        'ai_thread_id' => $thread->id,
        'proposed_by_user_id' => $owner->id,
        'type' => 'upsert_daily_note',
        'payload' => ['note_date' => '2026-02-25', 'body' => 'nota'],
        'preview' => 'Nota giornaliera',
        'status' => 'pending',
    ]);

    actingAs($other)
        ->post("/aiassistant/actions/{$proposal->public_id}/confirm")
        ->assertStatus(403);
});

it('returns SSE headers even without OpenAI key', function (): void {
    $user = makeOperatorUser();
    actingAs($user);

    config(['services.openai.key' => '']);

    get('/aiassistant/chat/stream?message=test')
        ->assertOk()
        ->assertHeader('content-type', 'text/event-stream; charset=utf-8');
});

it('creates inventory movement only after explicit confirm', function (): void {
    $user = makeOperatorUser();
    actingAs($user);

    $warehouse = Warehouse::query()->create(['name' => 'Main']);
    $product = Product::query()->create(['name' => 'Vite M8', 'is_active' => true]);

    $thread = AiThread::query()->create([
        'public_id' => (string) str()->uuid(),
        'created_by_user_id' => $user->id,
        'panel_id' => 'operator',
    ]);

    $proposal = AiActionProposal::query()->create([
        'public_id' => (string) str()->uuid(),
        'ai_thread_id' => $thread->id,
        'proposed_by_user_id' => $user->id,
        'type' => 'create_inventory_movement',
        'payload' => [
            'product_id' => $product->id,
            'movement_type' => 'carico',
            'quantity' => 5,
            'to_warehouse_id' => $warehouse->id,
        ],
        'preview' => 'Carico',
        'status' => 'pending',
    ]);

    expect(InventoryMovement::query()->count())->toBe(0);

    post("/aiassistant/actions/{$proposal->public_id}/confirm")
        ->assertOk()
        ->assertJsonPath('ok', true);

    expect(InventoryMovement::query()->count())->toBe(1);
});

it('blocks allocating to work order when disabled by settings', function (): void {
    $user = makeOperatorUser();
    actingAs($user);

    PlatformSetting::query()->updateOrCreate(
        ['key' => 'inventory.allow_link_work_orders'],
        ['value_json' => ['value' => false], 'updated_by_user_id' => $user->id],
    );

    $warehouse = Warehouse::query()->create(['name' => 'Main']);
    $product = Product::query()->create(['name' => 'Lamiera', 'is_active' => true]);
    $workOrder = makeWorkOrder();

    $thread = AiThread::query()->create([
        'public_id' => (string) str()->uuid(),
        'created_by_user_id' => $user->id,
        'panel_id' => 'operator',
    ]);

    $proposal = AiActionProposal::query()->create([
        'public_id' => (string) str()->uuid(),
        'ai_thread_id' => $thread->id,
        'proposed_by_user_id' => $user->id,
        'type' => 'allocate_inventory_to_work_order',
        'payload' => [
            'product_id' => $product->id,
            'quantity' => 2,
            'from_warehouse_id' => $warehouse->id,
            'work_order_id' => $workOrder->id,
        ],
        'preview' => 'Allocazione',
        'status' => 'pending',
    ]);

    expect(InventoryMovement::query()->count())->toBe(0);

    post("/aiassistant/actions/{$proposal->public_id}/confirm")
        ->assertStatus(422)
        ->assertJsonPath('ok', false);

    expect(InventoryMovement::query()->count())->toBe(0);
});

it('blocks allocating to intervention when stock is insufficient', function (): void {
    $user = makeOperatorUser();
    actingAs($user);

    $warehouse = Warehouse::query()->create(['name' => 'Van Demo']);
    $product = Product::query()->create(['name' => 'Timer scale', 'is_active' => true, 'unit_price' => 24.00]);
    $workOrder = makeWorkOrder();

    /** @var Intervention $intervention */
    $intervention = Intervention::query()->create([
        'work_order_id' => $workOrder->id,
        'performed_by_user_id' => $user->id,
    ]);

    InventoryMovement::query()->create([
        'product_id' => $product->id,
        'movement_type' => 'carico',
        'quantity' => 1,
        'to_warehouse_id' => $warehouse->id,
        'unit_price' => 24.00,
        'line_total' => 24.00,
    ]);

    $thread = AiThread::query()->create([
        'public_id' => (string) str()->uuid(),
        'created_by_user_id' => $user->id,
        'panel_id' => 'operator',
    ]);

    $proposal = AiActionProposal::query()->create([
        'public_id' => (string) str()->uuid(),
        'ai_thread_id' => $thread->id,
        'proposed_by_user_id' => $user->id,
        'type' => 'allocate_inventory_to_intervention',
        'payload' => [
            'product_id' => $product->id,
            'quantity' => 3,
            'from_warehouse_id' => $warehouse->id,
            'intervention_id' => $intervention->id,
            'unit_price' => 24.00,
        ],
        'preview' => 'Scarico materiale su intervento',
        'status' => 'pending',
    ]);

    post("/aiassistant/actions/{$proposal->public_id}/confirm")
        ->assertStatus(422)
        ->assertJsonPath('ok', false);

    expect(InventoryMovement::query()->where('intervention_id', $intervention->id)->count())->toBe(0);
});

it('creates production order only after explicit confirm', function (): void {
    $user = makeOperatorUser();
    actingAs($user);

    $product = Product::query()->create(['name' => 'Assieme A', 'is_active' => true]);

    $thread = AiThread::query()->create([
        'public_id' => (string) str()->uuid(),
        'created_by_user_id' => $user->id,
        'panel_id' => 'operator',
    ]);

    $proposal = AiActionProposal::query()->create([
        'public_id' => (string) str()->uuid(),
        'ai_thread_id' => $thread->id,
        'proposed_by_user_id' => $user->id,
        'type' => 'create_production_order',
        'payload' => [
            'product_id' => $product->id,
            'quantity' => 3,
        ],
        'preview' => 'Crea ordine produzione',
        'status' => 'pending',
    ]);

    expect(ProductionOrder::query()->count())->toBe(0);

    post("/aiassistant/actions/{$proposal->public_id}/confirm")
        ->assertOk()
        ->assertJsonPath('ok', true);

    expect(ProductionOrder::query()->count())->toBe(1);
});

it('advances production phase only after explicit confirm', function (): void {
    $user = makeOperatorUser();
    actingAs($user);

    $product = Product::query()->create(['name' => 'Assieme B', 'is_active' => true]);
    $order = ProductionOrder::query()->create([
        'code' => 'PO-TEST',
        'product_id' => $product->id,
        'quantity' => 1,
        'status' => 'planned',
    ]);
    $phase = ProductionPhase::query()->create([
        'production_order_id' => $order->id,
        'name' => 'Taglio',
        'status' => 'planned',
        'sort_order' => 1,
    ]);

    $thread = AiThread::query()->create([
        'public_id' => (string) str()->uuid(),
        'created_by_user_id' => $user->id,
        'panel_id' => 'operator',
    ]);

    $proposal = AiActionProposal::query()->create([
        'public_id' => (string) str()->uuid(),
        'ai_thread_id' => $thread->id,
        'proposed_by_user_id' => $user->id,
        'type' => 'advance_production_phase',
        'payload' => [
            'production_phase_id' => $phase->id,
            'to_status' => 'in_progress',
        ],
        'preview' => 'Avanza fase',
        'status' => 'pending',
    ]);

    expect($phase->refresh()->status)->toBe('planned');

    post("/aiassistant/actions/{$proposal->public_id}/confirm")
        ->assertOk()
        ->assertJsonPath('ok', true);

    expect($phase->refresh()->status)->toBe('in_progress');
});

it('confirms energy broker document import only after explicit approval', function (): void {
    $adminRole = Role::findOrCreate('admin');
    $adminRole->givePermissionTo([
        Permission::findOrCreate('Update:EnergyBroker'),
        Permission::findOrCreate('ViewAny:EnergyBroker'),
    ]);

    $user = User::factory()->create();
    $user->syncRoles(['admin']);
    actingAs($user);

    /** @var EnergyBrokerDocument $document */
    $document = EnergyBrokerDocument::query()->create([
        'source_file_name' => 'contratto-energy.pdf',
        'document_type' => 'supply_contract',
        'service_type' => 'electricity',
        'supplier_name' => 'PLENITUDE',
        'analysis_status' => 'draft',
        'confidence_score' => 0.97,
        'normalized_payload' => [
            'customer' => [
                'legal_name' => 'SOLDAN PAOLA MARIA',
                'tax_code' => 'SLDPMR62T48H655S',
                'address_line1' => 'VIA SANDRO PERTINI, 10',
                'city' => 'TORREGLIA',
                'postal_code' => '35038',
                'province' => 'PD',
            ],
            'document' => [
                'document_number' => 'CONTR-1',
                'issued_at' => '2024-11-14',
            ],
            'supply' => [
                'service_type' => 'electricity',
                'pod' => 'IT001E31033285',
                'supply_address' => 'VIA SANDRO PERTINI, 10',
            ],
            'contract' => [
                'proposed_supplier' => 'PLENITUDE',
                'offer_name' => 'FIX CASA',
            ],
        ],
    ]);

    $thread = AiThread::query()->create([
        'public_id' => (string) str()->uuid(),
        'created_by_user_id' => $user->id,
        'panel_id' => 'admin',
    ]);

    $proposal = AiActionProposal::query()->create([
        'public_id' => (string) str()->uuid(),
        'ai_thread_id' => $thread->id,
        'proposed_by_user_id' => $user->id,
        'type' => 'confirm_energy_broker_document_import',
        'payload' => ['document_id' => $document->id],
        'preview' => 'Conferma import documento Energy Broker',
        'status' => 'pending',
    ]);

    expect($document->refresh()->analysis_status)->toBe('draft');

    post("/aiassistant/actions/{$proposal->public_id}/confirm")
        ->assertOk()
        ->assertJsonPath('ok', true)
        ->assertJsonPath('result.status', 'imported');

    expect($document->refresh()->analysis_status)->toBe('imported')
        ->and($document->customer_id)->not->toBeNull();
});

