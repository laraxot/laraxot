<?php

declare(strict_types=1);

use Modules\Intervention\Tests\TestCase;

uses(\Modules\Intervention\Tests\TestCase::class);

use Illuminate\Support\Facades\DB;
use Modules\Intervention\Actions\SoftDeleteInterventionAction;
use Modules\Intervention\Models\Intervention;
use Modules\User\Database\Factories\UserFactory;

it('soft-deletes an intervention', function (): void {
    // Arrange
    $user = UserFactory::new()->createOne();

    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    $workOrderId = DB::table('work_orders')->insertGetId([
        'code' => 'WO-INT-DEL-0001',
        'customer_id' => $customerId,
        'title' => 'Test work order',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $intervention = Intervention::query()->create([
        'work_order_id' => $workOrderId,
        'performed_by_user_id' => $user->id,
        'started_at' => now(),
        'ended_at' => now(),
        'work_minutes' => 0,
    ]);

    // Act
    app(SoftDeleteInterventionAction::class)->execute($intervention);

    // Assert
    expect($intervention->refresh()->trashed())->toBeTrue();
});
