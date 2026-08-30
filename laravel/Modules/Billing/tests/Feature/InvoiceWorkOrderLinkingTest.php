<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Modules\Billing\Actions\LinkInvoiceToWorkOrderAction;
use Modules\Billing\Actions\UnlinkInvoiceFromWorkOrderAction;
use Modules\Billing\Models\Invoice;
use Modules\Billing\Tests\TestCase;

uses(\Modules\Billing\Tests\TestCase::class);

it('links and unlinks invoices to work orders idempotently', function (): void {
    // Arrange
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $workOrderId = DB::table('work_orders')->insertGetId([
        'customer_id' => $customerId,
        'title' => 'Test work order',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    /** @var Invoice $invoice */
    $invoice = Invoice::query()->create([
        'customer_id' => $customerId,
        'work_order_id' => null,
        'number' => 'INV-TEST-0001',
        'issued_on' => '2026-02-16',
        'total' => 123.45,
        'notes' => null,
    ]);

    // Act (link twice)
    $first = app(LinkInvoiceToWorkOrderAction::class)->execute($invoice, $workOrderId);
    $second = app(LinkInvoiceToWorkOrderAction::class)->execute($invoice, $workOrderId);

    // Assert
    expect($second->id)->toBe($first->id);
    expect(DB::table('invoice_work_order_links')->where('invoice_id', $invoice->id)->where('work_order_id', $workOrderId)->count())
        ->toBe(1);

    // Act (unlink twice)
    app(UnlinkInvoiceFromWorkOrderAction::class)->execute($invoice, $workOrderId);
    app(UnlinkInvoiceFromWorkOrderAction::class)->execute($invoice, $workOrderId);

    // Assert
    expect(DB::table('invoice_work_order_links')->where('invoice_id', $invoice->id)->where('work_order_id', $workOrderId)->count())
        ->toBe(0);
});
