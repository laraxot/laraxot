<?php

declare(strict_types=1);

uses(\Modules\WorkOrder\Tests\TestCase::class);

use Modules\Customer\Models\Customer;
use Modules\WorkOrder\Actions\GenerateWorkOrderPdfAction;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Models\WorkOrderStatus;

it('generates a work order PDF', function (): void {
    // Arrange
    $customer = Customer::query()->create([
        'legal_name' => 'Acme S.p.A.',
        'vat_number' => null,
        'tax_code' => null,
        'email' => null,
        'phone' => null,
        'address_line1' => 'Via Roma 1',
        'address_line2' => null,
        'city' => 'Milano',
        'postal_code' => '20100',
        'province' => 'MI',
        'country_code' => 'IT',
        'payment_method' => null,
        'iban' => null,
        'notes' => null,
        'is_active' => true,
    ]);

    $status = WorkOrderStatus::query()->firstOrCreate(
        ['code' => 'draft'],
        ['name' => 'Draft', 'color' => 'gray', 'is_terminal' => 0, 'sort_order' => 10, 'created_at' => now(), 'updated_at' => now()],
    );

    $workOrder = WorkOrder::query()->create([
        'code' => 'WO-PDF-0001',
        'customer_id' => $customer->id,
        'customer_site_id' => null,
        'primary_contact_id' => null,
        'status_id' => $status->id,
        'title' => 'Test Work Order',
        'description' => "Line 1\nLine 2",
        'notes' => 'Some notes',
        'opened_at' => now(),
        'scheduled_at' => null,
        'started_at' => null,
        'completed_at' => null,
        'closed_at' => null,
    ]);

    // Act
    $pdf = app(GenerateWorkOrderPdfAction::class)->execute($workOrder);

    // Assert
    expect($pdf)->toBeString();
    expect(substr($pdf, 0, 4))->toBe('%PDF');
    expect(strlen($pdf))->toBeGreaterThan(1000);
});
