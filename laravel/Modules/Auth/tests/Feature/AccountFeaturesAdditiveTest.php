<?php

declare(strict_types=1);

namespace Modules\Auth\Tests\Feature;

use Modules\Auth\Tests\TestCase;

uses(TestCase::class);

use App\Support\AccountFeatures;
use Modules\User\Models\User;
use Spatie\Permission\Models\Role;

beforeEach(function (): void {
    foreach (['admin', 'operator', 'timber_admin', 'timber_operator'] as $role) {
        Role::findOrCreate($role);
    }
});

it('grants a feature when any role enables it even if another role disables it', function (): void {
    $adminOperator = User::factory()->create();
    $adminOperator->syncRoles(['timber_admin', 'timber_operator']);

    // timber_operator sets timber.registries => false, timber_admin grants timber.* => true.
    expect(AccountFeatures::hasFeature($adminOperator, 'timber.registries', true))->toBeTrue();
});

it('keeps a feature denied for a role that only disables it', function (): void {
    $operator = User::factory()->create();
    $operator->syncRoles(['timber_operator']);

    expect(AccountFeatures::hasFeature($operator, 'timber.registries', true))->toBeFalse();
});

it('grants everything to the pure admin role via wildcard', function (): void {
    $admin = User::factory()->create();
    $admin->syncRoles(['timber_admin']);

    expect(AccountFeatures::hasFeature($admin, 'timber.registries', true))->toBeTrue()
        ->and(AccountFeatures::hasFeature($admin, 'timber.access', true))->toBeTrue();
});

it('keeps account overrides authoritative over additive role grants', function (): void {
    // The gsbuildingservice account explicitly disables operator.catalog in config.
    $account = User::factory()->create(['email' => 'info@gsbuildingservice.it']);
    $account->syncRoles(['admin', 'operator']);

    // admin would otherwise grant it; the account-level deny must win.
    expect(AccountFeatures::hasFeature($account, 'operator.catalog', true))->toBeFalse();
});
