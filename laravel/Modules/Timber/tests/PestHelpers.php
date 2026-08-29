<?php

declare(strict_types=1);

use Livewire\Component;
use Livewire\Features\SupportTesting\Testable;
use Livewire\Livewire;
use Modules\User\Database\Factories\UserFactory;
use Modules\User\Models\User;
use Spatie\Permission\Models\Role;

use function Pest\Laravel\actingAs;

function timberUserWithRole(string $role): User
{
    Role::findOrCreate($role);

    $user = UserFactory::new()->createOne();
    $user->syncRoles([$role]);

    return $user;
}

function timberOperator(): User
{
    return timberUserWithRole('timber_operator');
}

function timberAdmin(): User
{
    return timberUserWithRole('timber_admin');
}

function actingAsTimberOperator(): User
{
    $user = timberOperator();
    actingAs($user);

    return $user;
}

function actingAsTimberAdmin(): User
{
    $user = timberAdmin();
    actingAs($user);

    return $user;
}

/**
 * @template T of \Livewire\Component
 *
 * @param  class-string<T>  $componentClass
 * @param  array<string, scalar|null>  $mount
 * @return Testable<T>
 */
function testTimberLivewire(string $componentClass, array $mount = []): Testable
{
    /** @var Testable<T> $component */
    $component = $mount === []
        ? Livewire::test($componentClass)
        : Livewire::test($componentClass, $mount);

    return $component;
}

/**
 * @param  array<string, scalar|null>  $query
 * @return Testable<Component>
 */
function testTimberLivewireWithQuery(string $componentClass, array $query): Testable
{
    Livewire::withQueryParams($query);

    /** @var Testable<Component> $component */
    $component = Livewire::test($componentClass);

    return $component;
}
