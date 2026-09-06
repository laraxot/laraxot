<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Modules\Cms\Models\Conf;
use Modules\Cms\Tests\TestCase;
use PHPUnit\Framework\Assert;

use function Safe\class_uses;

use Sushi\Sushi;

uses(TestCase::class);
describe('Conf Business Logic', function (): void {
    test('conf extends eloquent model', function (): void {
        Assert::assertTrue(
            (new ReflectionClass(Conf::class))->isSubclassOf(Model::class),
        );
    });

    test('conf uses sushi trait for in-memory data', function (): void {
        $traits = class_uses(Conf::class);

        Assert::assertArrayHasKey(Sushi::class, $traits);
    });

    test('conf has expected fillable fields', function (): void {
        $conf = new Conf;
        $expectedFillable = [
            'id',
            'name',
        ];

        Assert::assertEquals($expectedFillable, $conf->getFillable());
    });

    test('conf uses name as route key', function (): void {
        $conf = new Conf;

        Assert::assertSame('name', $conf->getRouteKeyName());
    });

    test('conf can get rows from tenant service', function (): void {
        $conf = new Conf;

        Assert::assertNotEmpty($conf->getRows());
    });
});
