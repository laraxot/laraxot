<?php

declare(strict_types=1);

use Livewire\Volt\Component as VoltComponent;
use Modules\Cms\Http\Volt\Password\ConfirmComponent;
use Modules\Cms\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);
describe('Password ConfirmComponent', function (): void {
    test('confirm component extends volt component', function (): void {
        $component = new ConfirmComponent();

        Assert::assertInstanceOf(VoltComponent::class, $component);
    });

    test('confirm component has password property', function (): void {
        $component = new ConfirmComponent();

        Assert::assertTrue((new ReflectionClass($component))->hasProperty('password'));

        Assert::assertSame('', $component->password);
    });

    it('confirm component has confirm method')->todo();
    test('confirm method declares redirect response return type', function (): void {
        $reflection = new ReflectionClass(ConfirmComponent::class);
        $method = $reflection->getMethod('confirm');
        $returnType = $method->getReturnType();

        Assert::assertNull($returnType);

        Assert::assertSame('Illuminate\Http\RedirectResponse', (string) $returnType);
    });
});
