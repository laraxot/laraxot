<?php

declare(strict_types=1);

use Livewire\Volt\Component as VoltComponent;
use Modules\Cms\Http\Volt\VerifyComponent;
use Modules\Cms\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);
describe('VerifyComponent', function (): void {
    test('verify component extends volt component', function (): void {
        $component = new VerifyComponent();

        Assert::assertInstanceOf(VoltComponent::class, $component);
    });

    it('verify component has resend method')->todo();
    test('resend method returns void', function (): void {
        $reflection = new ReflectionClass(VerifyComponent::class);
        $method = $reflection->getMethod('resend');
        $returnType = $method->getReturnType();

        Assert::assertNull($returnType);

        Assert::assertSame('void', (string) $returnType);
    });
});
