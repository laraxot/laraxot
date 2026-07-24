<?php

declare(strict_types=1);

uses(\Modules\Intervention\Tests\TestCase::class);

use Modules\User\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\Intervention\Actions\CreateIssueReportAction;
use Modules\Intervention\Models\Intervention;
use Modules\Intervention\Models\IssueReport;

it('creates an issue report for an intervention', function (): void {
    // Arrange
    $user = User::factory()->create();

    $customerId = DB::table('customers')->insertGetId([
        'legal_name' => 'Acme S.p.A.',
        'is_active' => 1,
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $workOrderId = DB::table('work_orders')->insertGetId([
        'code' => 'WO-ISSUE-0001',
        'customer_id' => $customerId,
        'title' => 'Test work order',
        'created_at' => now(),
        'updated_at' => now(),
    ]);

    $intervention = Intervention::query()->create([
        'work_order_id' => $workOrderId,
        'performed_by_user_id' => $user->id,
        'started_at' => now()->startOfDay()->addHours(9),
        'ended_at' => now()->startOfDay()->addHours(10),
        'work_minutes' => 60,
    ]);

    // Act
    $report = app(CreateIssueReportAction::class)->execute(
        intervention: $intervention,
        createdByUserId: $user->id,
        body: 'Found an issue on site',
    );

    // Assert
    expect($report)->toBeInstanceOf(IssueReport::class);
    expect((int) $report->intervention_id)->toBe((int) $intervention->id);
    expect($report->body)->toBe('Found an issue on site');
    expect(IssueReport::query()->count())->toBe(1);
});
