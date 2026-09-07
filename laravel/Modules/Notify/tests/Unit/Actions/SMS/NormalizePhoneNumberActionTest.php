<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Actions\SMS;

use Modules\Notify\Actions\SMS\NormalizePhoneNumberAction;
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
=======
>>>>>>> a988596b (first)
describe('SMS\NormalizePhoneNumberAction', function () {
    it('can be instantiated', function () {
        Assert::assertTrue(class_exists(NormalizePhoneNumberAction::class));
    });

    it('has execute method with correct signature', function () {
        $reflection = new \ReflectionClass(NormalizePhoneNumberAction::class);
        $method = $reflection->getMethod('execute');

        Assert::assertTrue($method->isPublic());
        Assert::assertSame(1, $method->getNumberOfParameters());
    });

    it('execute accepts string parameter', function () {
        $reflection = new \ReflectionClass(NormalizePhoneNumberAction::class);
        $method = $reflection->getMethod('execute');
        $params = $method->getParameters();

        XotBasePest::assertReflectionTypeName($params[0]->getType(), 'string');
    });

    it('execute returns string', function () {
        $reflection = new \ReflectionClass(NormalizePhoneNumberAction::class);
        $method = $reflection->getMethod('execute');
        $returnType = $method->getReturnType();

        XotBasePest::assertReflectionTypeName($returnType, 'string');
    });

    it('uses strict types', function () {
        $reflection = new \ReflectionClass(NormalizePhoneNumberAction::class);
        $content = TestCase::notifyReflectionSource($reflection);
        Assert::assertStringContainsString('declare(strict_types=1)', $content);
    });

    it('has correct namespace', function () {
        $reflection = new \ReflectionClass(NormalizePhoneNumberAction::class);

        Assert::assertSame('Modules\Notify\Actions\SMS', $reflection->getNamespaceName());
    });

    it('has required imports', function () {
        $content = TestCase::notifyReflectionSource(new \ReflectionClass(NormalizePhoneNumberAction::class));

        Assert::assertStringContainsString('use Webmozart\Assert\Assert', $content);
        Assert::assertStringContainsString('use function Safe\preg_replace', $content);
    });

    it('is not using QueueableAction trait', function () {
        $traits = class_uses(NormalizePhoneNumberAction::class);

<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertArrayNotHasKey(QueueableAction::class, $traits);
=======
        Assert::assertArrayHasKey(QueueableAction::class, $traits);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        Assert::assertArrayNotHasKey(QueueableAction::class, $traits);
>>>>>>> a988596b (first)
    });
});
