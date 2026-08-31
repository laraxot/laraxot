<?php

declare(strict_types=1);

uses(\Modules\WorkOrder\Tests\TestCase::class);

use Carbon\CarbonImmutable;
use Livewire\Livewire;
use Modules\Customer\Models\Customer;
use Modules\Quotation\Filament\Resources\QuotationResource;
use Modules\Quotation\Filament\Widgets\QuotationsMarginByProjectChart;
use Modules\Quotation\Models\Quotation;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource\Pages\ViewWorkOrder;
use Modules\WorkOrder\Filament\Resources\WorkOrderResource\RelationManagers\MaterialsRelationManager;
use Modules\WorkOrder\Filament\Resources\WorkOrderStatusResource\Pages\ListWorkOrderStatuses;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderStatus;
use Modules\WorkOrder\Tests\TestCase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

use PHPUnit\Framework\Assert;

use function Pest\Laravel\actingAs;

function costingAdmin(): User
{
    $admin = UserFactory::new()->createOne();
    $admin->syncRoles([Role::findOrCreate('admin')]);

    return $admin;
}

function costedQuotation(float $cost, float $taxable, ?string $project = 'MM Berlino'): Quotation
{
    $customer = Customer::query()->create(['legal_name' => 'Cliente Margine S.r.l.', 'is_active' => true]);

    return Quotation::query()->create([
        'customer_id' => $customer->id,
        'status_code' => 'draft',
        'project' => $project,
        'subtotal' => $taxable,
        'taxable_amount' => $taxable,
        'total' => $taxable * 1.22,
        'cost_total' => $cost,
    ]);
}

// ── Margin ───────────────────────────────────────────────────────────────────

it('states the margin in euro and percent', function (): void {
    $quotation = costedQuotation(cost: 600.0, taxable: 1000.0);

    expect(QuotationResource::marginSummary($quotation))->toBe('400,00 € (40,0%)')
        ->and(QuotationResource::marginColour($quotation))->toBe('success');
});

it('flags a thin margin in amber and a losing job in red', function (): void {
    expect(QuotationResource::marginColour(costedQuotation(cost: 900.0, taxable: 1000.0)))->toBe('warning')
        ->and(QuotationResource::marginColour(costedQuotation(cost: 1200.0, taxable: 1000.0)))->toBe('danger');
});

it('says the margin is unknown instead of inventing one when the cost is missing', function (): void {
    $quotation = costedQuotation(cost: 0.0, taxable: 1000.0);

    expect(QuotationResource::marginSummary($quotation))->toBe(__('quotations.margin.unknown'))
        ->and(QuotationResource::marginColour($quotation))->toBe('gray')
        // Same for a quotation that does not exist yet (create form).
        ->and(QuotationResource::marginSummary(null))->toBe(__('quotations.margin.unknown'));
});

// ── Delivery ─────────────────────────────────────────────────────────────────

it('colours the promised delivery by how close (or late) it is', function (): void {
    $customer = Customer::query()->create(['legal_name' => 'Cliente Consegne S.r.l.', 'is_active' => true]);

    $open = WorkOrder::query()->create([
        'customer_id' => $customer->id,
        'title' => 'Commessa aperta',
        'opened_at' => now(),
    ]);

    $overdue = CarbonImmutable::now()->subDay()->toDateTimeString();
    $soon = CarbonImmutable::now()->addDays(3)->toDateTimeString();
    $far = CarbonImmutable::now()->addMonths(2)->toDateTimeString();

    expect(WorkOrderResource::deliveryColour($overdue, $open))->toBe('danger')
        ->and(WorkOrderResource::deliveryColour($soon, $open))->toBe('warning')
        ->and(WorkOrderResource::deliveryColour($far, $open))->toBe('gray')
        ->and(WorkOrderResource::deliveryColour(null, $open))->toBe('gray');

    // A finished job is never late: it is done.
    $open->completed_at = now();

    expect(WorkOrderResource::deliveryColour($overdue, $open))->toBe('gray');
});

// ── Statuses managed from the UI ─────────────────────────────────────────────

it('lets an admin manage the job order statuses from the panel', function (): void {
    actingAs(costingAdmin());
    filament()->setCurrentPanel('admin');

    WorkOrderStatus::query()->create([
        'code' => 'production',
        'name' => 'In produzione',
        'color' => 'amber',
        'is_terminal' => false,
        'sort_order' => 30,
    ]);

    Livewire::test(ListWorkOrderStatuses::class)
        ->assertOk()
        ->assertSee('In produzione')
        ->assertSee('production');
});

// ── Dashboard ────────────────────────────────────────────────────────────────

it('charts revenue and margin by project, ignoring quotations with no cost', function (): void {
    actingAs(costingAdmin());
    filament()->setCurrentPanel('admin');

    costedQuotation(cost: 600.0, taxable: 1000.0, project: 'MM Berlino');
    costedQuotation(cost: 200.0, taxable: 500.0, project: 'Podium');
    // No cost recorded: must not appear as a 100% margin project.
    costedQuotation(cost: 0.0, taxable: 900.0, project: 'Senza costo');

    Livewire::test(QuotationsMarginByProjectChart::class)->assertOk();

    $dataRaw = (new ReflectionMethod(QuotationsMarginByProjectChart::class, 'getData'))
        ->invoke(app(QuotationsMarginByProjectChart::class));
    Assert::assertIsArray($dataRaw);
    Assert::assertArrayHasKey('labels', $dataRaw);
    Assert::assertArrayHasKey('datasets', $dataRaw);
    $datasets = $dataRaw['datasets'];
    Assert::assertIsArray($datasets);
    Assert::assertArrayHasKey(0, $datasets);
    Assert::assertArrayHasKey(1, $datasets);
    Assert::assertIsArray($datasets[0]);
    Assert::assertIsArray($datasets[1]);
    Assert::assertArrayHasKey('data', $datasets[0]);
    Assert::assertArrayHasKey('data', $datasets[1]);

    expect($dataRaw['labels'])->toBe(['MM Berlino', 'Podium'])
        ->and($datasets[0]['data'])->toBe([1220.0, 610.0])
        ->and($datasets[1]['data'])->toBe([620.0, 410.0]);
});

// ── Bill of materials ────────────────────────────────────────────────────────

it('derives the line total when a material is added by hand', function (): void {
    Role::findOrCreate('admin')->givePermissionTo(Permission::findOrCreate('Update:WorkOrder'));

    $admin = UserFactory::new()->createOne();
    $admin->syncRoles(['admin']);
    actingAs($admin);
    filament()->setCurrentPanel('admin');

    $customer = Customer::query()->create(['legal_name' => 'Cliente Distinta S.r.l.', 'is_active' => true]);
    $workOrder = WorkOrder::query()->create([
        'customer_id' => $customer->id,
        'title' => 'Commessa con distinta',
        'opened_at' => now(),
    ]);

    $lw = Livewire::test(MaterialsRelationManager::class, [
        'ownerRecord' => $workOrder,
        'pageClass' => ViewWorkOrder::class,
    ]);
    $lw->assertOk();
    $lw->callTableAction('create', data: [
        'description' => 'Profilo alluminio',
        'unit_code' => 'mt',
        'quantity' => 3,
        'unit_cost' => 7.5,
    ]);

    $material = $workOrder->materials()->firstOrFail();

    expect($material->description)->toBe('Profilo alluminio')
        // 3 × 7,50 = 22,50, calcolato, non digitato
        ->and((float) $material->line_total)->toBe(22.5)
        ->and($material->source_code)->toBe('manual');
});
