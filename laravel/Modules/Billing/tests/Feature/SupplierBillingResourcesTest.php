<?php

declare(strict_types=1);

uses(TestCase::class);

use Modules\Billing\Domain\DTO\ReconciliationDiscrepancy;
use Modules\Billing\Filament\Resources\DeliveryNoteResource;
use Modules\Billing\Filament\Resources\SupplierInvoiceResource;
use Modules\Billing\Filament\Resources\SupplierResource;
use Modules\Billing\Models\DeliveryNote;
use Modules\Billing\Models\Supplier;
use Modules\Billing\Models\SupplierInvoice;
use Modules\Billing\Tests\TestCase;
use Modules\User\Database\Seeders\PermissionsSeeder;
use Modules\User\Models\User;

use function Pest\Laravel\actingAs;

function supplierBillingAdmin(): User
{
    app(PermissionsSeeder::class)->run();

    $user = User::factory()->create();
    $user->syncRoles(['admin']);

    return $user;
}

function makeSupplier(): Supplier
{
    /** @var Supplier $supplier */
    $supplier = Supplier::query()->create([
        'legal_name' => 'Idraulica Rossi S.r.l.',
        'vat_number' => '01234567890',
        'is_active' => true,
    ]);

    return $supplier;
}

it('loads suppliers, delivery notes and supplier invoices in the admin panel', function (): void {
    $user = supplierBillingAdmin();
    $supplier = makeSupplier();

    /** @var DeliveryNote $deliveryNote */
    $deliveryNote = DeliveryNote::query()->create([
        'supplier_id' => $supplier->id,
        'number' => 'DDT-001',
        'received_on' => now()->toDateString(),
    ]);

    /** @var SupplierInvoice $invoice */
    $invoice = SupplierInvoice::query()->create([
        'supplier_id' => $supplier->id,
        'number' => 'FT-001',
        'issued_on' => now()->toDateString(),
        'total' => 100,
        'status_code' => 'pending',
    ]);

    filament()->setCurrentPanel('admin');

    actingAs($user)->get(SupplierResource::getUrl('index'))->assertStatus(200);
    actingAs($user)->get(SupplierResource::getUrl('edit', ['record' => $supplier->id]))->assertStatus(200);
    actingAs($user)->get(DeliveryNoteResource::getUrl('index'))->assertStatus(200);
    actingAs($user)->get(DeliveryNoteResource::getUrl('edit', ['record' => $deliveryNote->id]))->assertStatus(200);
    actingAs($user)->get(SupplierInvoiceResource::getUrl('index'))->assertStatus(200);
    actingAs($user)->get(SupplierInvoiceResource::getUrl('edit', ['record' => $invoice->id]))->assertStatus(200);
});

it('marks an invoice as disputed and lists what the supplier is overcharging', function (): void {
    $supplier = makeSupplier();

    /** @var DeliveryNote $deliveryNote */
    $deliveryNote = DeliveryNote::query()->create([
        'supplier_id' => $supplier->id,
        'number' => 'DDT-002',
        'received_on' => now()->toDateString(),
    ]);

    // Consegnati 10 raccordi a 2,00.
    $deliveryNote->lines()->create([
        'description' => 'Raccordo 1/2"',
        'quantity' => 10,
        'unit_price' => 2.00,
    ]);

    /** @var SupplierInvoice $invoice */
    $invoice = SupplierInvoice::query()->create([
        'supplier_id' => $supplier->id,
        'number' => 'FT-002',
        'issued_on' => now()->toDateString(),
        'total' => 100,
        'status_code' => 'pending',
    ]);

    // Ne fattura 12 a 2,50: quantita' eccedente + prezzo diverso dal DDT.
    $invoice->lines()->create([
        'description' => 'Raccordo 1/2"',
        'quantity' => 12,
        'unit_price' => 2.50,
    ]);

    $discrepancies = SupplierInvoiceResource::reconcile($invoice);

    $types = array_map(
        static fn (ReconciliationDiscrepancy $discrepancy): string => $discrepancy->typeCode,
        $discrepancies,
    );

    expect($types)->toContain(ReconciliationDiscrepancy::TYPE_QUANTITY_OVER)
        ->and($types)->toContain(ReconciliationDiscrepancy::TYPE_PRICE_MISMATCH)
        ->and($invoice->refresh()->status_code)->toBe('disputed');
});

it('reconciles an invoice that matches the delivery notes', function (): void {
    $supplier = makeSupplier();

    /** @var DeliveryNote $deliveryNote */
    $deliveryNote = DeliveryNote::query()->create([
        'supplier_id' => $supplier->id,
        'number' => 'DDT-003',
        'received_on' => now()->toDateString(),
    ]);

    $deliveryNote->lines()->create([
        'description' => 'Valvola a sfera',
        'quantity' => 4,
        'unit_price' => 12.00,
    ]);

    /** @var SupplierInvoice $invoice */
    $invoice = SupplierInvoice::query()->create([
        'supplier_id' => $supplier->id,
        'number' => 'FT-003',
        'total' => 48,
        'status_code' => 'pending',
    ]);

    $invoice->lines()->create([
        'description' => 'Valvola a sfera',
        'quantity' => 4,
        'unit_price' => 12.00,
    ]);

    expect(SupplierInvoiceResource::reconcile($invoice))->toBe([])
        ->and($invoice->refresh()->status_code)->toBe('reconciled');
});

it('paints the invoice status badge by severity', function (): void {
    expect(SupplierInvoiceResource::statusColor('reconciled'))->toBe('success')
        ->and(SupplierInvoiceResource::statusColor('disputed'))->toBe('danger')
        ->and(SupplierInvoiceResource::statusColor('pending'))->toBe('warning');
});
