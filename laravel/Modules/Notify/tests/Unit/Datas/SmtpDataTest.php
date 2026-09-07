<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Datas;

use Modules\Notify\Datas\SmtpData;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;
use Spatie\LaravelData\Data;
=======
use PHPUnit\Framework\Assert;
use Spatie\LaravelData\Data;
use Modules\Xot\Tests\XotBasePest;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;
use Spatie\LaravelData\Data;
>>>>>>> a988596b (first)

describe('SmtpData', function () {
    it('can be referenced via reflection without instantiation', function () {
        $reflection = new \ReflectionClass(SmtpData::class);

        Assert::assertTrue($reflection->isInstantiable());
    });

    it('has correct namespace', function () {
        Assert::assertStringStartsWith('Modules\Notify\Datas', (string) SmtpData::class);
    });

    it('extends Spatie Data', function () {
        $reflection = new \ReflectionClass(SmtpData::class);

        Assert::assertTrue($reflection->isSubclassOf(Data::class));
    });

    it('has required properties', function () {
        $reflection = new \ReflectionClass(SmtpData::class);
        $properties = $reflection->getProperties();

        $propertyNames = array_map(static fn (\ReflectionProperty $p): string => $p->getName(), $properties);

        XotBasePest::assertListContains('transport', $propertyNames);
        XotBasePest::assertListContains('host', $propertyNames);
        XotBasePest::assertListContains('port', $propertyNames);
        XotBasePest::assertListContains('username', $propertyNames);
        XotBasePest::assertListContains('password', $propertyNames);
    });

    it('has make static method', function () {
        $reflection = new \ReflectionClass(SmtpData::class);

        Assert::assertTrue($reflection->hasMethod('make'));
    });

    it('has toArray method', function () {
        $reflection = new \ReflectionClass(SmtpData::class);

        Assert::assertTrue($reflection->hasMethod('toArray'));
    });

    it('has getTransport method', function () {
        $reflection = new \ReflectionClass(SmtpData::class);

        Assert::assertTrue($reflection->hasMethod('getTransport'));
    });

    it('has send method', function () {
        $reflection = new \ReflectionClass(SmtpData::class);

        Assert::assertTrue($reflection->hasMethod('send'));
    });

    it('has getMailer method', function () {
        $reflection = new \ReflectionClass(SmtpData::class);

        Assert::assertTrue($reflection->hasMethod('getMailer'));
    });
});
