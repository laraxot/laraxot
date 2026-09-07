<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Console;

use Modules\Notify\Console\Commands\TelegramWebhook;
<<<<<<< HEAD
<<<<<<< HEAD
use PHPUnit\Framework\Assert;

=======
use Modules\Notify\Tests\TestCase;
use PHPUnit\Framework\Assert;

uses(TestCase::class)->group('no-notify-db');

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
test('telegram webhook command has expected signature and handle returns void', function () {
    $command = new TelegramWebhook;
=======
use PHPUnit\Framework\Assert;

test('telegram webhook command has expected signature and handle returns void', function () {
    $command = new TelegramWebhook();
>>>>>>> a988596b (first)

    Assert::assertSame('telegram:set-webhook', $command->getName());
    $command->handle();
});
