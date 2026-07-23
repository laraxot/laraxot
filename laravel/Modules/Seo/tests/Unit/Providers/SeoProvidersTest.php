<?php

declare(strict_types=1);

namespace Modules\Seo\Tests\Unit\Providers;
use Modules\Seo\Providers\EventServiceProvider;
use Modules\Seo\Providers\SeoServiceProvider;
use Modules\Seo\Adapters\MetatagManager;
use PHPUnit\Framework\Assert;
uses(\Modules\Seo\Tests\TestCase::class);

it('registers metatag service singleton and provides list', function (): void {
    $provider = new SeoServiceProvider(app());
    $provider->register();

    $instanceA = app(MetatagManager::class);
    $instanceB = app(MetatagManager::class);

    Assert::assertInstanceOf(MetatagManager::class, $instanceA);
    Assert::assertSame($instanceA, $instanceB);
    Assert::assertContains(MetatagManager::class, $provider->provides());
});

it('event service provider enables event discovery', function (): void {
    $reflection = new \ReflectionClass(EventServiceProvider::class);
    $property = $reflection->getProperty('shouldDiscoverEvents');
    $property->setAccessible(true);

    Assert::assertTrue($property->getValue());
});
