<?php

declare(strict_types=1);

namespace Modules\Geo\Tests\Unit\Traits;

use Modules\Geo\Models\Traits\HasPlaceTrait;
use Modules\Geo\Traits\HandlesCoordinates;
use Modules\Geo\Traits\HasAddresses;
use PHPUnit\Framework\Assert;

test('HasAddresses trait exists with expected API', function (): void {
    Assert::assertTrue(trait_exists(HasAddresses::class));
    Assert::assertTrue(trait_exists(HasPlaceTrait::class));

    $reflection = new \ReflectionClass(HasAddresses::class);
    Assert::assertTrue($reflection->hasMethod('addresses'));
    Assert::assertTrue($reflection->hasMethod('primaryAddress'));
});

test('HandlesCoordinates trait can be used', function (): void {
    Assert::assertTrue(trait_exists(HandlesCoordinates::class));

    $reflection = new \ReflectionClass(HandlesCoordinates::class);
    Assert::assertTrue($reflection->hasMethod('formatCoordinates'));
});
