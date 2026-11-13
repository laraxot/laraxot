<?php

declare(strict_types=1);

namespace Modules\Employee\Tests\Unit\Policies;

use Illuminate\Contracts\Auth\Access\Authorizable;
use Mockery\MockInterface;
use Modules\Employee\Enums\AbsenceRequestStatusEnum;
use Modules\Employee\Models\AbsenceRequest;
use Modules\Employee\Policies\AbsenceRequestPolicy;
use Modules\Xot\Contracts\UserContract;

/*
 * Utenti e richieste sono in memoria (nessun database, nessuna app): la regola dipende solo da ruolo, id e stato.
 * Il verdetto segue il Gate: se `before()` risponde, vale quello; altrimenti decide il metodo della policy.
 * Ogni verdetto usa una policy nuova perche' `before()` memorizza il risultato con `once()` per istanza.
 */
function absencePolicyUser(string $id, bool $isSuperAdmin): UserContract
{
    /** @var MockInterface&UserContract $user */
    $user = \Mockery::mock(UserContract::class, Authorizable::class);
    $user->shouldReceive('hasRole')->andReturnUsing(static fn (mixed $role): bool => $isSuperAdmin && $role === 'super-admin');
    $user->shouldReceive('getAuthIdentifier')->andReturn($id);

    return $user;
}

function absencePolicyRequest(string $ownerId, AbsenceRequestStatusEnum $status): AbsenceRequest
{
    return new AbsenceRequest(['user_id' => $ownerId, 'status' => $status->value]);
}

function absencePolicyAllows(UserContract $user, string $ability, ?AbsenceRequest $request = null): bool
{
    $policy = new AbsenceRequestPolicy();
    $before = $policy->before($user, $ability);
    if ($before !== null) {
        return $before;
    }

    $verdict = $request === null ? $policy->{$ability}($user) : $policy->{$ability}($user, $request);

    return $verdict === true;
}

afterEach(function (): void {
    \Mockery::close();
});

test('an employee lists and creates absence requests', function (): void {
    $employee = absencePolicyUser('u1', false);

    expect(absencePolicyAllows($employee, 'viewAny'))->toBeTrue()
        ->and(absencePolicyAllows($employee, 'create'))->toBeTrue();
});

test('an employee sees only their own absence request', function (): void {
    $employee = absencePolicyUser('u1', false);

    expect(absencePolicyAllows($employee, 'view', absencePolicyRequest('u1', AbsenceRequestStatusEnum::APPROVED)))->toBeTrue()
        ->and(absencePolicyAllows($employee, 'view', absencePolicyRequest('u2', AbsenceRequestStatusEnum::PENDING)))->toBeFalse();
});

test('an employee edits or withdraws only their own pending absence request', function (string $ability): void {
    $employee = absencePolicyUser('u1', false);

    expect(absencePolicyAllows($employee, $ability, absencePolicyRequest('u1', AbsenceRequestStatusEnum::PENDING)))->toBeTrue()
        ->and(absencePolicyAllows($employee, $ability, absencePolicyRequest('u1', AbsenceRequestStatusEnum::APPROVED)))->toBeFalse()
        ->and(absencePolicyAllows($employee, $ability, absencePolicyRequest('u1', AbsenceRequestStatusEnum::REJECTED)))->toBeFalse()
        ->and(absencePolicyAllows($employee, $ability, absencePolicyRequest('u2', AbsenceRequestStatusEnum::PENDING)))->toBeFalse();
})->with(['update', 'delete']);

test('an employee cannot decide, restore, force delete, replicate or reorder', function (string $ability): void {
    $employee = absencePolicyUser('u1', false);

    $request = in_array($ability, ['approve', 'reject', 'replicate'], true)
        ? absencePolicyRequest('u1', AbsenceRequestStatusEnum::PENDING)
        : null;

    expect(absencePolicyAllows($employee, $ability, $request))->toBeFalse();
})->with(['manage', 'approve', 'reject', 'deleteAny', 'restore', 'restoreAny', 'forceDelete', 'forceDeleteAny', 'replicate', 'reorder']);

test('a super-admin can do everything through before()', function (string $ability): void {
    $admin = absencePolicyUser('a1', true);

    expect(absencePolicyAllows($admin, $ability, absencePolicyRequest('u2', AbsenceRequestStatusEnum::APPROVED)))->toBeTrue();
})->with([
    'viewAny', 'create', 'view', 'update', 'delete', 'manage', 'approve', 'reject',
    'deleteAny', 'restore', 'restoreAny', 'forceDelete', 'forceDeleteAny', 'replicate', 'reorder',
]);

test('a non scalar identifier owns nothing', function (): void {
    /** @var MockInterface&UserContract $user */
    $user = \Mockery::mock(UserContract::class, Authorizable::class);
    $user->shouldReceive('hasRole')->andReturn(false);
    $user->shouldReceive('getAuthIdentifier')->andReturn(null);

    expect(absencePolicyAllows($user, 'view', absencePolicyRequest('', AbsenceRequestStatusEnum::PENDING)))->toBeFalse();
});
