<?php

declare(strict_types=1);

use Livewire\Volt\Component;
use Modules\Cms\Http\Volt\LoginComponent;
use Modules\Cms\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);
describe('LoginComponent', function (): void {
    test('login component extends volt component', function (): void {
        $component = new LoginComponent();

        Assert::assertInstanceOf(Component::class, $component);
    });

    test('login component has email property', function (): void {
        $component = new LoginComponent();

        Assert::assertTrue((new ReflectionClass($component))->hasProperty('email'));
    });

    test('login component has password property', function (): void {
        $component = new LoginComponent();

        Assert::assertTrue((new ReflectionClass($component))->hasProperty('password'));
    });

    test('login component has remember property', function (): void {
        $component = new LoginComponent();

        Assert::assertTrue((new ReflectionClass($component))->hasProperty('remember'));
    });

    it('login component has authenticate method')->todo();
    test('login component uses correct namespace', function (): void {
        $reflector = new ReflectionClass(LoginComponent::class);

        Assert::assertSame('Modules\Cms\Http\Volt', $reflector->getNamespaceName());
    });
});
