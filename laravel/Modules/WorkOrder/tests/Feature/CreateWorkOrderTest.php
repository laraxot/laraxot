<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Tests\Feature;

use Illuminate\Support\Facades\DB;
use Modules\WorkOrder\Actions\CreateWorkOrderAction;
use Modules\WorkOrder\Tests\TestCase;

uses(\Modules\WorkOrder\Tests\TestCase::class);

it('creates a work order', function (): void {
    // Arrange
    $customerId = (int) DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    // Act
    $workOrder = app(CreateWorkOrderAction::class)->execute([
        'code' => 'WO-CRT-0001',
        'customer_id' => $customerId,
        'title' => 'Installazione',
        'description' => 'Test description',
    ]);

    // Assert
    expect($workOrder->id)->not->toBeNull();
    expect(DB::table('work_orders')->where('id', $workOrder->id)->value('code'))->toBe('WO-CRT-0001');
});
