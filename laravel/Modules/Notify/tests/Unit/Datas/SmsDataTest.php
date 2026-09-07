<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Datas;

use Modules\Notify\Datas\SmsData;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;
=======
use PHPUnit\Framework\Assert;
use Modules\Xot\Tests\XotBasePest;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;
>>>>>>> a988596b (first)

describe('SmsData', function () {
    it('can be referenced via reflection without instantiation', function () {
        $reflection = new \ReflectionClass(SmsData::class);

        Assert::assertTrue($reflection->isInstantiable());
    });

    it('has correct namespace', function () {
        Assert::assertStringStartsWith('Modules\Notify\Datas', (string) SmsData::class);
    });

    it('has required properties', function () {
        $reflection = new \ReflectionClass(SmsData::class);
        $properties = $reflection->getProperties();

        $propertyNames = array_map(static fn (\ReflectionProperty $p): string => $p->getName(), $properties);

        XotBasePest::assertListContains('from', $propertyNames);
        XotBasePest::assertListContains('recipient', $propertyNames);
        XotBasePest::assertListContains('body', $propertyNames);
    });

    it('has from method', function () {
        $reflection = new \ReflectionClass(SmsData::class);

        Assert::assertTrue($reflection->hasMethod('from'));
    });

    it('from method is static', function () {
        $reflection = new \ReflectionClass(SmsData::class);
        $fromMethod = $reflection->getMethod('from');

        Assert::assertTrue($fromMethod->isStatic());
    });

    it('has constructor', function () {
        $reflection = new \ReflectionClass(SmsData::class);

        Assert::assertNotNull($reflection->getConstructor());
    });

    it('constructor accepts array parameter', function () {
        $reflection = new \ReflectionClass(SmsData::class);
        $constructor = $reflection->getConstructor();
        Assert::assertNotNull($constructor);
        $params = $constructor->getParameters();

        Assert::assertCount(1, $params);
        Assert::assertSame('data', $params[0]->getName());
        $type = $params[0]->getType();
        Assert::assertInstanceOf(\ReflectionNamedType::class, $type);
        Assert::assertSame('array', $type->getName());
    });
});
