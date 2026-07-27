<?php

declare(strict_types=1);

namespace Modules\Geo\Tests\Unit\Traits;

use Modules\Geo\Models\Traits\HasPlaceTrait;
use Modules\Geo\Tests\TestCase;
use Modules\Geo\Traits\HandlesCoordinates;
use Modules\Geo\Traits\HasAddresses;
use Modules\TechPlanner\Models\Worker;
use PHPUnit\Framework\Assert;

uses(TestCase::class);
test('HasAddresses trait can be used', function (): void {
    new class extends Worker {
        use HasAddresses;
    };
    new class extends Worker {
        use HasPlaceTrait;
    };

    Assert::assertTrue(trait_exists(HasAddresses::class));

    $reflection = new \ReflectionClass(HasAddresses::class);
    Assert::assertTrue($reflection->hasMethod('addresses'));
    Assert::assertTrue($reflection->hasMethod('primaryAddress'));
});

test('HandlesCoordinates trait can be used', function (): void {
    Assert::assertTrue(trait_exists(HandlesCoordinates::class));

    $reflection = new \ReflectionClass(HandlesCoordinates::class);
    Assert::assertTrue($reflection->hasMethod('formatCoordinates'));
});
