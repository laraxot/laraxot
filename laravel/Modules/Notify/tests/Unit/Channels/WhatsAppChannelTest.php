<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Channels;

use Modules\Notify\Channels\WhatsAppChannel;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Notify\Tests\TestCase;
use PHPUnit\Framework\Assert;
=======
use PHPUnit\Framework\Assert;
use Modules\Notify\Tests\TestCase;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use Modules\Notify\Tests\TestCase;
use PHPUnit\Framework\Assert;
>>>>>>> a988596b (first)

describe('WhatsAppChannel', function () {
    it('can be instantiated', function () {
        // WhatsAppChannel requires WhatsAppActionFactory in constructor
        // but we can test structure via reflection
        $reflection = new \ReflectionClass(WhatsAppChannel::class);
        Assert::assertTrue($reflection->isInstantiable());
    });

    it('has send method', function () {
        $reflection = new \ReflectionClass(WhatsAppChannel::class);
        $method = $reflection->getMethod('send');

        Assert::assertTrue($method->isPublic());
    });

    it('has correct namespace', function () {
        $reflection = new \ReflectionClass(WhatsAppChannel::class);

        Assert::assertSame('Modules\Notify\Channels', $reflection->getNamespaceName());
    });

    it('uses strict types', function () {
        $reflection = new \ReflectionClass(WhatsAppChannel::class);
        $content = TestCase::notifyReflectionSource($reflection);
        Assert::assertStringContainsString('declare(strict_types=1)', $content);
    });

    it('has private factory property', function () {
        $reflection = new \ReflectionClass(WhatsAppChannel::class);
        $property = $reflection->getProperty('factory');

        Assert::assertTrue($property->isPrivate());
    });
});
