<?php

declare(strict_types=1);

use Modules\WorkOrder\Tests\TestCase as WorkOrderTestCase;

uses(WorkOrderTestCase::class);

use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use Modules\Catalog\Models\Product;
use Modules\Customer\Models\Customer;
use Modules\Inventory\Models\Warehouse;
use Modules\Quotation\Models\Quotation;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;
use Modules\WorkOrder\Actions\BuildPeriodicSummaryAction;
use Modules\WorkOrder\Actions\GeneratePeriodicSummaryPdfAction;
use Modules\WorkOrder\Filament\Pages\PeriodicSummaryPage;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderStatus;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

// ── Helpers ──────────────────────────────────────────────────────────────────

/**
 * Modules\User\Models\BaseUser (HasXotFactory) lacks a newFactory() override, so
 * Larastan's dynamic return type extension for the static User::factory() proxy
 * cannot resolve the concrete factory class and treats it as mixed. Calling
 * Modules\User\Database\Factories\UserFactory directly (same class
 * GetFactoryAction would have resolved at runtime) sidesteps that, but the base
 * Factory::create() stub still types its return as TModel|Collection<int, TModel>
 * because count() might have been called; this helper narrows that back down to
 * a single User, which is what actually happens at runtime here. Fix kept
 * entirely inside Modules/WorkOrder. See
 * docs/chat/2026-09-06-phpstan-workorder-claim.md for the cross-module note.
 */
function createSummaryUser(): User
{
    $user = UserFactory::new()->create();
    if (! $user instanceof User) {
        throw new RuntimeException('Expected UserFactory::new()->create() to return a single User instance.');
    }

    return $user;
}

function makeSummaryAdmin(): User
{
    $admin = createSummaryUser();
    $admin->syncRoles([Role::findOrCreate('admin')]);

    return $admin;
}

function makeSummaryCustomer(string $name): Customer
{
    $customer = Customer::query()->create([
        'legal_name' => $name,
        'is_active' => true,
    ]);
    if (! $customer instanceof Customer) {
        throw new RuntimeException('Customer creation failed');
    }

    return $customer;
}

function makeSummaryWorkOrder(Customer $customer, string $code, string $title = 'Commessa resoconto'): WorkOrder
{
    $status = WorkOrderStatus::query()->firstOrCreate(
        ['code' => 'in_progress'],
        ['name' => 'In lavorazione', 'color' => 'amber', 'is_terminal' => 0, 'sort_order' => 30, 'created_at' => now(), 'updated_at' => now()],
    );

    return WorkOrder::query()->create([
        'code' => $code,
        'customer_id' => $customer->id,
        'status_id' => $status->id,
        'title' => $title,
        'opened_at' => now(),
    ]);
}

