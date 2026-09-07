<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Actions;

use Modules\Notify\Actions\DetermineSeasonalContentViewPathAction;
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

uses(TestCase::class)->group('no-notify-db');

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
describe('DetermineSeasonalContentViewPathAction', function () {
    it('can be instantiated', function () {
        Assert::assertTrue(class_exists(DetermineSeasonalContentViewPathAction::class));
    });

    it('uses QueueableAction trait', function () {
        $traits = class_uses(DetermineSeasonalContentViewPathAction::class);
        Assert::assertArrayHasKey(QueueableAction::class, $traits);
    });

    it('has execute method with correct signature', function () {
        $reflection = new \ReflectionClass(DetermineSeasonalContentViewPathAction::class);
        $method = $reflection->getMethod('execute');

        Assert::assertTrue($method->isPublic());
        Assert::assertSame(1, $method->getNumberOfParameters());
    });

    it('returns string from execute', function () {
        $reflection = new \ReflectionClass(DetermineSeasonalContentViewPathAction::class);
        $method = $reflection->getMethod('execute');
        $returnType = $method->getReturnType();

        XotBasePest::assertReflectionTypeName($returnType, 'string');
    });

    it('has private determineViewFileName method', function () {
        $reflection = new \ReflectionClass(DetermineSeasonalContentViewPathAction::class);
        $method = $reflection->getMethod('determineViewFileName');

        Assert::assertTrue($method->isPrivate());
    });

    it('has private getEasterDate method', function () {
        $reflection = new \ReflectionClass(DetermineSeasonalContentViewPathAction::class);
        $method = $reflection->getMethod('getEasterDate');

        Assert::assertTrue($method->isPrivate());
    });

    it('returns view path with sixteen namespace', function () {
<<<<<<< HEAD
        $action = new DetermineSeasonalContentViewPathAction;
=======
        $action = new DetermineSeasonalContentViewPathAction();
>>>>>>> a988596b (first)
        $result = $action->execute('base-content');

        Assert::assertStringStartsWith('sixteen::emails.', (string) $result);
    });

    it('uses strict types', function () {
        $reflection = new \ReflectionClass(DetermineSeasonalContentViewPathAction::class);
        $content = TestCase::notifyReflectionSource($reflection);
        Assert::assertStringContainsString('declare(strict_types=1)', $content);
    });

    it('has correct namespace', function () {
        $reflection = new \ReflectionClass(DetermineSeasonalContentViewPathAction::class);

        Assert::assertSame('Modules\Notify\Actions', $reflection->getNamespaceName());
    });

    it('has required imports', function () {
        $content = TestCase::notifyReflectionSource(new \ReflectionClass(DetermineSeasonalContentViewPathAction::class));

        Assert::assertStringContainsString('use Carbon\Carbon', $content);
        Assert::assertStringContainsString('use Spatie\QueueableAction\QueueableAction', $content);
        Assert::assertStringContainsString('use Webmozart\Assert\Assert', $content);
    });
});
