<?php

declare(strict_types=1);

namespace Modules\Auth\Tests\Feature;

use Modules\Auth\Tests\TestCase;

uses(TestCase::class);

use Modules\Auth\Database\Seeders\TimberRolesSeeder;
use Spatie\Permission\Models\Role;

it('creates the timber, timber_operator and timber_admin roles', function (): void {
    app(TimberRolesSeeder::class)->run();

    expect(Role::where('name', 'timber')->exists())->toBeTrue()
        ->and(Role::where('name', 'timber_operator')->exists())->toBeTrue()
        ->and(Role::where('name', 'timber_admin')->exists())->toBeTrue();
});

it('is idempotent when run more than once', function (): void {
    app(TimberRolesSeeder::class)->run();
    app(TimberRolesSeeder::class)->run();

    expect(Role::where('name', 'timber_admin')->count())->toBe(1);
});
