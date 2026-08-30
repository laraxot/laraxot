<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Tests\Feature;

use Illuminate\Support\Facades\DB;
use Modules\WorkOrder\Actions\SoftDeleteWorkOrderAction;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Tests\TestCase;

uses(\Modules\WorkOrder\Tests\TestCase::class);

it('soft-deletes a work order', function (): void {
    // Arrange
    $customerId = (int) DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

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
