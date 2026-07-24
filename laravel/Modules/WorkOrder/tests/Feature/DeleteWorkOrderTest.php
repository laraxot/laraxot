<?php

declare(strict_types=1);

uses(\Modules\WorkOrder\Tests\TestCase::class);

use Illuminate\Support\Facades\DB;
use Modules\WorkOrder\Actions\SoftDeleteWorkOrderAction;
use Modules\WorkOrder\Models\WorkOrder;

it('soft-deletes a work order', function (): void {
    // Arrange
    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    /** @var WorkOrder $workOrder */
    $workOrder = WorkOrder::query()->create([
        'code' => 'WO-DEL-0001',
        'customer_id' => $customerId,
        'title' => 'Test work order',
    ]);

    // Act
    app(SoftDeleteWorkOrderAction::class)->execute($workOrder);

    // Assert
    expect($workOrder->refresh()->trashed())->toBeTrue();
});

