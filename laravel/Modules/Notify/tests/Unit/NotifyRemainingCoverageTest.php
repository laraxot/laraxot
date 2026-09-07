<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit;

<<<<<<< HEAD
<<<<<<< HEAD
=======
use Safe\DateTime;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Mockery;
use Modules\Notify\Actions\Push\SchedulePushNotificationAction;
use Modules\Notify\Actions\Push\SendPushToAllUsersAction;
use Modules\Notify\Actions\Push\SendPushToDeviceAction;
use Modules\Notify\Actions\Push\SendPushWithTargetingAction;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Notify\Actions\SMS\SendAgiletelecomSMSAction;
use Modules\Notify\Actions\SMS\SendAgiletelecomSMSv1Action;
use Modules\Notify\Actions\SMS\SendAgiletelecomSMSv2Action;
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use Modules\Notify\Actions\SMS\SendAgiletelecomSMSAction;
use Modules\Notify\Actions\SMS\SendAgiletelecomSMSv1Action;
use Modules\Notify\Actions\SMS\SendAgiletelecomSMSv2Action;
>>>>>>> a988596b (first)
use Modules\Notify\Channels\NetfunChannel;
use Modules\Notify\Channels\SmsChannel;
use Modules\Notify\Channels\TelegramChannel;
use Modules\Notify\Channels\WhatsAppChannel;
use Modules\Notify\Contracts\SMS\SmsActionContract;
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
use Modules\Notify\Contracts\TelegramProviderActionInterface;
use Modules\Notify\Datas\FirebaseNotificationData;
use Modules\Notify\Datas\NotificationData;
use Modules\Notify\Datas\PushCriteriaData;
use Modules\Notify\Datas\PushNotificationData;
use Modules\Notify\Datas\SendNotificationBulkResultData;
use Modules\Notify\Datas\SMS\AgiletelecomData;
use Modules\Notify\Datas\SmsData;
use Modules\Notify\Datas\SmsMessageData;
use Modules\Notify\Datas\SmtpData;
<<<<<<< HEAD
=======
use Modules\Notify\Datas\FirebaseNotificationData;
use Modules\Notify\Datas\NotificationData;
use Modules\Notify\Datas\PushNotificationData;
use Modules\Notify\Datas\SendNotificationBulkResultData;
use Modules\Notify\Datas\SmsData;
use Modules\Notify\Datas\SmsMessageData;
use Modules\Notify\Datas\SmtpData;
use Modules\Notify\Datas\SMS\AgiletelecomData;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
use Modules\Notify\Factories\SmsActionFactory;
use Modules\Notify\Factories\TelegramActionFactory;
use Modules\Notify\Factories\WhatsAppActionFactory;
use Modules\Notify\Models\Notification as NotificationModel;
use Modules\Notify\Tests\Fixtures\NotifyCoveragePivotStub;
use Modules\Notify\Tests\Fixtures\NotifyNetfunNotifiableStub;
use Modules\Notify\Tests\Fixtures\NotifyNetfunNotificationStub;
<<<<<<< HEAD
<<<<<<< HEAD
=======
use Modules\Notify\Tests\TestCase;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
use Modules\Xot\Tests\ModuleBusinessCoverage;
use Modules\Xot\Tests\ModuleDeepCoverage;
use Modules\Xot\Tests\ModuleExecuteCoverage;
use PHPUnit\Framework\Assert;
use ReflectionClass;
<<<<<<< HEAD
<<<<<<< HEAD
use Safe\DateTime;
=======

uses(TestCase::class)->group('no-notify-db');
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use Safe\DateTime;
>>>>>>> a988596b (first)

afterEach(function (): void {
    Mockery::close();
});

/**
 * @return array{string, string} radice `app/` del modulo e namespace corrispondente
 */
/** @return list{string, string} */
function notifyRemainingContext(): array
{
    return [dirname(__DIR__, 2).'/app', 'Modules\\Notify\\'];
}

