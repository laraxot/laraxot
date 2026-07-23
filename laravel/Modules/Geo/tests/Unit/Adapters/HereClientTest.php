<?php

declare(strict_types=1);

namespace Modules\Geo\Tests\Unit\Adapters;

use Modules\Geo\Adapters\HereClient;
use PHPUnit\Framework\Assert;

it('instantiates the Here client', function (): void {
    Assert::assertInstanceOf(HereClient::class, new HereClient());
});

it('exposes route duration and length', function (): void {
    Assert::assertTrue((new \ReflectionClass(HereClient::class))->hasMethod('getDurationAndLength'));
});
