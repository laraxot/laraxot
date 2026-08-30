<?php

declare(strict_types=1);

use Modules\Billing\Actions\ReconcileSupplierInvoiceWithDeliveryNotesAction;
use Modules\Billing\Datas\ReconciliationDiscrepancy;
use Modules\Billing\Models\DeliveryNote;
use Modules\Billing\Models\DeliveryNoteLine;
use Modules\Billing\Models\Supplier;
use Modules\Billing\Models\SupplierInvoice;
use Modules\Billing\Models\SupplierInvoiceLine;
use Modules\Billing\Tests\TestCase;

uses(\Modules\Billing\Tests\TestCase::class);

function billingSupplierWithDelivery(string $description, float $quantity, float $unitPrice): Supplier
{
    /** @var Supplier $supplier */
    $supplier = Supplier::query()->create(['legal_name' => 'Idraulica Forniture Srl']);

    /** @var DeliveryNote $note */
    $note = DeliveryNote::query()->create([
        'supplier_id' => $supplier->getKey(),
        'number' => 'DDT-001',
        'received_on' => now()->toDateString(),
    ]);

    DeliveryNoteLine::query()->create([
        'delivery_note_id' => $note->getKey(),
        'description' => $description,
        'quantity' => $quantity,
        'unit_price' => $unitPrice,
    ]);

    return $supplier;
}

function billingInvoiceFor(Supplier $supplier, string $description, float $quantity, float $unitPrice): SupplierInvoice
{
    /** @var SupplierInvoice $invoice */
    $invoice = SupplierInvoice::query()->create([
        'supplier_id' => $supplier->getKey(),
        'number' => 'FT-100',
        'issued_on' => now()->toDateString(),
        'total' => $quantity * $unitPrice,
    ]);

    SupplierInvoiceLine::query()->create([
        'supplier_invoice_id' => $invoice->getKey(),
        'description' => $description,
        'quantity' => $quantity,
        'unit_price' => $unitPrice,
    ]);

    return $invoice;
}

it('marks the invoice as reconciled when everything matches', function (): void {
    $supplier = billingSupplierWithDelivery('Tubo rame', 20, 4.5);
    $invoice = billingInvoiceFor($supplier, 'Tubo rame', 20, 4.5);

    $discrepancies = app(ReconcileSupplierInvoiceWithDeliveryNotesAction::class)->execute($invoice);

    expect($discrepancies)->toBe([])
        ->and($invoice->refresh()->status_code)->toBe('reconciled');
});

it('disputes an invoice charging more than delivered', function (): void {
    $supplier = billingSupplierWithDelivery('Raccordo', 10, 3.0);
    $invoice = billingInvoiceFor($supplier, 'Raccordo', 14, 3.0);

    $discrepancies = app(ReconcileSupplierInvoiceWithDeliveryNotesAction::class)->execute($invoice);

    expect($discrepancies)->toHaveCount(1)
        ->and($discrepancies[0]->typeCode)->toBe(ReconciliationDiscrepancy::TYPE_QUANTITY_OVER)
        ->and($discrepancies[0]->amountDifference)->toBe(12.0)
        ->and($invoice->refresh()->status_code)->toBe('disputed');
});

it('disputes a price different from the delivery note', function (): void {
    $supplier = billingSupplierWithDelivery('Valvola', 5, 10.0);
    $invoice = billingInvoiceFor($supplier, 'Valvola', 5, 13.0);

    $discrepancies = app(ReconcileSupplierInvoiceWithDeliveryNotesAction::class)->execute($invoice);

    expect($discrepancies[0]->typeCode)->toBe(ReconciliationDiscrepancy::TYPE_PRICE_MISMATCH)
        ->and($discrepancies[0]->amountDifference)->toBe(15.0)
        ->and($invoice->refresh()->status_code)->toBe('disputed');
});

it('stays reconciled when some delivered material is not invoiced yet', function (): void {
    $supplier = billingSupplierWithDelivery('Collettore', 10, 25.0);
    $invoice = billingInvoiceFor($supplier, 'Collettore', 4, 25.0);

    $discrepancies = app(ReconcileSupplierInvoiceWithDeliveryNotesAction::class)->execute($invoice);

    expect($discrepancies)->toHaveCount(1)
        ->and($discrepancies[0]->typeCode)->toBe(ReconciliationDiscrepancy::TYPE_NOT_INVOICED)
        ->and($invoice->refresh()->status_code)->toBe('reconciled');
});

it('does not compare delivery notes of other suppliers', function (): void {
    $supplier = billingSupplierWithDelivery('Pompa', 2, 100.0);

    /** @var Supplier $otherSupplier */
    $otherSupplier = Supplier::query()->create(['legal_name' => 'Altro Fornitore']);

    /** @var DeliveryNote $otherNote */
    $otherNote = DeliveryNote::query()->create([
        'supplier_id' => $otherSupplier->getKey(),
        'number' => 'DDT-999',
    ]);

    DeliveryNoteLine::query()->create([
        'delivery_note_id' => $otherNote->getKey(),
        'description' => 'Pompa',
        'quantity' => 50,
        'unit_price' => 100.0,
    ]);

    $invoice = billingInvoiceFor($supplier, 'Pompa', 5, 100.0);

    $discrepancies = app(ReconcileSupplierInvoiceWithDeliveryNotesAction::class)->execute($invoice);

    // Le 50 pompe dell'altro fornitore non devono coprire questa fattura.
    expect($discrepancies)->toHaveCount(1)
        ->and($discrepancies[0]->typeCode)->toBe(ReconciliationDiscrepancy::TYPE_QUANTITY_OVER)
        ->and($invoice->refresh()->status_code)->toBe('disputed');
});

it('disputes an invoice with no delivery note at all', function (): void {
    /** @var Supplier $supplier */
    $supplier = Supplier::query()->create(['legal_name' => 'Fornitore Senza DDT']);
    $invoice = billingInvoiceFor($supplier, 'Caldaia', 1, 900.0);

    $discrepancies = app(ReconcileSupplierInvoiceWithDeliveryNotesAction::class)->execute($invoice);

    expect($discrepancies[0]->typeCode)->toBe(ReconciliationDiscrepancy::TYPE_NOT_DELIVERED)
        ->and($invoice->refresh()->status_code)->toBe('disputed');
});
