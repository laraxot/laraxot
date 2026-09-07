<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Console\Commands;

use Illuminate\Console\Command;
use Modules\Notify\Console\Commands\SendMailCommand;
use PHPUnit\Framework\Assert;

describe('SendMailCommand', function () {
    it('has correct signature', function () {
<<<<<<< HEAD
        $command = new SendMailCommand;
=======
        $command = new SendMailCommand();
>>>>>>> a988596b (first)

        Assert::assertSame('notify:send-mail', $command->getName());
    });

    it('has description', function () {
<<<<<<< HEAD
        $command = new SendMailCommand;
=======
        $command = new SendMailCommand();
>>>>>>> a988596b (first)

        $description = $command->getDescription();

        Assert::assertNotEmpty($description);
    });

    it('extends command', function () {
<<<<<<< HEAD
        $command = new SendMailCommand;
=======
        $command = new SendMailCommand();
>>>>>>> a988596b (first)

        Assert::assertInstanceOf(Command::class, $command);
    });

    it('handle is a public command entrypoint', function () {
<<<<<<< HEAD
        $command = new SendMailCommand;
=======
        $command = new SendMailCommand();
>>>>>>> a988596b (first)
        $method = new \ReflectionMethod($command, 'handle');

        Assert::assertTrue($method->isPublic());
        Assert::assertSame('handle', $method->getName());
    });
});
