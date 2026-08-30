<?php

declare(strict_types=1);

uses(\Modules\MetalWork\Tests\TestCase::class);

use Modules\Customer\Models\Customer;
use Modules\MetalWork\Actions\CreateQuotationFromMetalPartEstimateAction;
use Modules\MetalWork\Actions\EstimateMetalPartCostAction;
use Modules\MetalWork\Actions\ImportStepDrawingAction;
use Modules\MetalWork\Actions\ImportSupplierInvoiceAction;
use Modules\MetalWork\Actions\StoreMetalPartEstimateAction;
use Modules\MetalWork\Database\Seeders\MetalWorkCenterSeeder;
use Modules\MetalWork\Models\MetalDrawing;
use Modules\MetalWork\Models\MetalRawMaterial;
use Modules\MetalWork\Tests\TestCase;
use Modules\Quotation\Models\Quotation;
use Modules\User\Database\Factories\UserFactory;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

function metalWorksFile(string $name): string
{
    return __DIR__.'/../Fixtures/'.$name;
}

function metalWorksCustomer(): Customer
{
    return Customer::query()->create([
        'legal_name' => 'MECCANICA INDUSTRIALE SNC',
        'is_active' => true,
    ]);
}

it('files a drawing under its number and keeps its measured geometry', function (): void {
    $drawing = app(ImportStepDrawingAction::class)->execute(metalWorksFile('358447106-1_stp1.stp'));

    expect($drawing->part_code)->toBe('358447106-1')
        ->and($drawing->name)->toBe('VASCA SCHIUMATRICE A TENUTA STAGNA')
        ->and((float) $drawing->bbox_length_mm)->toBe(500.0)
        ->and($drawing->content_hash)->not->toBeNull()
        ->and($drawing->geometry()->lengthMm)->toBe(500.0);
});

it('updates the existing row when the same file is imported twice', function (): void {
    $action = app(ImportStepDrawingAction::class);

    $first = $action->execute(metalWorksFile('358447106-1_stp1.stp'));
    $second = $action->execute(metalWorksFile('358447106-1_stp1.stp'));

    expect($second->id)->toBe($first->id)
        ->and(MetalDrawing::query()->count())->toBe(1);
});

it('quotes a real drawing against real invoice prices', function (): void {
    (new MetalWorkCenterSeeder())->run();
    app(ImportSupplierInvoiceAction::class)->execute(metalWorksFile('FAP_SOLSIDERSRLA_0001173.pdf'));

    $drawing = app(ImportStepDrawingAction::class)->execute(metalWorksFile('358447106-1_stp1.stp'));
    $material = MetalRawMaterial::query()->firstWhere('code', 'OSSITAGLIO');
    $customer = metalWorksCustomer();

    $costing = app(EstimateMetalPartCostAction::class)->execute(
        $drawing->geometry(),
        $material,
        ['quantity' => 2, 'occupancy_percent' => 50, 'scrap_percent' => 10],
    );

    $estimate = app(StoreMetalPartEstimateAction::class)->execute(
        $costing,
        $drawing,
        $material,
        ['occupancy_percent' => 50, 'scrap_percent' => 10],
    );

    expect($estimate->lines()->count())->toBe(count($costing->lines))
        ->and((float) $estimate->total_cost)->toBe($costing->totalCost());

    $quotation = app(CreateQuotationFromMetalPartEstimateAction::class)->execute($estimate, $customer);

    expect($quotation)->toBeInstanceOf(Quotation::class)
        ->and($quotation->status_code)->toBe('draft')
        ->and($quotation->customer_id)->toBe($customer->id)
        ->and($quotation->title)->toContain('358447106-1')
        ->and($quotation->items()->count())->toBe(1)
        ->and((float) $quotation->total)->toBeGreaterThan(0.0)
        ->and($estimate->fresh()->quotation_id)->toBe($quotation->id);

    $item = $quotation->items()->first();

    expect((int) $item->quantity)->toBe(2)
        ->and((float) $item->unit_price)->toBe(round($costing->totalSalePrice() / 2, 2));
});

it('writes the breakdown into the quotation notes so the price stays explainable', function (): void {
    (new MetalWorkCenterSeeder())->run();
    app(ImportSupplierInvoiceAction::class)->execute(metalWorksFile('FAP_SOLSIDERSRLA_0001173.pdf'));

    $drawing = app(ImportStepDrawingAction::class)->execute(metalWorksFile('358447106-1_stp1.stp'));
    $material = MetalRawMaterial::query()->firstWhere('code', 'OSSITAGLIO');

    $estimate = app(StoreMetalPartEstimateAction::class)->execute(
        app(EstimateMetalPartCostAction::class)->execute($drawing->geometry(), $material),
        $drawing,
        $material,
    );

    $quotation = app(CreateQuotationFromMetalPartEstimateAction::class)
        ->execute($estimate, metalWorksCustomer());

    expect($quotation->notes)->toContain('358447106-1_stp1.stp')
        ->and($quotation->notes)->toContain('material');
});

it('renders the quotation page of the metalworks panel', function (): void {
    Role::findOrCreate('metalwork_admin');

    $user = UserFactory::new()->createOne();
    $user->syncRoles(['metalwork_admin']);

    actingAs($user)
        ->get('/metalwork/drawing-quotation')
        ->assertOk()
        ->assertSee(__('metalwork::metalwork.quotation.steps.upload'));
});

it('renders the metalworks dashboard', function (): void {
    Role::findOrCreate('metalwork_operator');

    $user = UserFactory::new()->createOne();
    $user->syncRoles(['metalwork_operator']);

    actingAs($user)
        ->get('/metalwork')
        ->assertOk()
        ->assertSee(__('metalwork::metalwork.panel.no_data'));
});

it('keeps users without a metalworks role out of the panel', function (): void {
    Role::findOrCreate('operator');

    $user = UserFactory::new()->createOne();
    $user->syncRoles(['operator']);

    actingAs($user)
        ->get('/metalwork')
        ->assertForbidden();
});
