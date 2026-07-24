<?php

declare(strict_types=1);

uses(\Modules\WorkOrder\Tests\TestCase::class);

use Modules\Customer\Models\Customer;
use Modules\WorkOrder\Actions\RestoreWorkOrderCascadeAction;
use Modules\WorkOrder\Models\WorkOrder;

it('does nothing when restoring a non-trashed work order', function (): void {
    $customer = Customer::query()->create([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => true,
    ]);

    $workOrder = WorkOrder::query()->create([
        'code' => 'WO-NOOP-001',
        'customer_id' => $customer->id,
        'customer_site_id' => null,
        'primary_contact_id' => null,
        'status_id' => null,
        'title' => 'Noop restore work order',
        'description' => null,
        'notes' => null,
        'opened_at' => now(),
        'scheduled_at' => null,
    ]);

    expect($workOrder->trashed())->toBeFalse();

    app(RestoreWorkOrderCascadeAction::class)->execute($workOrder);

    $workOrder->refresh();
    expect($workOrder->trashed())->toBeFalse();
});

