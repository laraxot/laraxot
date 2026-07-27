<?php

declare(strict_types=1);

namespace Modules\Geo\Tests\Unit\Traits;

use Modules\Geo\Models\BaseModel;
use Modules\Geo\Models\Traits\HasPlaceTrait;
use Modules\Geo\Tests\TestCase;
use Modules\Geo\Traits\HandlesCoordinates;
use Modules\Geo\Traits\HasAddresses;
use PHPUnit\Framework\Assert;

uses(TestCase::class);

test('HasAddresses trait can be used', function (): void {
    $withAddresses = new class() extends BaseModel
    {
        use HasAddresses;

        /** @var list<string> */
        protected $fillable = ['name'];

        public $timestamps = false;

        protected $table = 'test_models';
    };
    $withPlace = new class() extends BaseModel
    {
        use HasPlaceTrait;

        /** @var list<string> */
        protected $fillable = ['name'];

        public $timestamps = false;

        protected $table = 'test_models';
    };

    Assert::assertInstanceOf(BaseModel::class, $withAddresses);
    Assert::assertInstanceOf(BaseModel::class, $withPlace);
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
