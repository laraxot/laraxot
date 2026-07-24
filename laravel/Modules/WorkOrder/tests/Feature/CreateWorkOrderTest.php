<?php

declare(strict_types=1);

uses(\Modules\WorkOrder\Tests\TestCase::class);

use Illuminate\Support\Facades\DB;
use Modules\WorkOrder\Actions\CreateWorkOrderAction;

it('creates a work order', function (): void {
    // Arrange
    $customerId = DB::table('customers')->insertGetId([
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
        'notes' => null,
        'opened_at' => null,
        'scheduled_at' => null,
        'started_at' => null,
        'completed_at' => null,
        'closed_at' => null,
    ]);

    // Assert
    expect($workOrder->id)->not->toBeNull();
    expect(DB::table('work_orders')->where('id', $workOrder->id)->value('code'))->toBe('WO-CRT-0001');
});

