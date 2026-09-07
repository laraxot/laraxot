<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Notifications\Channels;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;
use Modules\Notify\Actions\SMS\SendSmsFactorSMSAction;
use Modules\Notify\Contracts\CanThemeNotificationContract;
use Modules\Notify\Contracts\SMS\SmsActionContract;
use Modules\Notify\Datas\SmsData;
use Modules\Notify\Notifications\Channels\NetfunChannel;
use Modules\Notify\Notifications\Channels\TelegramChannel;
use Modules\Notify\Notifications\ThemeNotification;
use Modules\Notify\Tests\Fixtures\NetfunChannelNotifiableDummy;
use Modules\Notify\Tests\TestCase;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;
=======
use PHPUnit\Framework\Assert;
use Modules\Xot\Tests\XotBasePest;

uses(TestCase::class)->group('no-notify-db');
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;
>>>>>>> a988596b (first)

function makeThemeNotificationDummy(): ThemeNotification
{
    return new class('name', []) extends ThemeNotification
    {
        public function toSms(CanThemeNotificationContract $notifiable): SmsData
        {
            return SmsData::from([
                'from' => 'Xot',
                'recipient' => '+391234567890',
<<<<<<< HEAD
<<<<<<< HEAD
                'body' => 'Body']);
=======
                'body' => 'Body',
            ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'body' => 'Body']);
>>>>>>> a988596b (first)
        }
    };
}

function makeTelegramNotificationDummy(): Notification
{
<<<<<<< HEAD
    return new class extends Notification
=======
    return new class() extends Notification
>>>>>>> a988596b (first)
    {
        /** @return array{text: string} */
        public function toTelegram(object $notifiable): array
        {
            return ['text' => 'hello'];
        }
    };
}

function makeTelegramNotifiableDummy(): object
{
<<<<<<< HEAD
    return new class
=======
    return new class()
>>>>>>> a988596b (first)
    {
        public function routeNotificationForTelegram(): string
        {
            return 'chat-123';
        }
    };
}

test('netfun notifications channel sends and increases counter', function () {
    config()->set('sms.default', 'smsfactor');
    config()->set('sms.drivers.smsfactor.token', 'token-123');

<<<<<<< HEAD
    app()->instance(SendSmsFactorSMSAction::class, new class implements SmsActionContract
=======
    app()->instance(SendSmsFactorSMSAction::class, new class() implements SmsActionContract
>>>>>>> a988596b (first)
    {
        /** @return array{status_code: int, status_txt: string} */
        public function execute(SmsData $smsData): array
        {
            return ['status_code' => 200, 'status_txt' => 'ok'];
        }
    });

<<<<<<< HEAD
    $channel = new NetfunChannel;
    $notifiable = new NetfunChannelNotifiableDummy;
=======
    $channel = new NetfunChannel();
    $notifiable = new NetfunChannelNotifiableDummy();
>>>>>>> a988596b (first)
    $notification = makeThemeNotificationDummy();

    $channel->send($notifiable, $notification);

    Assert::assertArrayHasKey('sms', $notifiable->increased);
    Assert::assertSame(200, TestCase::notifyArrayGet($notifiable->increased, 'sms', 'status_code'));
});

test('telegram notifications channel logs when recipient and method are valid', function () {
    Log::shouldReceive('debug')->once()->withArgs(function (string $message, array $context): bool {
        return str_contains($message, 'Telegram') && isset($context['chat_id']);
    });
    Log::shouldReceive('info')->zeroOrMoreTimes();

<<<<<<< HEAD
    $channel = new TelegramChannel;
=======
    $channel = new TelegramChannel();
>>>>>>> a988596b (first)
    $channel->send(makeTelegramNotifiableDummy(), makeTelegramNotificationDummy());
});

test('telegram notifications channel throws when notification has no toTelegram method', function () {
<<<<<<< HEAD
    $channel = new TelegramChannel;

    XotBasePest::assertThrows(
        fn () => $channel->send(makeTelegramNotifiableDummy(), new class extends Notification {}),
=======
    $channel = new TelegramChannel();

    XotBasePest::assertThrows(
        fn () => $channel->send(makeTelegramNotifiableDummy(), new class() extends Notification {}),
>>>>>>> a988596b (first)
        \Exception::class,
    );
});
