<?php

declare(strict_types=1);

uses(\Modules\Signage\Tests\TestCase::class);

use Modules\Quotation\Models\Quotation;
use Modules\Signage\Database\Seeders\SignageDemoSeeder;
use Modules\Signage\Models\SignPriceListItem;
use Modules\Signage\Tests\TestCase;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderMaterial;
use Modules\WorkOrder\Models\WorkOrderStatus;

it('fills the sign-shop demo with quotations, job orders and their materials', function (): void {
    (new SignageDemoSeeder())->run();

    // Price list and rules come along, so a fresh install is demoable.
    expect(SignPriceListItem::query()->count())->toBe(11);

    // The workflow the shop actually uses.
    expect(WorkOrderStatus::query()->pluck('name', 'code')->all())
        ->toMatchArray([
            'quoting' => 'Preventivazione',
            'production' => 'In produzione',
            'shipping' => 'In spedizione',
            'closed' => 'Chiusa',
        ]);

    $quotations = Quotation::query()->orderBy('code')->get();

    expect($quotations)->toHaveCount(3)
        ->and($quotations->pluck('project')->all())->toBe(['MaxMara', 'Max&Co', 'Marina Rinaldi']);

    // Quotation 1: brass sheet 2,88 mq at the real supplier cost of 116,45 €/mq
    // plus milling, LED and labour, sold at a 65% markup.
    $berlin = $quotations->firstWhere('code', 'PRE-INS-2026-001');

    expect($berlin)->not->toBeNull()
        ->and($berlin->items()->count())->toBe(5)
        ->and(round((float) $berlin->cost_total, 2))->toBe(591.61)
        ->and((float) $berlin->subtotal)->toBeGreaterThan((float) $berlin->cost_total);

    $sheet = $berlin->items()->orderBy('sort_order')->first();

    expect($sheet->description)->toContain('Lastra ottone OT63')
        // 116,45 × 1,65 = 192,14
        ->and((float) $sheet->unit_price)->toBe(192.14);

    // Quotation 3 was quoted below cost on purpose: it is the red-margin case.
    $duomo = $quotations->firstWhere('code', 'PRE-INS-2026-003');

    expect((float) $duomo->subtotal)->toBeLessThan((float) $duomo->cost_total);

    // Two of the three became job orders, each with the quoted lines as its
    // bill of materials.
    $workOrders = WorkOrder::query()->get();

    expect($workOrders)->toHaveCount(2)
        ->and(WorkOrderMaterial::query()->where('source_code', 'quotation')->count())->toBe(10);

    $inProduction = WorkOrder::query()->where('title', $berlin->title)->firstOrFail();

    expect($inProduction->progress_percent)->toBe(60)
        ->and($inProduction->delivery_due_at)->not->toBeNull()
        ->and($inProduction->delivery_due_at->isFuture())->toBeTrue()
        ->and($inProduction->materials()->count())->toBe(5);

    // The shipping one is deliberately late, so the overdue colour shows up.
    $late = WorkOrder::query()->where('title', $duomo->title)->firstOrFail();

    expect($late->delivery_due_at?->isPast())->toBeTrue()
        ->and($late->progress_percent)->toBe(90);
});

it('can be run twice without duplicating anything', function (): void {
    (new SignageDemoSeeder())->run();
    (new SignageDemoSeeder())->run();

    expect(Quotation::query()->count())->toBe(3)
        ->and(WorkOrder::query()->count())->toBe(2)
        ->and(WorkOrderMaterial::query()->count())->toBe(10)
        ->and(SignPriceListItem::query()->count())->toBe(11)
        ->and(WorkOrderStatus::query()->count())->toBe(5);
});
