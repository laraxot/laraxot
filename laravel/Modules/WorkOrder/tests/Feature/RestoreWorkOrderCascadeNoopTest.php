<?php

declare(strict_types=1);

namespace Modules\WorkOrder\Tests\Feature;

use Illuminate\Support\Facades\DB;
use Modules\WorkOrder\Actions\RestoreWorkOrderCascadeAction;
use Modules\WorkOrder\Models\WorkOrder;
use Modules\WorkOrder\Tests\TestCase;

uses(\Modules\WorkOrder\Tests\TestCase::class);

it('does nothing when restoring a non-trashed work order', function (): void {
    $customerId = (int) DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $workOrder = WorkOrder::query()->create([
        'code' => 'WO-NOOP-001',
        'customer_id' => $customerId,
        'title' => 'Noop restore work order',
        'opened_at' => now(),
    ]);

    expect($workOrder->trashed())->toBeFalse();

    app(RestoreWorkOrderCascadeAction::class)->execute($workOrder);

    $workOrder->refresh();
    expect($workOrder->trashed())->toBeFalse();
});
