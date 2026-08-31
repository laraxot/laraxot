<?php

declare(strict_types=1);

namespace Modules\Geo\Tests\Unit\Models;

use Modules\Geo\Database\Factories\ComuneFactory;
use Modules\Geo\Models\BaseModel;
use Modules\Geo\Models\Comune;
use Modules\Tenant\Models\Traits\SushiToJson;
use PHPUnit\Framework\Assert;

use function Safe\class_uses;

describe('Comune Business Logic', function () {
    test('comune extends base model', function () {
        Assert::assertInstanceOf(BaseModel::class, new Comune());
    });

    test('comune can be created via factory', function () {
        $comune = ComuneFactory::new()->createOne();

        Assert::assertInstanceOf(Comune::class, $comune);
    });

    test('comune has sushi to json trait', function () {
        $traits = class_uses(Comune::class);

        Assert::assertArrayHasKey(SushiToJson::class, $traits);
    });

    test('comune has expected fillable fields for italian municipalities', function () {
        $comune = new Comune();
        $expectedFillable = [
            'id',
            'codice',
            'nome',
            'regione',
            'provincia',
            'sigla_provincia',
            'cap',
            'codice_catastale',
            'popolazione',
            'zona_altimetrica',
            'altitudine',
            'superficie',
            'lat',
            'lng',
        ];

        Assert::assertEquals($expectedFillable, $comune->getFillable());
    });

    test('comune has schema definition for structured geographic data', function () {
        $comune = new Comune();
        $reflection = new \ReflectionClass($comune);
        $schemaProperty = $reflection->getProperty('schema');

        Assert::assertTrue($schemaProperty->isProtected());

        $schema = $schemaProperty->getValue($comune);
        Assert::assertIsArray($schema);
        /* @var array<string, mixed> $schema */
        Assert::assertSame('json', $schema['zona']);
        Assert::assertSame('json', $schema['provincia']);
        Assert::assertSame('json', $schema['regione']);
        Assert::assertSame('json', $schema['cap']);
    });

    test('comune has json directory property for data source', function () {
        $comune = new Comune();

        Assert::assertObjectHasProperty('jsonDirectory', $comune);
    });

    test('comune has translatable array configured', function () {
        $comune = new Comune();

        Assert::assertIsArray($comune->translatable);
    });

    test('comune model can be instantiated without errors', function () {
        $comune = new Comune();

        Assert::assertInstanceOf(Comune::class, $comune);
        Assert::assertInstanceOf(BaseModel::class, $comune);
    });
});
