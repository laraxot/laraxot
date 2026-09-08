<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Console;

use Modules\Notify\Console\Commands\TelegramWebhook;
use PHPUnit\Framework\Assert;

test('telegram webhook command has expected signature and handle returns void', function () {
    $command = new TelegramWebhook;

    Assert::assertSame('telegram:set-webhook', $command->getName());
    $command->handle();
});
