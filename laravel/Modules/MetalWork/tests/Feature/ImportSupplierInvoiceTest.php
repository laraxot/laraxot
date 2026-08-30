<?php

declare(strict_types=1);

uses(\Modules\MetalWork\Tests\TestCase::class);

use Modules\MetalWork\Actions\ImportSupplierInvoiceAction;
use Modules\MetalWork\Models\MetalRawMaterial;
use Modules\MetalWork\Models\MetalSupplierPrice;
use Modules\MetalWork\Tests\TestCase;

/**
 * Two real invoices, one per supplier: they lay their rows out differently, and
 * between them they cover the article-code, discount and unit-of-measure cases.
 */
function metalWorksInvoice(string $name): string
{
    return __DIR__.'/../Fixtures/'.$name;
}

it('builds the price list from a supplier invoice', function (): void {
    $result = app(ImportSupplierInvoiceAction::class)->execute(
        metalWorksInvoice('FAP_BEATOEDOARDO_2026FV001976.pdf'),
    );

    expect($result['invoice']->supplierName)->toBe('BEATO EDOARDO MAT.FERROSI SRL')
        ->and($result['invoice']->invoiceNumber)->toBe('2026FV001976')
        ->and($result['invoice']->issuedOn)->toBe('2026-03-31')
        ->and($result['materials_created'])->toBe(6);

    $sheet = MetalRawMaterial::query()->firstWhere('code', 'LZP1,5H1500');

    expect($sheet)->not->toBeNull()
        ->and($sheet->family_code)->toBe('sheet')
        ->and($sheet->unit_code)->toBe('kg')
        ->and($sheet->steel_grade)->toBe('DX51')
        ->and($sheet->currentUnitPrice())->toBe(1.17);

    $tube = MetalRawMaterial::query()->firstWhere('code', 'TQ60XX3');

    expect($tube->family_code)->toBe('tube')
        ->and($tube->unit_code)->toBe('ml')
        ->and($tube->currentUnitPrice())->toBe(5.36);
});

it('keeps carriage and collection fees out of the material list', function (): void {
    app(ImportSupplierInvoiceAction::class)->execute(
        metalWorksInvoice('FAP_BEATOEDOARDO_2026FV001976.pdf'),
    );

    // Those rows carry no unit of measure, which is what excludes them.
    expect(MetalRawMaterial::query()->count())->toBe(6);
});

it('applies a row discount to the price it records', function (): void {
    // TQ603 is invoiced at 7,1294 €/ml less 10%.
    app(ImportSupplierInvoiceAction::class)->execute(
        metalWorksInvoice('FAP_SOLSIDERSRLA_0001173.pdf'),
    );

    $tube = MetalRawMaterial::query()->firstWhere('code', 'TQ603');

    expect($tube)->not->toBeNull()
        ->and($tube->currentUnitPrice())->toBe(6.4165);
});

it('reads the article code out of the layout that prints it above the row', function (): void {
    app(ImportSupplierInvoiceAction::class)->execute(
        metalWorksInvoice('FAP_SOLSIDERSRLA_0001173.pdf'),
    );

    $oxyCut = MetalRawMaterial::query()->firstWhere('code', 'OSSITAGLIO');

    expect($oxyCut)->not->toBeNull()
        ->and($oxyCut->family_code)->toBe('oxy_cut')
        ->and($oxyCut->description)->not->toContain('FORN');
});

it('replaces the rows of an invoice imported twice instead of duplicating them', function (): void {
    $action = app(ImportSupplierInvoiceAction::class);
    $path = metalWorksInvoice('FAP_BEATOEDOARDO_2026FV001976.pdf');

    $action->execute($path);
    $afterFirst = MetalSupplierPrice::query()->count();

    $second = $action->execute($path);

    expect(MetalSupplierPrice::query()->count())->toBe($afterFirst)
        ->and($second['materials_created'])->toBe(0);
});

it('records every occurrence of an article an invoice lists more than once', function (): void {
    // Invoice 2026FV000124 bills TR40X30X2N three times at the same price and
    // quantity. Deduplicating on the values would silently drop two of them.
    app(ImportSupplierInvoiceAction::class)->execute(
        metalWorksInvoice('FAP_BEATOEDOARDO_2026FV000124.pdf'),
    );

    $material = MetalRawMaterial::query()->firstWhere('code', 'TR40X30X2N');

    expect($material)->not->toBeNull()
        ->and($material->supplierPrices()->count())->toBe(3);
});

it('refuses a file that is not readable', function (): void {
    expect(fn () => app(ImportSupplierInvoiceAction::class)->execute(metalWorksInvoice('missing.pdf')))
        ->toThrow(RuntimeException::class);
});
