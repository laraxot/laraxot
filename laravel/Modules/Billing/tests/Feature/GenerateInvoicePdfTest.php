<?php

declare(strict_types=1);

uses(\Modules\Billing\Tests\TestCase::class);

use Illuminate\Support\Facades\DB;
use Modules\Billing\Actions\GenerateInvoicePdfAction;
use Modules\Billing\Models\Invoice;
use Modules\Billing\Tests\TestCase;

it('generates an invoice PDF', function (): void {
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'email' => 'amministrazione@acme.test',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    /** @var \Modules\Billing\Models\Invoice $invoice */
    $invoice = Invoice::query()->create([
        'number' => 'FAT-PDF-0001',
        'issued_on' => now()->toDateString(),
        'customer_id' => $customerId,
        'work_order_id' => null,
        'total' => 345.67,
        'notes' => "Fattura di test\nRiga 2",
    ]);

    $pdf = app(GenerateInvoicePdfAction::class)->execute($invoice->refresh());

expect($pdf)->not->toBeEmpty();
    expect(substr($pdf, 0, 4))->toBe('%PDF');
    expect(strlen($pdf))->toBeGreaterThan(1000);
});

it('generates an invoice PDF when linked to a work order', function (): void {
    // Arrange
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Cliente WO S.r.l.',
        'email' => 'amministrazione@cliente-wo.test',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $workOrderId = DB::table('work_orders')->insertGetId([
        'code' => 'WO-INV-0001',
        'customer_id' => $customerId,
        'title' => 'Commessa collegata fattura',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    /** @var \Modules\Billing\Models\Invoice $invoice */
    $invoice = Invoice::query()->create([
        'number' => 'FAT-PDF-0002',
        'issued_on' => now()->toDateString(),
        'customer_id' => $customerId,
        'work_order_id' => $workOrderId,
        'total' => 99.99,
        'notes' => 'Fattura con commessa collegata',
    ]);

    // Act
    $pdf = app(GenerateInvoicePdfAction::class)->execute($invoice->refresh());

    // Assert
expect($pdf)->not->toBeEmpty();
    expect(substr($pdf, 0, 4))->toBe('%PDF');
    expect(strlen($pdf))->toBeGreaterThan(1000);
});
