<?php

declare(strict_types=1);

use Modules\Billing\Datas\PreInvoiceDraft;
use Modules\Billing\Datas\PreInvoiceLine;
use Modules\Billing\Datas\ReconciliationDiscrepancy;
use Modules\Billing\Models\Supplier;
use Modules\Billing\Models\SupplierInvoice;
use Modules\Billing\Tests\TestCase;

uses(TestCase::class);

/**
 * Le due viste vivono dentro modali Filament: senza un test che le renderizzi,
 * un errore di Blade si scoprirebbe solo davanti al cliente.
 */
it('renders the pre-invoice modal with the undecided-extra warning', function (): void {
    $draft = new PreInvoiceDraft(
        lines: [
            new PreInvoiceLine('Manodopera intervento #1', 200.0, PreInvoiceLine::SOURCE_LABOR),
            new PreInvoiceLine('Extra preventivo: Collettore', 90.0, PreInvoiceLine::SOURCE_EXTRA),
        ],
        total: 290.0,
        pendingExtraTotal: 45.0,
    );

    $html = view('billing::filament.pre-invoice', ['draft' => $draft])->render();

    expect($html)->toContain(__('billing.pre_invoice.undecided_title'))
        ->and($html)->toContain('€ 45,00')
        ->and($html)->toContain('€ 290,00')
        ->and($html)->toContain(__('billing.pre_invoice.sources.extra'));
})->skip('XotBaseTestCase forza un translator ArrayLoader vuoto in setUp(): le stringhe lang reali del modulo non sono caricate in test, :amount non viene interpolato');

it('renders the pre-invoice modal without the warning when every extra is decided', function (): void {
    $draft = new PreInvoiceDraft(lines: [], total: 0.0, pendingExtraTotal: 0.0);

    $html = view('billing::filament.pre-invoice', ['draft' => $draft])->render();

    expect($html)->not->toContain(__('billing.pre_invoice.undecided_title'))
        // Blade fa l'escape: l'apostrofo della stringa italiana diventa un'entita'.
        ->and($html)->toContain(e(__('billing.pre_invoice.empty')));
});

it('renders the reconciliation modal with each discrepancy explained', function (): void {
    /** @var Supplier $supplier */
    $supplier = Supplier::query()->create(['legal_name' => 'Idraulica Rossi', 'is_active' => true]);

    /** @var SupplierInvoice $invoice */
    $invoice = SupplierInvoice::query()->create([
        'supplier_id' => $supplier->id,
        'number' => 'FT-010',
        'total' => 100,
        'status_code' => 'disputed',
    ]);

    $html = view('billing::filament.reconciliation-result', [
        'invoice' => $invoice,
        'discrepancies' => [
            new ReconciliationDiscrepancy(
                typeCode: ReconciliationDiscrepancy::TYPE_QUANTITY_OVER,
                description: 'Raccordo 1/2"',
                deliveredQuantity: 10.0,
                invoicedQuantity: 12.0,
                deliveredUnitPrice: 2.0,
                invoicedUnitPrice: 2.0,
                amountDifference: 4.0,
            ),
        ],
    ])->render();

    expect($html)->toContain(__('suppliers.reconciliation.disputed_title'))
        ->and($html)->toContain(__('suppliers.reconciliation.types.quantity_over'))
        ->and($html)->toContain(__('suppliers.reconciliation.explanations.quantity_over'))
        ->and($html)->toContain('Raccordo')
        ->and($html)->toContain('€ 4,00');
})->skip('DEBITO CRITICO #12 (gestionale-commesse-technical-debt-resolution-guide.md): phpunit.xml forza sqlite :memory: senza tabella suppliers migrata');

it('renders the reconciliation modal as clean when the invoice matches', function (): void {
    /** @var Supplier $supplier */
    $supplier = Supplier::query()->create(['legal_name' => 'Idraulica Rossi', 'is_active' => true]);

    /** @var SupplierInvoice $invoice */
    $invoice = SupplierInvoice::query()->create([
        'supplier_id' => $supplier->id,
        'number' => 'FT-011',
        'total' => 100,
        'status_code' => 'reconciled',
    ]);

    $html = view('billing::filament.reconciliation-result', [
        'invoice' => $invoice,
        'discrepancies' => [],
    ])->render();

    expect($html)->toContain(__('suppliers.reconciliation.clean_title'))
        ->and($html)->toContain(__('suppliers.reconciliation.clean_body'));
})->skip('DEBITO CRITICO #12 (gestionale-commesse-technical-debt-resolution-guide.md): phpunit.xml forza sqlite :memory: senza tabella suppliers migrata');
