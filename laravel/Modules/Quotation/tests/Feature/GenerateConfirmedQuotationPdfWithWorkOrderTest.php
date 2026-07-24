<?php

declare(strict_types=1);

uses(\Modules\Quotation\Tests\TestCase::class);

use Illuminate\Support\Facades\DB;
use Modules\Quotation\Actions\GenerateConfirmedQuotationPdfAction;
use Modules\Quotation\Models\Quotation;

it('generates a confirmed quotation PDF including the related work order code when present', function (): void {
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $workOrderId = DB::table('work_orders')->insertGetId([
        'code' => 'WO-CNF-PDF-0001',
        'customer_id' => $customerId,
        'customer_site_id' => null,
        'primary_contact_id' => null,
        'status_id' => null,
        'title' => 'PDF Work order',
        'description' => null,
        'notes' => null,
        'opened_at' => now(),
        'scheduled_at' => null,
        'started_at' => null,
        'completed_at' => null,
        'closed_at' => null,
        'created_at' => now(),
        'updated_at' => now(),
        'deleted_at' => null,
    ]);

    $quotation = Quotation::query()->create([
        'customer_id' => $customerId,
        'work_order_id' => $workOrderId,
        'code' => 'Q-CNF-PDF-WO-0001',
        'status_code' => 'confirmed',
        'title' => 'Confirmed quote',
        'notes' => null,
        'issued_at' => now()->subDay(),
        'confirmed_at' => now(),
        'subtotal' => 100.00,
        'tax_amount' => 22.00,
        'total' => 122.00,
    ]);

    $pdf = app(GenerateConfirmedQuotationPdfAction::class)->execute($quotation);

    expect($pdf)->toStartWith('%PDF');
    expect(strlen($pdf))->toBeGreaterThan(1000);
});

