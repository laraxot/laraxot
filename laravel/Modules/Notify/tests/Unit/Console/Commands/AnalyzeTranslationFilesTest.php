<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Console\Commands;

use Illuminate\Console\Command;
use Modules\Notify\Console\Commands\AnalyzeTranslationFiles;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;
=======
use Modules\Notify\Tests\TestCase;
use PHPUnit\Framework\Assert;
use Modules\Xot\Tests\XotBasePest;

uses(TestCase::class)->group('no-notify-db');
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])

describe('AnalyzeTranslationFiles', function () {
    it('has correct signature', function () {
        $command = new AnalyzeTranslationFiles;
=======
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;

describe('AnalyzeTranslationFiles', function () {
    it('has correct signature', function () {
        $command = new AnalyzeTranslationFiles();
>>>>>>> a988596b (first)

        Assert::assertSame('notify:analyze-translations', $command->getName());
    });

    it('has description', function () {
<<<<<<< HEAD
        $command = new AnalyzeTranslationFiles;
=======
        $command = new AnalyzeTranslationFiles();
>>>>>>> a988596b (first)

        Assert::assertNotEmpty($command->getDescription());
    });

    it('extends command', function () {
<<<<<<< HEAD
        $command = new AnalyzeTranslationFiles;
=======
        $command = new AnalyzeTranslationFiles();
>>>>>>> a988596b (first)

        Assert::assertInstanceOf(Command::class, $command);
    });

    it('has handle method', function () {
        $reflection = new \ReflectionClass(AnalyzeTranslationFiles::class);

        Assert::assertTrue($reflection->hasMethod('handle'));
    });

    it('has flatten array method', function () {
        $reflection = new \ReflectionClass(AnalyzeTranslationFiles::class);

        Assert::assertTrue($reflection->hasMethod('flattenArray'));
    });

    it('has analyze structure patterns method', function () {
        $reflection = new \ReflectionClass(AnalyzeTranslationFiles::class);

        Assert::assertTrue($reflection->hasMethod('analyzeStructurePatterns'));
    });

    it('has generate consistency report method', function () {
        $reflection = new \ReflectionClass(AnalyzeTranslationFiles::class);

        Assert::assertTrue($reflection->hasMethod('generateConsistencyReport'));
    });

    it('has generate recommendations method', function () {
        $reflection = new \ReflectionClass(AnalyzeTranslationFiles::class);

        Assert::assertTrue($reflection->hasMethod('generateRecommendations'));
    });

    it('has analyze navigation structure method', function () {
        $reflection = new \ReflectionClass(AnalyzeTranslationFiles::class);

        Assert::assertTrue($reflection->hasMethod('analyzeNavigationStructure'));
    });

    it('flatten array handles nested arrays', function () {
<<<<<<< HEAD
        $command = new AnalyzeTranslationFiles;
=======
        $command = new AnalyzeTranslationFiles();
>>>>>>> a988596b (first)

        $reflection = new \ReflectionClass($command);
        $method = $reflection->getMethod('flattenArray');
        $method->setAccessible(true);

        $input = [
            'parent' => [
                'child1' => 'value1',
<<<<<<< HEAD
<<<<<<< HEAD
                'child2' => 'value2']];
=======
                'child2' => 'value2',
            ],
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'child2' => 'value2']];
>>>>>>> a988596b (first)

        $result = XotBasePest::assertArray($method->invoke($command, $input));

        Assert::assertArrayHasKey('parent.child1', $result);
        Assert::assertArrayHasKey('parent.child2', $result);
        Assert::assertSame('value1', $result['parent.child1']);
    });

    it('flatten array handles empty array', function () {
<<<<<<< HEAD
        $command = new AnalyzeTranslationFiles;
=======
        $command = new AnalyzeTranslationFiles();
>>>>>>> a988596b (first)

        $reflection = new \ReflectionClass($command);
        $method = $reflection->getMethod('flattenArray');
        $method->setAccessible(true);

        $result = XotBasePest::assertArray($method->invoke($command, []));

        Assert::assertEmpty($result);
    });

    it('flatten array handles nested levels', function () {
<<<<<<< HEAD
        $command = new AnalyzeTranslationFiles;
=======
        $command = new AnalyzeTranslationFiles();
>>>>>>> a988596b (first)

        $reflection = new \ReflectionClass($command);
        $method = $reflection->getMethod('flattenArray');
        $method->setAccessible(true);

        $input = [
            'level1' => [
                'level2' => [
<<<<<<< HEAD
<<<<<<< HEAD
                    'level3' => 'deep value']]];
=======
                    'level3' => 'deep value',
                ],
            ],
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                    'level3' => 'deep value']]];
>>>>>>> a988596b (first)

        $result = XotBasePest::assertArray($method->invoke($command, $input));

        Assert::assertArrayHasKey('level1.level2.level3', $result);
        Assert::assertSame('deep value', $result['level1.level2.level3']);
    });

    it('flatten array handles prefix parameter', function () {
<<<<<<< HEAD
        $command = new AnalyzeTranslationFiles;
=======
        $command = new AnalyzeTranslationFiles();
>>>>>>> a988596b (first)

        $reflection = new \ReflectionClass($command);
        $method = $reflection->getMethod('flattenArray');
        $method->setAccessible(true);

        $input = ['key' => 'value'];

        $result = XotBasePest::assertArray($method->invoke($command, $input, 'prefix'));

        Assert::assertArrayHasKey('prefix.key', $result);
    });
});