function addSummaryIntervention(
    CarbonImmutable $scheduledAt,
    ?int $workOrderId = null,
    ?int $customerId = null,
    int $workMinutes = 90,
    float $amount = 100.0,
    float $calloutFee = 20.0,
    float $totalAmount = 0.0,
    string $outcomeCode = 'completed',
): int {
    return (int) DB::table('interventions')->insertGetId([
        'work_order_id' => $workOrderId,
        'customer_id' => $customerId,
        'performed_by_user_id' => createSummaryUser()->id,
        'scheduled_at' => $scheduledAt,
        'work_minutes' => $workMinutes,
        'outcome_code' => $outcomeCode,
        'amount' => $amount,
        'callout_fee' => $calloutFee,
        'total_amount' => $totalAmount,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

function addSummaryManualLine(int $interventionId, string $description, float $quantity, float $unitPrice): void
{
    DB::table('intervention_material_lines')->insert([
        'intervention_id' => $interventionId,
        'description' => $description,
        'quantity' => $quantity,
        'unit_price' => $unitPrice,
        'line_total' => $quantity * $unitPrice,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

function addSummaryWarehouseMaterial(int $interventionId, string $productName, float $quantity, float $unitPrice): void
{
    $product = Product::query()->create([
        'name' => $productName,
        'is_active' => true,
        'unit_price' => $unitPrice,
    ]);

    $warehouse = Warehouse::query()->create([
        'name' => 'Furgone resoconto '.$productName,
        'is_active' => true,
        'is_final_destination' => false,
    ]);

    DB::table('inventory_movements')->insert([
        'product_id' => $product->id,
        'from_warehouse_id' => $warehouse->id,
        'movement_type' => 'scarico',
        'quantity' => $quantity,
        'unit_price' => $unitPrice,
        'line_total' => $quantity * $unitPrice,
        'intervention_id' => $interventionId,
        'occurred_at' => now(),
        'origin_auto' => false,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
}

// ── Aggregation ──────────────────────────────────────────────────────────────

it('aggregates customers, work orders, materials and quotations for the period', function (): void {
    $from = CarbonImmutable::now()->startOfMonth();
    $to = CarbonImmutable::now()->endOfMonth();

    $bigCustomer = makeSummaryCustomer('Cliente Grande S.r.l.');
    $smallCustomer = makeSummaryCustomer('Cliente Piccolo S.n.c.');
    $workOrder = makeSummaryWorkOrder($bigCustomer, 'WO-SUMMARY-1');

    // Big customer: two interventions on the same work order, one with materials.
    $withMaterials = addSummaryIntervention(
        $from->addDays(2)->setTime(9, 0),
        workOrderId: $workOrder->id,
        workMinutes: 120,
        amount: 200.0,
        calloutFee: 30.0,
    );
    addSummaryWarehouseMaterial($withMaterials, 'Cavo elettrico', 3.0, 10.0);
    addSummaryManualLine($withMaterials, 'Nastro isolante', 2.0, 2.50);

    addSummaryIntervention(
        $from->addDays(3)->setTime(9, 0),
        workOrderId: $workOrder->id,
        workMinutes: 60,
        amount: 100.0,
        calloutFee: 0.0,
        outcomeCode: 'suspended',
    );

    // Small customer: intervention without work order.
    addSummaryIntervention(
        $from->addDays(4)->setTime(9, 0),
        customerId: $smallCustomer->id,
        workMinutes: 30,
        amount: 50.0,
        calloutFee: 0.0,
    );

    // Outside the period: must be ignored.
    addSummaryIntervention(
        $from->subMonth()->setTime(9, 0),
        customerId: $smallCustomer->id,
        amount: 9999.0,
    );

    Quotation::query()->create([
        'customer_id' => $bigCustomer->id,
        'status_code' => 'confirmed',
        'total' => 1000.0,
        'issued_at' => $from->addDay(),
    ]);
    Quotation::query()->create([
        'customer_id' => $smallCustomer->id,
        'status_code' => 'draft',
        'total' => 400.0,
        'issued_at' => null,
    ]);
    Quotation::query()->create([
        'customer_id' => $smallCustomer->id,
        'status_code' => 'sent',
        'total' => 7777.0,
        'issued_at' => $from->subMonth(),
    ]);

    $summary = app(BuildPeriodicSummaryAction::class)->execute($from, $to);

    // Totals: 3 interventions, 120 + 60 + 30 minutes.
    expect($summary['totals']['interventions'])->toBe(3)
        ->and($summary['totals']['minutes'])->toBe(210)
        ->and($summary['totals']['customers'])->toBe(2)
        ->and($summary['totals']['work_orders'])->toBe(1)
        ->and($summary['totals']['labor'])->toBe(350.0)
        ->and($summary['totals']['callout'])->toBe(30.0)
        // Materials: warehouse 30,00 vs manual 5,00 → the largest source wins.
        ->and($summary['totals']['materials'])->toBe(30.0)
        ->and($summary['totals']['total'])->toBe(410.0);

    // Customers are sorted by turnover, largest first.
    expect($summary['customers'])->toHaveCount(2)
        ->and($summary['customers'][0]['name'])->toBe('Cliente Grande S.r.l.')
        ->and($summary['customers'][0]['interventions'])->toBe(2)
        ->and($summary['customers'][0]['minutes'])->toBe(180)
        ->and($summary['customers'][0]['total'])->toBe(360.0)
        ->and($summary['customers'][1]['name'])->toBe('Cliente Piccolo S.n.c.')
        ->and($summary['customers'][1]['total'])->toBe(50.0);

    // Only the work order carrying interventions shows up.
    expect($summary['work_orders'])->toHaveCount(1)
        ->and($summary['work_orders'][0]['code'])->toBe('WO-SUMMARY-1')
        ->and($summary['work_orders'][0]['customer'])->toBe('Cliente Grande S.r.l.')
        ->and($summary['work_orders'][0]['status'])->toBe('In lavorazione')
        ->and($summary['work_orders'][0]['interventions'])->toBe(2)
        ->and($summary['work_orders'][0]['minutes'])->toBe(180);

    // Outcomes counted by code.
    expect($summary['outcomes'])->toBe(['completed' => 2, 'suspended' => 1]);

    // Materials keep warehouse and manual lines apart, sorted by value.
    expect($summary['materials'])->toHaveCount(2)
        ->and($summary['materials'][0])->toBe([
            'label' => 'Cavo elettrico',
            'source' => 'warehouse',
            'quantity' => 3.0,
            'amount' => 30.0,
        ])
        ->and($summary['materials'][1])->toBe([
            'label' => 'Nastro isolante',
            'source' => 'manual',
            'quantity' => 2.0,
            'amount' => 5.0,
        ])
        ->and($summary['materials_total'])->toBe(35.0);

    // Quotations: issued in the period + never-issued drafts created in it.
    expect($summary['quotations']['count'])->toBe(2)
        ->and($summary['quotations']['total'])->toBe(1400.0)
        ->and($summary['quotations']['rows'])->toBe([
            ['status' => 'confirmed', 'count' => 1, 'total' => 1000.0],
            ['status' => 'draft', 'count' => 1, 'total' => 400.0],
        ]);
});

it('counts work orders opened and closed inside the period', function (): void {
    $from = CarbonImmutable::now()->startOfMonth();
    $to = CarbonImmutable::now()->endOfMonth();
    $customer = makeSummaryCustomer('Cliente Conteggi S.r.l.');

    $closed = makeSummaryWorkOrder($customer, 'WO-CLOSED');
    $closed->update(['closed_at' => $from->addDays(5)]);

    $oldOne = makeSummaryWorkOrder($customer, 'WO-OLD');
    $oldOne->update(['opened_at' => $from->subMonths(2), 'closed_at' => $from->subMonths(2)]);

    $summary = app(BuildPeriodicSummaryAction::class)->execute($from, $to);

    expect($summary['totals']['work_orders_opened'])->toBe(1)
        ->and($summary['totals']['work_orders_closed'])->toBe(1);
});

it('uses the stored grand total when the intervention carries one', function (): void {
    $from = CarbonImmutable::now()->startOfMonth();
    $to = CarbonImmutable::now()->endOfMonth();
    $customer = makeSummaryCustomer('Cliente Totale Fisso S.r.l.');

    addSummaryIntervention(
        $from->addDay()->setTime(8, 0),
        customerId: $customer->id,
        amount: 100.0,
        calloutFee: 10.0,
        totalAmount: 500.0,
    );

    $summary = app(BuildPeriodicSummaryAction::class)->execute($from, $to);

    expect($summary['totals']['total'])->toBe(500.0)
        ->and($summary['customers'][0]['total'])->toBe(500.0);
});

it('groups interventions without customer and without outcome under their fallbacks', function (): void {
    $from = CarbonImmutable::now()->startOfMonth();
    $to = CarbonImmutable::now()->endOfMonth();

    addSummaryIntervention($from->addDay()->setTime(8, 0), outcomeCode: '');

    $summary = app(BuildPeriodicSummaryAction::class)->execute($from, $to);

    expect($summary['customers'])->toHaveCount(1)
        ->and($summary['customers'][0]['name'])->toBe('')
        ->and($summary['outcomes'])->toBe(['unknown' => 1]);
});

it('returns empty sections when nothing happened in the period', function (): void {
    $from = CarbonImmutable::now()->addYears(5)->startOfMonth();
    $to = $from->endOfMonth();

    $summary = app(BuildPeriodicSummaryAction::class)->execute($from, $to);

    expect($summary['totals']['interventions'])->toBe(0)
        ->and($summary['totals']['total'])->toBe(0.0)
        ->and($summary['customers'])->toBe([])
        ->and($summary['work_orders'])->toBe([])
        ->and($summary['materials'])->toBe([])
        ->and($summary['materials_total'])->toBe(0.0)
        ->and($summary['quotations']['rows'])->toBe([]);
});

// ── PDF ──────────────────────────────────────────────────────────────────────

it('renders the periodic summary as a landscape PDF', function (): void {
    $from = CarbonImmutable::now()->startOfMonth();
    $to = CarbonImmutable::now()->endOfMonth();

    $customer = makeSummaryCustomer('Cliente PDF S.r.l.');
    $workOrder = makeSummaryWorkOrder($customer, 'WO-SUMMARY-PDF');
    $interventionId = addSummaryIntervention($from->addDay()->setTime(9, 0), workOrderId: $workOrder->id);
    addSummaryWarehouseMaterial($interventionId, 'Tubo rame', 5.0, 8.0);
    addSummaryManualLine($interventionId, 'Raccordo', 1.0, 4.0);
    Quotation::query()->create([
        'customer_id' => $customer->id,
        'status_code' => 'sent',
        'total' => 250.0,
        'issued_at' => $from->addDay(),
    ]);

    $result = app(GeneratePeriodicSummaryPdfAction::class)->execute($from, $to);

    expect(substr($result['content'], 0, 4))->toBe('%PDF')
        ->and($result['mime'])->toBe('application/pdf')
        ->and($result['filename'])->toBe(sprintf('resoconto_periodico_%s_%s.pdf', $from->format('Ymd'), $to->format('Ymd')));
});

it('renders the PDF with no data in the period', function (): void {
    $from = CarbonImmutable::now()->addYears(6)->startOfMonth();

    $result = app(GeneratePeriodicSummaryPdfAction::class)->execute($from, $from->endOfMonth());

    expect(substr($result['content'], 0, 4))->toBe('%PDF');
});

// ── Filament page ────────────────────────────────────────────────────────────

it('shows the periodic summary page and downloads its PDF', function (): void {
    $admin = makeSummaryAdmin();

    $customer = makeSummaryCustomer('Cliente Pagina S.r.l.');
    $workOrder = makeSummaryWorkOrder($customer, 'WO-SUMMARY-PAGE');
    addSummaryIntervention(CarbonImmutable::now()->startOfMonth()->addDay()->setTime(9, 0), workOrderId: $workOrder->id);

    actingAs($admin);
    filament()->setCurrentPanel('admin');

    $page = Livewire::test(PeriodicSummaryPage::class);
    $page->assertOk();
    $page->assertSee('Cliente Pagina S.r.l.');
    $page->assertSee('WO-SUMMARY-PAGE');
    $page->call('applyPreset', 'last_month');
    $page->assertSet('from', CarbonImmutable::now()->subMonth()->startOfMonth()->toDateString());
    $page->call('applyPreset', 'this_year');
    $page->assertSet('from', CarbonImmutable::now()->startOfYear()->toDateString());
    $page->call('applyPreset', 'this_quarter');
    $page->assertSet('from', CarbonImmutable::now()->startOfQuarter()->toDateString());
    $page->call('applyPreset', 'this_month');
    $page->assertSet('from', CarbonImmutable::now()->startOfMonth()->toDateString());
    $page->call('downloadPdf');
    $page->assertFileDownloaded(sprintf(
        'resoconto_periodico_%s_%s.pdf',
        CarbonImmutable::now()->startOfMonth()->format('Ymd'),
        CarbonImmutable::now()->endOfMonth()->format('Ymd'),
    ));
});

it('falls back to the current month when the typed dates are unusable', function (): void {
    $admin = makeSummaryAdmin();

    actingAs($admin);
    filament()->setCurrentPanel('admin');

    $page = Livewire::test(PeriodicSummaryPage::class);
    $page->set('from', '');
    $page->set('to', 'non-una-data');
    $page->assertOk();
    $page->call('downloadPdf');
    $page->assertFileDownloaded(sprintf(
        'resoconto_periodico_%s_%s.pdf',
        CarbonImmutable::now()->startOfMonth()->format('Ymd'),
        CarbonImmutable::now()->endOfMonth()->format('Ymd'),
    ));
});
