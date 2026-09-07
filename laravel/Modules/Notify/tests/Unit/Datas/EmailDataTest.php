<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Datas;

use Modules\Notify\Datas\EmailData;
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
use Modules\Notify\Tests\TestCase;
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;
use Spatie\LaravelData\Data;
<<<<<<< HEAD
=======
use PHPUnit\Framework\Assert;
use Spatie\LaravelData\Data;
use Modules\Xot\Tests\XotBasePest;
use Modules\Notify\Tests\TestCase;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

describe('EmailData', function () {
    it('can be instantiated via reflection without constructor', function () {
        $reflection = new \ReflectionClass(EmailData::class);

        Assert::assertTrue($reflection->isInstantiable());
    });

    it('has correct namespace', function () {
        Assert::assertStringStartsWith('Modules\Notify\Datas', (string) EmailData::class);
    });

    it('has required properties', function () {
        $propertyNames = TestCase::notifyReflectionPropertyNames(new \ReflectionClass(EmailData::class));

        XotBasePest::assertListContains('recipient', $propertyNames);
        XotBasePest::assertListContains('from', $propertyNames);
        XotBasePest::assertListContains('from_email', $propertyNames);
        XotBasePest::assertListContains('subject', $propertyNames);
        XotBasePest::assertListContains('body_html', $propertyNames);
        XotBasePest::assertListContains('body', $propertyNames);
        XotBasePest::assertListContains('attachments', $propertyNames);
    });

    it('extends Spatie Data', function () {
        $reflection = new \ReflectionClass(EmailData::class);

        Assert::assertTrue($reflection->isSubclassOf(Data::class));
    });

    it('has getFrom method', function () {
        $reflection = new \ReflectionClass(EmailData::class);

        Assert::assertTrue($reflection->hasMethod('getFrom'));
    });

    it('has getMimeEmail method', function () {
        $reflection = new \ReflectionClass(EmailData::class);

        Assert::assertTrue($reflection->hasMethod('getMimeEmail'));
    });

    it('has from method', function () {
        $reflection = new \ReflectionClass(EmailData::class);

        Assert::assertTrue($reflection->hasMethod('from'));
    });

    it('can create via static from method with valid data', function () {
        $reflection = new \ReflectionClass(EmailData::class);
        $fromMethod = $reflection->getMethod('from');
        Assert::assertTrue($fromMethod->isStatic());
    });
});
