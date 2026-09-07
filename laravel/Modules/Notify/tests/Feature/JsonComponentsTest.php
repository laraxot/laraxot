<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Feature;

use Illuminate\Support\Facades\File;
<<<<<<< HEAD
<<<<<<< HEAD
=======
use Modules\Notify\Tests\TestCase;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
use PHPUnit\Framework\Assert;

use function Safe\json_decode;

<<<<<<< HEAD
<<<<<<< HEAD
=======
uses(TestCase::class)->group('no-notify-db');

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
test('components json is valid and contains expected components', function (): void {
    $filePath = base_path('Modules/Notify/app/Console/Commands/_components.json');

    Assert::assertTrue(File::exists($filePath));

<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
    $json = \assertNotifyArray(json_decode(File::get($filePath), true));

    Assert::assertCount(2, $json);

    $first = \assertNotifyArray($json[0] ?? null);
    $second = \assertNotifyArray($json[1] ?? null);

    Assert::assertArrayHasKey('name', $first);
    Assert::assertArrayHasKey('class', $first);
    Assert::assertArrayHasKey('ns', $first);
    Assert::assertArrayHasKey('name', $second);
    Assert::assertArrayHasKey('class', $second);
    Assert::assertArrayHasKey('ns', $second);
<<<<<<< HEAD
=======
    $content = File::get($filePath);
    /** @var array<int, array<string, string>>|null $json */
    $json = json_decode($content, true);

    Assert::assertIsArray($json);
    Assert::assertCount(3, $json);

    foreach ($json as $component) {
        Assert::assertArrayHasKey('name', $component);
        Assert::assertArrayHasKey('class', $component);
        Assert::assertArrayHasKey('ns', $component);
    }
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

    $names = array_column($json, 'name');
    Assert::assertContains('send-mail-command', $names);
    Assert::assertContains('telegram-webhook', $names);
    Assert::assertContains('analyze-translation-files', $names);

    $classes = array_column($json, 'class');
    Assert::assertContains('SendMailCommand', $classes);
    Assert::assertContains('TelegramWebhook', $classes);
    Assert::assertContains('AnalyzeTranslationFiles', $classes);
});