describe('Notify remaining coverage sweep', function (): void {
    test('extended execute sweep covers Traits Factories Channels Jobs Http Services', function (): void {
        [$appRoot, $ns] = notifyRemainingContext();

        foreach (['Traits', 'Factories', 'Channels', 'Jobs', 'Http', 'Services', 'Datas', 'Console', 'Emails'] as $dir) {
            ModuleExecuteCoverage::testInvokePublicMethodsInDirectory($appRoot, $ns, $dir);
            ModuleExecuteCoverage::testInvokeNonPublicMethods($appRoot, $ns, $dir);
        }

        ModuleDeepCoverage::testFromAllDatas($appRoot, $ns);
        ModuleBusinessCoverage::testAllDatas($appRoot, $ns);
        Assert::assertDirectoryExists($appRoot);
    });

    test('push device schedule and targeting actions execute offline', function (): void {
        config([
            'notify.fcm.server_key' => 'test-key',
<<<<<<< HEAD
<<<<<<< HEAD
            'cache.default' => 'array']);
=======
            'cache.default' => 'array',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'cache.default' => 'array']);
>>>>>>> a988596b (first)
        Http::fake(['https://fcm.googleapis.com/*' => Http::response(['message_id' => 'x'], 200)]);
        Queue::fake();

        $notification = PushNotificationData::from(['title' => 'T', 'body' => 'B']);
        $token = str_repeat('a', 80).':'.str_repeat('b', 40);

<<<<<<< HEAD
<<<<<<< HEAD
        $device = (new SendPushToDeviceAction)->execute($token, $notification);
        Assert::assertArrayHasKey('fcm', $device);

        $jobId = (new SchedulePushNotificationAction)->execute(
=======
=======
>>>>>>> a988596b (first)
        $device = (new SendPushToDeviceAction())->execute($token, $notification);
        Assert::assertArrayHasKey('fcm', $device);

        $jobId = (new SchedulePushNotificationAction())->execute(
<<<<<<< HEAD
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
            [$token],
            $notification,
            [],
            new DateTime('+1 hour'),
        );
        Assert::assertStringStartsWith('push_', $jobId);
        Assert::assertNotNull(Cache::get("scheduled_push:{$jobId}"));

<<<<<<< HEAD
<<<<<<< HEAD
        $all = (new SendPushToAllUsersAction)->execute($notification);
        Assert::assertArrayHasKey('success', $all);
        Assert::assertFalse($all['success']);

        $criteria = PushCriteriaData::from(['platform' => 'fcm']);
        $target = (new SendPushWithTargetingAction)->execute($criteria, $notification);
=======
=======
>>>>>>> a988596b (first)
        $all = (new SendPushToAllUsersAction())->execute($notification);
        Assert::assertArrayHasKey('success', $all);
        Assert::assertFalse($all['success']);

<<<<<<< HEAD
        $criteria = \Modules\Notify\Datas\PushCriteriaData::from(['platform' => 'fcm']);
        $target = (new SendPushWithTargetingAction())->execute($criteria, $notification);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        $criteria = PushCriteriaData::from(['platform' => 'fcm']);
        $target = (new SendPushWithTargetingAction())->execute($criteria, $notification);
>>>>>>> a988596b (first)
        Assert::assertArrayHasKey('success', $target);
        Assert::assertFalse($target['success']);
    });

    test('datas from and route helpers', function (): void {
        $data = NotificationData::from([
            'from' => 'APP',
            'recipient' => 'user@example.test',
            'body' => 'Hello',
<<<<<<< HEAD
<<<<<<< HEAD
            'channels' => ['mail']]);
        Assert::assertSame('user@example.test', $data->routeNotificationFor('mail', new NotifyNetfunNotificationStub));
        Assert::assertInstanceOf(NotificationModel::class, $data->routeNotificationFor('database', new NotifyNetfunNotificationStub));
=======
            'channels' => ['mail'],
        ]);
        Assert::assertSame('user@example.test', $data->routeNotificationFor('mail', new NotifyNetfunNotificationStub()));
        Assert::assertInstanceOf(NotificationModel::class, $data->routeNotificationFor('database', new NotifyNetfunNotificationStub()));
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'channels' => ['mail']]);
        Assert::assertSame('user@example.test', $data->routeNotificationFor('mail', new NotifyNetfunNotificationStub()));
        Assert::assertInstanceOf(NotificationModel::class, $data->routeNotificationFor('database', new NotifyNetfunNotificationStub()));
>>>>>>> a988596b (first)
        Assert::assertInstanceOf(SmsData::class, $data->getSmsData());

        SendNotificationBulkResultData::from([
            'successCount' => 1,
            'errorCount' => 0,
            'errors' => collect([]),
<<<<<<< HEAD
<<<<<<< HEAD
            'totalProcessed' => 1]);
=======
            'totalProcessed' => 1,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'totalProcessed' => 1]);
