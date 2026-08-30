<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Modules\Customer\Actions\UpsertCustomerContactByEmailAction;
use Modules\Intervention\Models\Intervention;
use Modules\Signature\Tests\TestCase;
use Modules\User\Database\Factories\UserFactory;
use Modules\WorkOrder\Actions\SetWorkOrderPrimaryContactAction;
use Modules\WorkOrder\Models\WorkOrder;

uses(\Modules\Signature\Tests\TestCase::class);

it('upserts a customer contact for a signatory and assigns it as the work order primary contact', function (): void {
    // Arrange
    $operator = UserFactory::new()->createOne();

    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $workOrder = WorkOrder::query()->create([
        'code' => 'WO-SIGN-0001',
        'customer_id' => $customerId,
        'customer_site_id' => null,
        'primary_contact_id' => null,
        'status_id' => null,
        'title' => 'WO',
        'description' => null,
        'notes' => null,
    ]);

    $intervention = Intervention::query()->create([
        'work_order_id' => $workOrder->id,
        'performed_by_user_id' => $operator->id,
        'work_minutes' => 0,
    ]);

    // Act
    $contact = app(UpsertCustomerContactByEmailAction::class)->execute(
        customerId: (int) $workOrder->customer_id,
        fullName: 'John Signatory',
        email: 'john.signatory@test.local',
        phone: '123',
        position: 'Manager',
        notes: 'note',
        customerSiteId: null,
    );
    app(SetWorkOrderPrimaryContactAction::class)->execute($workOrder->refresh(), (int) $contact->id);

    // Assert
    $workOrder->refresh();
    expect((int) $workOrder->primary_contact_id)->toBe((int) $contact->id);
    expect($intervention->workOrder()->first()?->id)->toBe($workOrder->id);
});
