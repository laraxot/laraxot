<?php

declare(strict_types=1);

namespace Modules\Auth\Tests\Feature;

use Modules\Auth\Tests\TestCase;

uses(TestCase::class);

use Modules\Auth\Database\Seeders\WtsRolesSeeder;
use Spatie\Permission\Models\Role;

it('creates the wts, wts_operator and wts_admin roles', function (): void {
    app(WtsRolesSeeder::class)->run();

    expect(Role::where('name', 'wts')->exists())->toBeTrue()
        ->and(Role::where('name', 'wts_operator')->exists())->toBeTrue()
        ->and(Role::where('name', 'wts_admin')->exists())->toBeTrue();
});

it('is idempotent when run more than once', function (): void {
    app(WtsRolesSeeder::class)->run();
    app(WtsRolesSeeder::class)->run();

    expect(Role::where('name', 'wts_admin')->count())->toBe(1);
});
