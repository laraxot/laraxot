<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Actions\NotifyTheme;

use Modules\Notify\Actions\NotifyTheme\Get;
use Modules\Notify\Datas\NotifyThemeData;
use Modules\Notify\Tests\TestCase;
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;
use Spatie\QueueableAction\QueueableAction;

use function Safe\class_uses;

<<<<<<< HEAD
=======
use PHPUnit\Framework\Assert;
use Spatie\QueueableAction\QueueableAction;
use Modules\Xot\Tests\XotBasePest;

use function Safe\class_uses;

uses(TestCase::class)->group('notify-db');

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
describe('NotifyTheme\Get', function () {
    it('can be instantiated', function () {
        Assert::assertTrue(class_exists(Get::class));
    });

    it('uses QueueableAction trait', function () {
        $traits = class_uses(Get::class);
        Assert::assertArrayHasKey(QueueableAction::class, $traits);
    });

    it('has execute method with correct signature', function () {
        $reflection = new \ReflectionClass(Get::class);
        $method = $reflection->getMethod('execute');

        Assert::assertTrue($method->isPublic());
        Assert::assertSame(3, $method->getNumberOfParameters());
    });

    it('execute accepts string parameters and array', function () {
        $reflection = new \ReflectionClass(Get::class);
        $method = $reflection->getMethod('execute');
        $params = $method->getParameters();

        XotBasePest::assertReflectionTypeName($params[0]->getType(), 'string');
        XotBasePest::assertReflectionTypeName($params[1]->getType(), 'string');
        XotBasePest::assertReflectionTypeName($params[2]->getType(), 'array');
    });

    it('execute returns NotifyThemeData', function () {
        $reflection = new \ReflectionClass(Get::class);
        $method = $reflection->getMethod('execute');
        $returnType = $method->getReturnType();

        XotBasePest::assertReflectionTypeName($returnType, NotifyThemeData::class);
    });

    it('uses strict types', function () {
        $reflection = new \ReflectionClass(Get::class);
        $content = TestCase::notifyReflectionSource($reflection);
        Assert::assertStringContainsString('declare(strict_types=1)', $content);
    });

    it('has correct namespace', function () {
        $reflection = new \ReflectionClass(Get::class);

        Assert::assertSame('Modules\Notify\Actions\NotifyTheme', $reflection->getNamespaceName());
    });

    it('has required imports', function () {
        $content = TestCase::notifyReflectionSource(new \ReflectionClass(Get::class));

        Assert::assertStringContainsString('use Modules\Notify\Datas\NotifyThemeData', $content);
        Assert::assertStringContainsString('use Modules\Notify\Models\NotifyTheme', $content);
        Assert::assertStringContainsString('use Modules\Xot\Datas\XotData', $content);
    });

    it('implements queueable functionality', function () {
        $reflection = new \ReflectionClass(Get::class);
        Assert::assertTrue($reflection->hasMethod('onQueue'));
    });
});