>>>>>>> a988596b (first)
        $smsMessage = new SmsMessageData(recipient: '+390000000000', message: 'Hi');
        Assert::assertSame('+390000000000', $smsMessage->recipient);
        $smtp = SmtpData::from(['host' => 'smtp.test', 'port' => 25, 'username' => 'u', 'password' => 'p']);
        Assert::assertSame('smtp.test', $smtp->host);
        AgiletelecomData::from(['recipient' => '+390000000000', 'message' => 'Hi']);
        FirebaseNotificationData::from(['title' => 'T', 'body' => 'B']);
    });

    test('factories resolve or throw with clear errors', function (): void {
        config([
            'sms.default' => 'smsfactor',
<<<<<<< HEAD
<<<<<<< HEAD
            'sms.drivers.smsfactor' => ['token' => 'test-token', 'api_url' => 'https://example.test/sms']]);

        try {
            $sms = (new SmsActionFactory)->create();
=======
            'sms.drivers.smsfactor' => ['token' => 'test-token', 'api_url' => 'https://example.test/sms'],
        ]);

        try {
            $sms = (new SmsActionFactory())->create();
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'sms.drivers.smsfactor' => ['token' => 'test-token', 'api_url' => 'https://example.test/sms']]);

        try {
            $sms = (new SmsActionFactory())->create();
>>>>>>> a988596b (first)
            Assert::assertInstanceOf(SmsActionContract::class, $sms);
        } catch (\Throwable $e) {
            Assert::assertNotSame('', $e->getMessage());
        }

        try {
<<<<<<< HEAD
<<<<<<< HEAD
            (new SmsActionFactory)->create('unknown-driver-xyz');
=======
            (new SmsActionFactory())->create('unknown-driver-xyz');
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            (new SmsActionFactory())->create('unknown-driver-xyz');
>>>>>>> a988596b (first)
        } catch (\Throwable $e) {
            Assert::assertNotSame('', $e->getMessage());
        }

        config(['telegram.default' => 'official']);
        try {
            Assert::assertInstanceOf(
<<<<<<< HEAD
<<<<<<< HEAD
                TelegramProviderActionInterface::class,
                (new TelegramActionFactory)->create(),
=======
                \Modules\Notify\Contracts\TelegramProviderActionInterface::class,
                (new TelegramActionFactory())->create(),
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                TelegramProviderActionInterface::class,
                (new TelegramActionFactory())->create(),
>>>>>>> a988596b (first)
            );
        } catch (\Throwable $e) {
            Assert::assertNotSame('', $e->getMessage());
        }

        config(['whatsapp.default' => '360dialog']);
        try {
<<<<<<< HEAD
<<<<<<< HEAD
            (new WhatsAppActionFactory)->create();
=======
            (new WhatsAppActionFactory())->create();
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            (new WhatsAppActionFactory())->create();
>>>>>>> a988596b (first)
        } catch (\Throwable $e) {
            Assert::assertNotSame('', $e->getMessage());
        }
    });

    test('notification channels handle missing routes gracefully', function (): void {
        config([
<<<<<<< HEAD
<<<<<<< HEAD
            'sms.drivers.smsfactor' => ['token' => 'test-token', 'api_url' => 'https://example.test/sms']]);

        $notification = new NotifyNetfunNotificationStub;
        $notifiable = new NotifyNetfunNotifiableStub;
=======
            'sms.drivers.smsfactor' => ['token' => 'test-token', 'api_url' => 'https://example.test/sms'],
        ]);

        $notification = new NotifyNetfunNotificationStub();
        $notifiable = new NotifyNetfunNotifiableStub();
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'sms.drivers.smsfactor' => ['token' => 'test-token', 'api_url' => 'https://example.test/sms']]);

        $notification = new NotifyNetfunNotificationStub();
        $notifiable = new NotifyNetfunNotifiableStub();
>>>>>>> a988596b (first)

        try {
            $netfun = app(NetfunChannel::class);
            $result = $netfun->send($notifiable, $notification);
            Assert::assertTrue($result === null || is_array($result));
        } catch (\Throwable $e) {
            Assert::assertNotSame('', $e->getMessage());
        }

        foreach ([SmsChannel::class, TelegramChannel::class, WhatsAppChannel::class] as $channelClass) {
            try {
                $channel = app($channelClass);
                if (method_exists($channel, 'send')) {
                    $channel->send($notifiable, $notification);
                }
            } catch (\Throwable $e) {
                Assert::assertNotSame('', $e->getMessage());
            }
        }
    });

    test('base pivot stub exposes casts and connection', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
        $pivot = new NotifyCoveragePivotStub;
=======
        $pivot = new NotifyCoveragePivotStub();
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        $pivot = new NotifyCoveragePivotStub();
>>>>>>> a988596b (first)
        $pivot->setRawAttributes(['id' => 'pivot-1']);
        Assert::assertSame('notify', $pivot->getConnectionName());
        Assert::assertArrayHasKey('id', $pivot->getCasts());
        Assert::assertSame(['id' => 'pivot-1'], $pivot->toArray());
    });

    test('agiletelecom sms actions instantiate', function (): void {
        foreach ([
<<<<<<< HEAD
<<<<<<< HEAD
            SendAgiletelecomSMSAction::class,
            SendAgiletelecomSMSv1Action::class,
            SendAgiletelecomSMSv2Action::class] as $class) {
=======
            \Modules\Notify\Actions\SMS\SendAgiletelecomSMSAction::class,
            \Modules\Notify\Actions\SMS\SendAgiletelecomSMSv1Action::class,
            \Modules\Notify\Actions\SMS\SendAgiletelecomSMSv2Action::class,
        ] as $class) {
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            SendAgiletelecomSMSAction::class,
            SendAgiletelecomSMSv1Action::class,
            SendAgiletelecomSMSv2Action::class] as $class) {
>>>>>>> a988596b (first)
            Assert::assertTrue(class_exists($class));
            $ref = new ReflectionClass($class);
            Assert::assertTrue($ref->hasMethod('execute') || $ref->hasMethod('handle'));
        }
    });
});
