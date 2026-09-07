<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Factories;

use Modules\Notify\Actions\SMS\SendSmsFactorSMSAction;
use Modules\Notify\Contracts\SMS\SmsActionContract;
use Modules\Notify\Contracts\TelegramProviderActionInterface;
use Modules\Notify\Contracts\WhatsAppProviderActionInterface;
use Modules\Notify\Factories\TelegramActionFactory;
use Modules\Notify\Factories\WhatsAppActionFactory;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;
=======
use Modules\Notify\Tests\TestCase;
use PHPUnit\Framework\Assert;
use Modules\Xot\Tests\XotBasePest;

uses(TestCase::class)->group('notify-db');
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;
>>>>>>> a988596b (first)

test('sms action resolves default smsfactor driver instance', function () {
    config()->set('sms.default', 'smsfactor');
    config()->set('sms.drivers.smsfactor.token', 'token-123');

    $action = app(SendSmsFactorSMSAction::class);

    Assert::assertInstanceOf(SmsActionContract::class, $action);
});

test('telegram action factory creates official driver instance', function () {
    config()->set('services.telegram.token', 'telegram-token');

<<<<<<< HEAD
    $factory = new TelegramActionFactory;
=======
    $factory = new TelegramActionFactory();
>>>>>>> a988596b (first)
    $action = $factory->create('official');

    Assert::assertInstanceOf(TelegramProviderActionInterface::class, $action);
});

test('telegram action factory throws for unsupported driver', function () {
    XotBasePest::assertThrows(
<<<<<<< HEAD
        fn () => (new TelegramActionFactory)->create('unsupported'),
=======
        fn () => (new TelegramActionFactory())->create('unsupported'),
>>>>>>> a988596b (first)
        \Exception::class,
    );
});

test('whatsapp action factory creates twilio driver instance', function () {
    config()->set('services.twilio.account_sid', 'sid-123');
    config()->set('services.twilio.auth_token', 'token-123');

<<<<<<< HEAD
    $factory = new WhatsAppActionFactory;
=======
    $factory = new WhatsAppActionFactory();
>>>>>>> a988596b (first)
    $action = $factory->create('twilio');

    Assert::assertInstanceOf(WhatsAppProviderActionInterface::class, $action);
});

test('whatsapp action factory throws for unsupported driver', function () {
    XotBasePest::assertThrows(
<<<<<<< HEAD
        fn () => (new WhatsAppActionFactory)->create('unsupported'),
=======
        fn () => (new WhatsAppActionFactory())->create('unsupported'),
>>>>>>> a988596b (first)
        \Exception::class,
    );
});
