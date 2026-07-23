<?php

declare(strict_types=1);

use Filament\Resources\Resource;
use Modules\Xot\Filament\Resources\XotBaseResource;
use Modules\Xot\Tests\Fixtures\Filament\Resources\NavigationProbeResource;
use Modules\Xot\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('xot base resource extends filament resource', function (): void {
    Assert::assertInstanceOf(Resource::class, new NavigationProbeResource());
});

test('xot base resource has navigation icon', function (): void {
    Assert::assertSame('heroicon-o-rectangle-stack', NavigationProbeResource::getNavigationIcon());
});

test('xot base resource has navigation group', function (): void {
    Assert::assertSame('Test Group', NavigationProbeResource::getNavigationGroup());
});

test('xot base resource has navigation sort', function (): void {
    Assert::assertSame(1, NavigationProbeResource::getNavigationSort());
});

test('xot base resource can be instantiated', function (): void {
    Assert::assertInstanceOf(XotBaseResource::class, new NavigationProbeResource());
});
