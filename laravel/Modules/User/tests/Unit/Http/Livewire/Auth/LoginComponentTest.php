<?php

declare(strict_types=1);

namespace Modules\User\Tests\Unit\Http\Livewire\Auth;

use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\TextInput;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Mockery\ExpectationInterface;
use Modules\User\Http\Livewire\Auth\Login;
use Modules\User\Tests\TestCase;
use Modules\Xot\Datas\XotData;
use PHPUnit\Framework\Assert;
use Spatie\Permission\Models\Role;

uses(TestCase::class)->group('no-user-db');

afterEach(function (): void {
    \Mockery::close();
});

/**
 * @return list<TextInput|Checkbox>
 */
function loginFormSchema(Login $component): array
{
    $method = new \ReflectionMethod($component, 'getFormSchema');
    $method->setAccessible(true);

    /** @var list<TextInput|Checkbox> $schema */
    $schema = $method->invoke($component);

    return $schema;
}

/**
 * @param  list<string>  $roleNames
 */
function loginRedirectForRoles(array $roleNames): string
{
    app()->setLocale('it');
    /** @var class-string<Model> $userClass */
    $userClass = XotData::make()->getUserClass();
    $user = new $userClass;
    $user->forceFill(['id' => 'redirect-user']);

    /** @var Collection<int, Role> $roles */
    $roles = collect(array_map(
        static fn (string $name): Role => new Role(['name' => $name, 'guard_name' => 'web']),
        $roleNames
    ));

    $relation = \Mockery::mock(BelongsToMany::class);
    $relationGetExpectation = $relation->shouldReceive('get');
    \assert($relationGetExpectation instanceof ExpectationInterface);
    $relationGetExpectation->andReturn($roles);

    $userMock = \Mockery::mock($user)->makePartial();
    $userRolesExpectation = $userMock->shouldReceive('roles');
    \assert($userRolesExpectation instanceof ExpectationInterface);
    $userRolesExpectation->andReturn($relation);

    Auth::shouldReceive('user')->andReturn($userMock);

    $component = new Login;
    $method = new \ReflectionMethod($component, 'getRedirectUrl');
    $method->setAccessible(true);

    $redirect = $method->invoke($component);
    Assert::assertInstanceOf(RedirectResponse::class, $redirect);

    return $redirect->getTargetUrl();
}

describe('Login Livewire component', function (): void {
    test('mount initializes component without throwing', function (): void {
        $component = new Login;
        $component->mount();

        Assert::assertIsArray($component->data);
    });

    test('form schema exposes email password remember fields', function (): void {
        $schema = loginFormSchema(new Login);

        Assert::assertCount(3, $schema);
        Assert::assertInstanceOf(TextInput::class, $schema[0]);
        Assert::assertSame('email', $schema[0]->getName());
        Assert::assertSame('password', $schema[1]->getName());
        Assert::assertSame('remember', $schema[2]->getName());
    });

    test('render returns login view', function (): void {
        $view = (new Login)->render();

        Assert::assertInstanceOf(View::class, $view);
        Assert::assertSame('user::livewire.auth.login', $view->name());
    });

    test('getRedirectUrl sends single module admin to module panel', function (): void {
        Assert::assertStringEndsWith('/notify/admin', loginRedirectForRoles(['notify::admin']));
    });

    test('getRedirectUrl sends multi-admin user to global admin', function (): void {
        Assert::assertStringEndsWith('/admin', loginRedirectForRoles(['notify::admin', 'user::admin']));
    });

    test('getRedirectUrl sends non-admin to localized home', function (): void {
        Assert::assertStringEndsWith('/it', loginRedirectForRoles([]));
    });
});
