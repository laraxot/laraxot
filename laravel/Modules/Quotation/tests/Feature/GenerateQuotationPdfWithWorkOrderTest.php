<?php

declare(strict_types=1);

uses(\Modules\Quotation\Tests\TestCase::class);

use Illuminate\Support\Facades\DB;
use Modules\Quotation\Actions\GenerateQuotationPdfAction;
use Modules\Quotation\Models\Quotation;

it('generates a quotation PDF including the related work order code when present', function (): void {
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $workOrderId = DB::table('work_orders')->insertGetId([
        'code' => 'WO-PDF-0001',
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
        'code' => 'Q-PDF-WO-0001',
        'status_code' => 'draft',
        'title' => 'Test quotation',
        'notes' => null,
        'subtotal' => 0,
        'tax_amount' => 0,
        'total' => 0,
        'issued_at' => now(),
        'confirmed_at' => null,
    ]);

    $pdf = app(GenerateQuotationPdfAction::class)->execute($quotation->refresh());

expect($pdf)->not->toBeEmpty();
    expect(substr($pdf, 0, 4))->toBe('%PDF');
    expect(strlen($pdf))->toBeGreaterThan(1000);
});

