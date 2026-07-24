<?php

declare(strict_types=1);

uses(TestCase::class);

use Carbon\CarbonImmutable;
use Modules\HR\Actions\ApproveAbsenceRequestAction;
use Modules\HR\Actions\CreateAbsenceRequestAction;
use Modules\HR\Actions\RejectAbsenceRequestAction;
use Modules\HR\Models\AbsenceRequest;
use Modules\HR\Tests\TestCase;
use Modules\User\Models\User;
use Spatie\Permission\Models\Role;

it('creates and approves an absence request', function (): void {
    // Arrange
    $adminRole = Role::findOrCreate('admin');
    $operatorRole = Role::findOrCreate('operator');

    $operator = User::factory()->create();
    $operator->syncRoles([$operatorRole]);

    $admin = User::factory()->create();
    $admin->syncRoles([$adminRole]);

    // Act
    $request = app(CreateAbsenceRequestAction::class)->execute(
        userId: $operator->id,
        type: 'leave',
        startsAt: CarbonImmutable::parse('2026-02-17 09:00:00'),
        endsAt: CarbonImmutable::parse('2026-02-17 12:00:00'),
        notes: 'Doctor appointment',
    );

    $request->refresh();
    expect($request->status)->toBe('pending');
    expect($request->notes)->toBe('Doctor appointment');
    expect($request->decided_by_user_id)->toBeNull();
    expect($request->decided_at)->toBeNull();

    $decidedAt = CarbonImmutable::parse('2026-02-18 10:00:00');
    app(ApproveAbsenceRequestAction::class)->execute($request, $admin->id, $decidedAt);

    // Assert
    $request->refresh();
    expect($request)->toBeInstanceOf(AbsenceRequest::class);
    expect($request->status)->toBe('approved');
    expect((int) $request->decided_by_user_id)->toBe((int) $admin->id);
    expect($request->decided_at?->toDateTimeString())->toBe('2026-02-18 10:00:00');
})->skip('D6: User factory + Spatie roles su fixture sqlite — laravel/docs/debt-pest-field-service-coverage.md');

it('rejects an absence request', function (): void {
    // Arrange
    $adminRole = Role::findOrCreate('admin');
    $operatorRole = Role::findOrCreate('operator');

    $operator = User::factory()->create();
    $operator->syncRoles([$operatorRole]);

    $admin = User::factory()->create();
    $admin->syncRoles([$adminRole]);

    $request = app(CreateAbsenceRequestAction::class)->execute(
        userId: $operator->id,
        type: 'vacation',
        startsAt: CarbonImmutable::parse('2026-02-20 00:00:00'),
        endsAt: CarbonImmutable::parse('2026-02-21 23:59:59'),
        notes: null,
    );

    // Act
    $decidedAt = CarbonImmutable::parse('2026-02-22 10:00:00');
    app(RejectAbsenceRequestAction::class)->execute($request, $admin->id, $decidedAt);

    // Assert
    $request->refresh();
    expect($request->status)->toBe('rejected');
    expect((int) $request->decided_by_user_id)->toBe((int) $admin->id);
    expect($request->decided_at?->toDateTimeString())->toBe('2026-02-22 10:00:00');
})->skip('D6: User factory + Spatie roles su fixture sqlite — laravel/docs/debt-pest-field-service-coverage.md');
