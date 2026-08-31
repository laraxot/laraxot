<?php

declare(strict_types=1);

namespace Modules\HR\Tests\Feature;

use Carbon\CarbonImmutable;
use Modules\HR\Actions\ApproveAbsenceRequestAction;
use Modules\HR\Actions\CreateAbsenceRequestAction;
use Modules\HR\Actions\RejectAbsenceRequestAction;
use Modules\HR\Models\AbsenceRequest;
use Modules\HR\Tests\TestCase;

uses(\Modules\HR\Tests\TestCase::class);

it('creates and approves an absence request', function (): void {

    $operator = hrUserWithRole('operator');
    $admin = hrUserWithRole('admin');

    $request = app(CreateAbsenceRequestAction::class)->execute(
        userId: (int) $operator->id,
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
    app(ApproveAbsenceRequestAction::class)->execute($request, (int) $admin->id, $decidedAt);

    $request->refresh();
expect($request)->not->toBeNull();
    expect($request->status)->toBe('approved');
    expect((int) $request->decided_by_user_id)->toBe((int) $admin->id);
    expect($request->decided_at?->toDateTimeString())->toBe('2026-02-18 10:00:00');
});

it('rejects an absence request', function (): void {

    $operator = hrUserWithRole('operator');
    $admin = hrUserWithRole('admin');

    $request = app(CreateAbsenceRequestAction::class)->execute(
        userId: (int) $operator->id,
        type: 'vacation',
        startsAt: CarbonImmutable::parse('2026-02-20 00:00:00'),
        endsAt: CarbonImmutable::parse('2026-02-21 23:59:59'),
        notes: null,
    );

    $decidedAt = CarbonImmutable::parse('2026-02-22 10:00:00');
    app(RejectAbsenceRequestAction::class)->execute($request, (int) $admin->id, $decidedAt);

    $request->refresh();
    expect($request->status)->toBe('rejected');
    expect((int) $request->decided_by_user_id)->toBe((int) $admin->id);
    expect($request->decided_at?->toDateTimeString())->toBe('2026-02-22 10:00:00');
});
