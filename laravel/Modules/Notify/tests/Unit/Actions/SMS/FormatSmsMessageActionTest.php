<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Actions\SMS;

use Modules\Notify\Actions\SMS\FormatSmsMessageAction;
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

uses(TestCase::class);

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
describe('FormatSmsMessageAction', function () {
    it('can be instantiated', function () {
        $action = new FormatSmsMessageAction;
=======
describe('FormatSmsMessageAction', function () {
    it('can be instantiated', function () {
        $action = new FormatSmsMessageAction();
>>>>>>> a988596b (first)

        Assert::assertInstanceOf(FormatSmsMessageAction::class, $action);
    });

    it('has execute method with correct signature', function () {
<<<<<<< HEAD
        $action = new FormatSmsMessageAction;
=======
        $action = new FormatSmsMessageAction();
>>>>>>> a988596b (first)

        $reflection = new \ReflectionClass($action);
        $method = $reflection->getMethod('execute');

        Assert::assertTrue($method->isPublic());
        Assert::assertSame(1, $method->getNumberOfParameters());
    });

    it('execute accepts string parameter', function () {
<<<<<<< HEAD
        $action = new FormatSmsMessageAction;
=======
        $action = new FormatSmsMessageAction();
>>>>>>> a988596b (first)

        $reflection = new \ReflectionClass($action);
        $method = $reflection->getMethod('execute');
        $params = $method->getParameters();

        XotBasePest::assertReflectionTypeName($params[0]->getType(), 'string');
    });

    it('execute returns array', function () {
<<<<<<< HEAD
        $action = new FormatSmsMessageAction;
=======
        $action = new FormatSmsMessageAction();
>>>>>>> a988596b (first)

        $reflection = new \ReflectionClass($action);
        $method = $reflection->getMethod('execute');
        $returnType = $method->getReturnType();

        XotBasePest::assertReflectionTypeName($returnType, 'array');
    });

    it('uses strict types', function () {
<<<<<<< HEAD
        $action = new FormatSmsMessageAction;
=======
        $action = new FormatSmsMessageAction();
>>>>>>> a988596b (first)

        $reflection = new \ReflectionClass($action);
        $content = TestCase::notifyReflectionSource($reflection);
        Assert::assertStringContainsString('declare(strict_types=1)', (string) $content);
    });

    it('has correct namespace', function () {
<<<<<<< HEAD
        $action = new FormatSmsMessageAction;
=======
        $action = new FormatSmsMessageAction();
>>>>>>> a988596b (first)

        $reflection = new \ReflectionClass($action);

        Assert::assertSame('Modules\Notify\Actions\SMS', $reflection->getNamespaceName());
    });

    it('has required imports', function () {
<<<<<<< HEAD
        $action = new FormatSmsMessageAction;
=======
        $action = new FormatSmsMessageAction();
>>>>>>> a988596b (first)

        $reflection = new \ReflectionClass($action);
        $content = TestCase::notifyReflectionSource($reflection);
        Assert::assertStringContainsString('declare(strict_types=1)', $content);
    });

    it('is not using QueueableAction trait', function () {
<<<<<<< HEAD
        $action = new FormatSmsMessageAction;
=======
        $action = new FormatSmsMessageAction();
>>>>>>> a988596b (first)

        $traits = class_uses(FormatSmsMessageAction::class);

        Assert::assertArrayHasKey(QueueableAction::class, $traits);
    });
});
