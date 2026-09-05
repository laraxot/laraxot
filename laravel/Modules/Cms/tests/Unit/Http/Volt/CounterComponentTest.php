<?php

declare(strict_types=1);

use Livewire\Volt\Component;
use Modules\Cms\Http\Volt\CounterComponent;
use Modules\Cms\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);
describe('CounterComponent', function (): void {
    test('counter component extends volt component', function (): void {
        $component = new CounterComponent();

        Assert::assertInstanceOf(Component::class, $component);
    });

    test('counter component has count property', function (): void {
        $component = new CounterComponent();

        Assert::assertTrue((new ReflectionClass($component))->hasProperty('count'));
    });

    it('counter component has increment method')->todo();
    it('counter component has decrement method')->todo();
    test('counter component uses correct namespace', function (): void {
        $reflector = new ReflectionClass(CounterComponent::class);

        Assert::assertSame('Modules\Cms\Http\Volt', $reflector->getNamespaceName());
    });

    test('counter component count starts at zero', function (): void {
        $component = new CounterComponent();

        Assert::assertSame(0, $component->count);
    });
});
