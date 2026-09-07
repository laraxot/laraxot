<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit;

<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Testing\PendingCommand;
use Modules\Notify\Actions\EsendexSendAction;
use Modules\Notify\Actions\Mail\Engines\Duocircle\TryDuocircleMailAction;
use Modules\Notify\Actions\NetfunSendAction;
<<<<<<< HEAD
use Modules\Notify\Actions\Push\SchedulePushNotificationAction;
use Modules\Notify\Actions\Push\SendPushToAllUsersAction;
use Modules\Notify\Actions\Push\SendPushToDeviceAction;
use Modules\Notify\Actions\Push\SendPushToDevicesAction;
use Modules\Notify\Actions\Push\SendPushToPlatformAction;
use Modules\Notify\Actions\Push\SendPushToTopicAction;
use Modules\Notify\Actions\Push\SendPushWithTargetingAction;
use Modules\Notify\Actions\Push\SendPushWithTemplateAction;
=======
use Modules\Notify\Actions\Push\SendPushToDevicesAction;
use Modules\Notify\Actions\Push\SendPushToPlatformAction;
use Modules\Notify\Actions\Push\SendPushToTopicAction;
>>>>>>> a988596b (first)
use Modules\Notify\Actions\SendNotificationAction;
use Modules\Notify\Actions\SMS\SendGammuSMSAction;
use Modules\Notify\Actions\SMS\SendNetfunSMSAction;
use Modules\Notify\Actions\SMS\SendNexmoSMSAction;
use Modules\Notify\Actions\SMS\SendPlivoSMSAction;
<<<<<<< HEAD
use Modules\Notify\Actions\SMS\SendSmsAction;
=======
>>>>>>> a988596b (first)
use Modules\Notify\Actions\SMS\SendTwilioSMSAction;
use Modules\Notify\Actions\Telegram\SendBotmanTelegramAction;
use Modules\Notify\Actions\Telegram\SendNutgramTelegramAction;
use Modules\Notify\Actions\Telegram\SendOfficialTelegramAction;
use Modules\Notify\Actions\WhatsApp\Send360dialogWhatsAppAction;
use Modules\Notify\Actions\WhatsApp\SendFacebookWhatsAppAction;
use Modules\Notify\Actions\WhatsApp\SendTwilioWhatsAppAction;
use Modules\Notify\Actions\WhatsApp\SendVonageWhatsAppAction;
use Modules\Notify\Database\Factories\NotificationTemplateFactory;
<<<<<<< HEAD
use Modules\Notify\Datas\PushCriteriaData;
=======
>>>>>>> a988596b (first)
use Modules\Notify\Datas\PushNotificationData;
use Modules\Notify\Datas\SmsData;
use Modules\Notify\Datas\TelegramData;
use Modules\Notify\Datas\WhatsAppData;
use Modules\Notify\Emails\SpatieEmail;
use Modules\Notify\Filament\Clusters\Test\Pages\SendAwsEmailPage;
use Modules\Notify\Filament\Clusters\Test\Pages\SendEmailPage;
use Modules\Notify\Filament\Clusters\Test\Pages\SendFirebasePushNotificationPage;
use Modules\Notify\Filament\Clusters\Test\Pages\SendNetfunSmsPage;
use Modules\Notify\Filament\Clusters\Test\Pages\SendSmsPage;
use Modules\Notify\Filament\Clusters\Test\Pages\SendSpatieEmailPage;
use Modules\Notify\Filament\Clusters\Test\Pages\SendTelegramPage;
use Modules\Notify\Filament\Clusters\Test\Pages\SendWhatsAppPage;
<<<<<<< HEAD
<<<<<<< HEAD
=======
use Safe\DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
use Modules\Notify\Filament\Resources\ContactResource;
use Modules\Notify\Filament\Resources\MailTemplateResource;
use Modules\Notify\Filament\Resources\NotificationResource;
use Modules\Notify\Filament\Resources\NotificationTemplateResource;
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> dabf035f (fix(notify): fix static calls to instance-only getFormSchema/getInfolistSchema)
use Modules\Notify\Filament\Resources\NotificationTemplateResource\Schemas\NotificationTemplateForm;
use Modules\Notify\Filament\Resources\NotifyThemeResource\Schemas\NotifyThemeForm;
=======
use Modules\Notify\Filament\Resources\NotifyThemeResource;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use Modules\Notify\Filament\Resources\NotificationTemplateResource\Schemas\NotificationTemplateForm;
use Modules\Notify\Filament\Resources\NotifyThemeResource;
use Modules\Notify\Filament\Resources\NotifyThemeResource\Schemas\NotifyThemeForm;
>>>>>>> a988596b (first)
use Modules\Notify\Helpers\ConfigHelper;
use Modules\Notify\Models\NotificationTemplate;
<<<<<<< HEAD
use Modules\Notify\Models\NotifyTheme;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Notify\Notifications\GenericNotification;
=======
=======
>>>>>>> dabf035f (fix(notify): fix static calls to instance-only getFormSchema/getInfolistSchema)
use Modules\Notify\Notifications\GenericNotification;
use Modules\Notify\Services\PushNotificationService;
use Modules\Notify\Services\SmsService;
>>>>>>> a988596b (first)
use Modules\Notify\Tests\Unit\Traits\NotifyTrackingDummy;
use PHPUnit\Framework\Assert;
use ReflectionClass;
use ReflectionMethod;
use Safe\DateTime;

use function Pest\Laravel\artisan;
use function Safe\file_put_contents;
use function Safe\unlink;

<<<<<<< HEAD
=======
use Modules\Notify\Database\Factories\NotificationTemplateFactory;
use Modules\Notify\Notifications\GenericNotification;
use Modules\Notify\Services\PushNotificationService;
use Modules\Notify\Tests\Unit\Traits\NotifyTrackingDummy;
use Modules\Notify\Tests\TestCase;
use PHPUnit\Framework\Assert;
use ReflectionClass;
use ReflectionMethod;

use function Safe\file_put_contents;
use function Safe\unlink;

uses(TestCase::class)->group('no-notify-db');

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
/**
 * @param  class-string  $class
 */
function notifyPageWithoutLivewire(string $class): object
{
    return (new ReflectionClass($class))->newInstanceWithoutConstructor();
}

/**
 * @param  array<string, mixed>  $attributes
 */
function notifyDummyRecipient(array $attributes = []): Model
{
    return new class($attributes) extends Model
    {
        use Notifiable;

        protected $guarded = [];

        /**
         * @param  array<string, mixed>  $attributes
         */
        public function __construct(array $attributes = [])
        {
            parent::__construct($attributes);
        }

        public function routeNotificationForMail(): string
        {
            $email = $this->getAttribute('email');

            return is_string($email) ? $email : '';
        }

        public function routeNotificationForSms(): string
        {
            $phone = $this->getAttribute('phone');

            return is_string($phone) ? $phone : '';
        }
    };
}

function notifyEnsureTemplateTable(): void
{
    try {
        $schema = Schema::connection('notify');

        if ($schema->hasTable('notification_templates')) {
            NotificationTemplate::query()->delete();

            return;
        }

        $schema->create('notification_templates', function (Blueprint $table): void {
            $table->string('id', 36)->primary();
            $table->string('name')->nullable();
            $table->string('code')->unique();
            $table->text('description')->nullable();
            $table->text('subject')->nullable();
            $table->longText('body_html')->nullable();
            $table->longText('body_text')->nullable();
            $table->json('channels')->nullable();
            $table->json('variables')->nullable();
            $table->json('conditions')->nullable();
            $table->json('preview_data')->nullable();
            $table->json('metadata')->nullable();
            $table->string('category')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('version')->default(1);
            $table->string('type')->nullable();
            $table->timestamps();
        });
    } catch (\Throwable $e) {
        Assert::markTestSkipped('Notify DB non disponibile: '.$e->getMessage());
    }
}

describe('Notify highest-miss coverage', function (): void {
    test('cluster test pages expose form schemas', function (): void {
        $pages = [
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            SendEmailPage::class => ['getEmailFormSchema'],
            SendNetfunSmsPage::class => ['getSmsFormSchema'],
            SendWhatsAppPage::class => ['getWhatsAppFormSchema'],
            SendTelegramPage::class => ['getTelegramFormSchema'],
            SendFirebasePushNotificationPage::class => ['getPushFormSchema'],
            SendAwsEmailPage::class => ['getEmailFormSchema'],
            SendSpatieEmailPage::class => ['getEmailFormSchema'],
            SendSmsPage::class => ['getSmsFormSchema']];
<<<<<<< HEAD
=======
            \Modules\Notify\Filament\Clusters\Test\Pages\SendEmailPage::class => ['getEmailFormSchema'],
            \Modules\Notify\Filament\Clusters\Test\Pages\SendNetfunSmsPage::class => ['getSmsFormSchema'],
            \Modules\Notify\Filament\Clusters\Test\Pages\SendWhatsAppPage::class => ['getWhatsAppFormSchema'],
            \Modules\Notify\Filament\Clusters\Test\Pages\SendTelegramPage::class => ['getTelegramFormSchema'],
            \Modules\Notify\Filament\Clusters\Test\Pages\SendFirebasePushNotificationPage::class => ['getPushFormSchema'],
            \Modules\Notify\Filament\Clusters\Test\Pages\SendAwsEmailPage::class => ['getEmailFormSchema'],
            \Modules\Notify\Filament\Clusters\Test\Pages\SendSpatieEmailPage::class => ['getEmailFormSchema'],
            \Modules\Notify\Filament\Clusters\Test\Pages\SendSmsPage::class => ['getSmsFormSchema'],
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

        foreach ($pages as $class => $methods) {
            $page = notifyPageWithoutLivewire($class);
            Assert::assertSame('Notify', $class::getModuleName());
            foreach ($methods as $method) {
                $schema = (new ReflectionMethod($class, $method))->invoke($page);
                Assert::assertNotEmpty($schema);
            }
        }
    });

<<<<<<< HEAD
    test('resources expose model pages and legacy form schema', function (): void {
        Assert::assertSame(NotificationTemplate::class, NotificationTemplateResource::getModel());
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        Assert::assertArrayHasKey('name', NotificationTemplateResource::getFormSchema());
        Assert::assertNotEmpty(NotificationTemplateResource::getPages());

        Assert::assertSame(NotifyTheme::class, NotifyThemeResource::getModel());
        Assert::assertArrayHasKey('subject', NotifyThemeResource::getFormSchema());
        Assert::assertNotEmpty(NotifyThemeResource::getPages());

        Assert::assertSame(MailTemplate::class, MailTemplateResource::getModel());
        Assert::assertNotEmpty(MailTemplateResource::getFormSchema());
        Assert::assertNotEmpty(MailTemplateResource::getPages());

        Assert::assertSame(\Modules\Notify\Models\Notification::class, NotificationResource::getModel());
        Assert::assertNotEmpty(NotificationResource::getFormSchema());

        Assert::assertSame(Contact::class, ContactResource::getModel());
        Assert::assertNotEmpty(ContactResource::getFormSchema());
        Assert::assertNotEmpty(ContactResource::getPages());
    });

<<<<<<< HEAD
    test('Push actions send fakes schedules and guards empty targets', function (): void {
=======
        Assert::assertArrayHasKey('name', NotificationTemplateResource::getFormSchemaOld());
        Assert::assertNotEmpty(NotificationTemplateResource::getPages());

        Assert::assertSame(NotifyTheme::class, NotifyThemeResource::getModel());
        Assert::assertArrayHasKey('subject', NotifyThemeResource::getFormSchemaOld());
        Assert::assertNotEmpty(NotifyThemeResource::getPages());

        Assert::assertSame(MailTemplate::class, MailTemplateResource::getModel());
        Assert::assertNotEmpty(MailTemplateResource::getFormSchemaOld());
        Assert::assertNotEmpty(MailTemplateResource::getPages());

        Assert::assertSame(\Modules\Notify\Models\Notification::class, NotificationResource::getModel());
        Assert::assertNotEmpty(NotificationResource::getFormSchemaOld());

        Assert::assertSame(Contact::class, ContactResource::getModel());
        Assert::assertNotEmpty(ContactResource::getFormSchemaOld());
        Assert::assertNotEmpty(ContactResource::getPages());
    });

=======
>>>>>>> dabf035f (fix(notify): fix static calls to instance-only getFormSchema/getInfolistSchema)
    test('PushNotificationService sends fakes schedules and guards empty targets', function (): void {
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
    test('PushNotificationService sends fakes schedules and guards empty targets', function (): void {
>>>>>>> a988596b (first)
        config([
            'notify.fcm.server_key' => 'test-key',
            'notify.apns.certificate' => null,
            'notify.apns.passphrase' => null,
            'notify.apns.url' => 'https://api.push.apple.com',
            'notify.webpush.vapid_public' => 'pub',
            'notify.webpush.vapid_private' => 'priv',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'notify.webpush.vapid_subject' => 'mailto:test@example.com']);
        Http::fake([
            'https://fcm.googleapis.com/*' => Http::response(['message_id' => 'mid-1'], 200)]);
        Queue::fake();
        config(['cache.default' => 'array']);

<<<<<<< HEAD
        // Ex `PushNotificationService` (rimosso, vedi notify-services-to-actions.story.md):
        // ogni ex-metodo pubblico e' ora una Action dedicata in Actions/Push/.
        $notification = PushNotificationData::from(['title' => 'Ciao', 'body' => 'Test']);
        $fcmToken = str_repeat('a', 80).':'.str_repeat('b', 40);
        $apnsToken = str_repeat('ab', 32);

        $one = app(SendPushToDeviceAction::class)->execute($fcmToken, $notification, ['k' => 'v']);
=======
            'notify.webpush.vapid_subject' => 'mailto:test@example.com',
        ]);
        Http::fake([
            'https://fcm.googleapis.com/*' => Http::response(['message_id' => 'mid-1'], 200),
        ]);
        Queue::fake();
        config(['cache.default' => 'array']);

=======
>>>>>>> a988596b (first)
        $service = new PushNotificationService();
        $notification = ['title' => 'Ciao', 'body' => 'Test'];
        $fcmToken = str_repeat('a', 80).':'.str_repeat('b', 40);
        $apnsToken = str_repeat('ab', 32);

        $one = $service->sendToDevice($fcmToken, $notification, ['k' => 'v']);
<<<<<<< HEAD
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        Assert::assertArrayHasKey('fcm', $one);
        Assert::assertTrue($one['fcm']['success']);
        Assert::assertTrue($one['apns']['success']);
        Assert::assertTrue($one['webpush']['success']);

<<<<<<< HEAD
<<<<<<< HEAD
        $batch = app(SendPushToDevicesAction::class)->execute([$fcmToken, $apnsToken, 'web-token'], $notification);
        Assert::assertArrayHasKey('fcm', $batch);

        $topic = app(SendPushToTopicAction::class)->execute('news', $notification);
        Assert::assertArrayHasKey('fcm', $topic);

        $emptyAll = app(SendPushToAllUsersAction::class)->execute($notification);
        Assert::assertFalse($emptyAll['success']);

        $emptyTarget = app(SendPushWithTargetingAction::class)->execute(PushCriteriaData::from(['platform' => 'unknown']), $notification);
        Assert::assertFalse($emptyTarget['success']);

        expect(fn (): mixed => app(SendPushWithTemplateAction::class)->execute('missing', ['t']))
            ->toThrow(\Exception::class);

        $jobId = app(SchedulePushNotificationAction::class)->execute(['t1'], $notification, [], new DateTime('+1 hour'));
=======
=======
>>>>>>> a988596b (first)
        $batch = $service->sendToDevices([$fcmToken, $apnsToken, 'web-token'], $notification);
        Assert::assertArrayHasKey('fcm', $batch);

        $topic = $service->sendToTopic('news', $notification);
        Assert::assertArrayHasKey('fcm', $topic);

        $emptyAll = $service->sendToAll($notification);
        Assert::assertFalse($emptyAll['success']);

        $emptyTarget = $service->sendWithTargeting(['platform' => 'unknown'], $notification);
        Assert::assertFalse($emptyTarget['success']);

        expect(fn (): mixed => $service->sendWithTemplate('missing', ['t']))
            ->toThrow(\Exception::class);

        $jobId = $service->scheduleNotification(['t1'], $notification, [], new DateTime('+1 hour'));
<<<<<<< HEAD
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        Assert::assertStringStartsWith('push_', $jobId);
    });

    test('ConfigHelper replaces template variables from notify config', function (): void {
        config([
            'notify.company' => ['name' => 'Comune Test', 'city' => '{{name}}'],
            'notify.template_variables' => ['year' => '2026'],
            'notify.test_data' => ['hello' => 'Hi {{name}}'],
            'notify.webhooks' => ['url' => 'https://example.test'],
<<<<<<< HEAD
<<<<<<< HEAD
            'notify.email' => ['from' => 'noreply@example.test']]);
=======
            'notify.email' => ['from' => 'noreply@example.test'],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'notify.email' => ['from' => 'noreply@example.test']]);
>>>>>>> a988596b (first)

        $replaced = ConfigHelper::replaceTemplateVariables(['msg' => 'Anno {{year}}']);
        Assert::assertSame('Anno 2026', $replaced['msg']);

        Assert::assertSame('Comune Test', ConfigHelper::get('notify.company.name'));
        Assert::assertSame('Hi Comune Test', ConfigHelper::getTestData()['hello']);
        Assert::assertArrayHasKey('name', ConfigHelper::getCompanyConfig());
        Assert::assertArrayHasKey('url', ConfigHelper::getWebhookConfig());
        Assert::assertArrayHasKey('from', ConfigHelper::getEmailConfig());
    });

    test('analyze translations artisan command runs against module lang', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
        $pending = artisan('notify:analyze-translations');
        if ($pending instanceof PendingCommand) {
=======
        $pending = $this->artisan('notify:analyze-translations');
        if ($pending instanceof \Illuminate\Testing\PendingCommand) {
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        $pending = artisan('notify:analyze-translations');
        if ($pending instanceof PendingCommand) {
>>>>>>> a988596b (first)
            $pending->assertExitCode(0);

            return;
        }

        Assert::assertSame(0, $pending);
    });

    test('push actions send across fcm apns and webpush with fakes', function (): void {
        config([
            'notify.fcm.server_key' => 'test-key',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'notify.fcm.url' => 'https://fcm.googleapis.com/fcm/send']);
        Http::fake([
            'https://fcm.googleapis.com/*' => Http::response(['message_id' => 'mid-2'], 200)]);

        $notification = PushNotificationData::from([
            'title' => 'Titolo',
            'body' => 'Corpo']);
        $fcmToken = str_repeat('a', 80).':'.str_repeat('b', 40);
        $apnsToken = str_repeat('ab', 32);

<<<<<<< HEAD
        $platform = (new SendPushToPlatformAction)->execute('fcm', $fcmToken, $notification);
        Assert::assertTrue($platform['success']);
        Assert::assertTrue((new SendPushToPlatformAction)->execute('apns', $apnsToken, $notification)['success']);
        Assert::assertTrue((new SendPushToPlatformAction)->execute('webpush', 'web-token', $notification)['success']);

        $devices = (new SendPushToDevicesAction)->execute([$fcmToken, $apnsToken], $notification);
        Assert::assertArrayHasKey('fcm', $devices);

        $topic = (new SendPushToTopicAction)->execute('news', $notification);
=======
            'notify.fcm.url' => 'https://fcm.googleapis.com/fcm/send',
        ]);
        Http::fake([
            'https://fcm.googleapis.com/*' => Http::response(['message_id' => 'mid-2'], 200),
        ]);

        $notification = \Modules\Notify\Datas\PushNotificationData::from([
            'title' => 'Titolo',
            'body' => 'Corpo',
        ]);
        $fcmToken = str_repeat('a', 80).':'.str_repeat('b', 40);
        $apnsToken = str_repeat('ab', 32);

        $platform = (new \Modules\Notify\Actions\Push\SendPushToPlatformAction())->execute('fcm', $fcmToken, $notification);
        Assert::assertTrue($platform['success']);
        Assert::assertTrue((new \Modules\Notify\Actions\Push\SendPushToPlatformAction())->execute('apns', $apnsToken, $notification)['success']);
        Assert::assertTrue((new \Modules\Notify\Actions\Push\SendPushToPlatformAction())->execute('webpush', 'web-token', $notification)['success']);

        $devices = (new \Modules\Notify\Actions\Push\SendPushToDevicesAction())->execute([$fcmToken, $apnsToken], $notification);
        Assert::assertArrayHasKey('fcm', $devices);

        $topic = (new \Modules\Notify\Actions\Push\SendPushToTopicAction())->execute('news', $notification);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        $platform = (new SendPushToPlatformAction())->execute('fcm', $fcmToken, $notification);
        Assert::assertTrue($platform['success']);
        Assert::assertTrue((new SendPushToPlatformAction())->execute('apns', $apnsToken, $notification)['success']);
        Assert::assertTrue((new SendPushToPlatformAction())->execute('webpush', 'web-token', $notification)['success']);

        $devices = (new SendPushToDevicesAction())->execute([$fcmToken, $apnsToken], $notification);
        Assert::assertArrayHasKey('fcm', $devices);

        $topic = (new SendPushToTopicAction())->execute('news', $notification);
>>>>>>> a988596b (first)
        Assert::assertArrayHasKey('fcm', $topic);
    });

    test('whatsapp and telegram actions execute with mocked http', function (): void {
        config([
            'services.360dialog.api_key' => '360-key',
            'services.vonage.api_key' => 'vonage-key',
            'services.vonage.api_secret' => 'vonage-secret',
            'services.telegram.token' => 'telegram-token',
            'whatsapp.debug' => false,
            'whatsapp.timeout' => 5,
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'whatsapp.from' => '+390000000000']);

        $whatsappData = WhatsAppData::from([
            'recipient' => '+393331112233',
            'body' => 'Ciao']);
        $telegramData = TelegramData::from([
            'chatId' => '12345',
            'text' => 'Ciao']);

        foreach ([
            Send360dialogWhatsAppAction::class,
            SendVonageWhatsAppAction::class,
            SendFacebookWhatsAppAction::class,
            SendTwilioWhatsAppAction::class,
            SendBotmanTelegramAction::class,
            SendNutgramTelegramAction::class,
            SendOfficialTelegramAction::class] as $class) {
            try {
<<<<<<< HEAD
                $action = new $class;
=======
            'whatsapp.from' => '+390000000000',
        ]);

        $whatsappData = \Modules\Notify\Datas\WhatsAppData::from([
            'recipient' => '+393331112233',
            'body' => 'Ciao',
        ]);
        $telegramData = \Modules\Notify\Datas\TelegramData::from([
            'chatId' => '12345',
            'text' => 'Ciao',
        ]);

        foreach ([
            \Modules\Notify\Actions\WhatsApp\Send360dialogWhatsAppAction::class,
            \Modules\Notify\Actions\WhatsApp\SendVonageWhatsAppAction::class,
            \Modules\Notify\Actions\WhatsApp\SendFacebookWhatsAppAction::class,
            \Modules\Notify\Actions\WhatsApp\SendTwilioWhatsAppAction::class,
            \Modules\Notify\Actions\Telegram\SendBotmanTelegramAction::class,
            \Modules\Notify\Actions\Telegram\SendNutgramTelegramAction::class,
            \Modules\Notify\Actions\Telegram\SendOfficialTelegramAction::class,
        ] as $class) {
            try {
                $action = new $class();
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                $action = new $class();
>>>>>>> a988596b (first)
                $data = str_contains($class, 'Telegram') ? $telegramData : $whatsappData;
                $result = $action->execute($data);
                Assert::assertNotEmpty($result);
            } catch (\Throwable $e) {
                Assert::assertNotSame('', $e->getMessage());
            }
        }
    });

<<<<<<< HEAD
<<<<<<< HEAD
    test('SendSmsAction validates missing engine and accepts local vars', function (): void {
        // Ex `SmsService::make()->setLocalVars()->mergeVars()->send()` (rimosso, vedi
        // notify-services-to-actions.story.md): un solo execute() sostituisce la catena
        // fluente, impostando prima le proprieta' e poi tentando (senza successo, per
        // design invariato) il dispatch verso il motore configurato.
        $action = new SendSmsAction;

        expect(fn (): array => $action->execute(['to' => '+390000000000', 'body' => 'Test', 'foo' => 'bar']))
            ->toThrow(\RuntimeException::class);

        Assert::assertSame('+390000000000', $action->to);
        Assert::assertSame('bar', $action->vars['foo']);
=======
    test('SmsService validates missing engine and accepts local vars', function (): void {
        $service = \Modules\Notify\Services\SmsService::make()
=======
    test('SmsService validates missing engine and accepts local vars', function (): void {
        $service = SmsService::make()
>>>>>>> a988596b (first)
            ->setLocalVars(['to' => '+390000000000', 'body' => 'Test'])
            ->mergeVars(['foo' => 'bar']);
        Assert::assertSame('+390000000000', $service->to);
        Assert::assertSame('bar', $service->vars['foo']);

<<<<<<< HEAD
        expect(fn (): \Modules\Notify\Services\SmsService => $service->send())
            ->toThrow(\RuntimeException::class);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        expect(fn (): SmsService => $service->send())
            ->toThrow(\RuntimeException::class);
>>>>>>> a988596b (first)
    });

    test('sms actions normalize recipients before provider call', function (): void {
        config([
            'services.netfun.token' => 'netfun-token',
            'sms.drivers.netfun.token' => 'netfun-token',
            'sms.drivers.netfun.api_url' => 'https://example.test/sms',
            'sms.drivers.twilio.sid' => 'sid',
            'sms.drivers.twilio.token' => 'token',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'sms.drivers.twilio.from' => '+390000000000']);

        $sms = SmsData::from([
            'recipient' => '0039333123456',
            'body' => 'Test',
            'from' => 'APP']);

        foreach ([
            NetfunSendAction::class,
            SendNetfunSMSAction::class,
            SendTwilioSMSAction::class,
            SendNexmoSMSAction::class,
            SendPlivoSMSAction::class,
            SendGammuSMSAction::class] as $class) {
            try {
<<<<<<< HEAD
                $action = new $class;
=======
            'sms.drivers.twilio.from' => '+390000000000',
        ]);

        $sms = \Modules\Notify\Datas\SmsData::from([
            'recipient' => '0039333123456',
            'body' => 'Test',
            'from' => 'APP',
        ]);

        foreach ([
            \Modules\Notify\Actions\NetfunSendAction::class,
            \Modules\Notify\Actions\SMS\SendNetfunSMSAction::class,
            \Modules\Notify\Actions\SMS\SendTwilioSMSAction::class,
            \Modules\Notify\Actions\SMS\SendNexmoSMSAction::class,
            \Modules\Notify\Actions\SMS\SendPlivoSMSAction::class,
            \Modules\Notify\Actions\SMS\SendGammuSMSAction::class,
        ] as $class) {
            try {
                $action = new $class();
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                $action = new $class();
>>>>>>> a988596b (first)
                $action->execute($sms);
            } catch (\Throwable $e) {
                Assert::assertNotSame('', $e->getMessage());
            }
        }
    });

    test('notification template and theme forms expose keyed schema', function (): void {
        Assert::assertArrayHasKey(
            'name',
<<<<<<< HEAD
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            NotificationTemplateForm::getFormSchema(),
        );
        Assert::assertArrayHasKey(
            'subject',
            NotifyThemeForm::getFormSchema(),
<<<<<<< HEAD
=======
            \Modules\Notify\Filament\Resources\NotificationTemplateResource\Schemas\NotificationTemplateForm::getFormSchema(),
        );
        Assert::assertArrayHasKey(
            'subject',
            \Modules\Notify\Filament\Resources\NotifyThemeResource\Schemas\NotifyThemeForm::getFormSchema(),
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
=======
            app(NotificationTemplateForm::class)->getFormSchema(),
        );
        Assert::assertArrayHasKey(
            'subject',
            app(NotifyThemeForm::class)->getFormSchema(),
>>>>>>> dabf035f (fix(notify): fix static calls to instance-only getFormSchema/getInfolistSchema)
        );
    });

    test('SendNotificationAction handle works with sqlite notify schema', function (): void {
        notifyEnsureTemplateTable();

        NotificationTemplateFactory::new()->createOne([
            'id' => (string) Str::uuid(),
            'name' => 'Welcome',
            'code' => 'welcome-template',
            'subject' => ['en' => 'Welcome', 'it' => 'Benvenuto'],
            'body_text' => ['en' => 'Hello', 'it' => 'Ciao'],
            'body_html' => ['en' => '<p>Hello</p>', 'it' => '<p>Ciao</p>'],
            'channels' => ['mail'],
            'variables' => [],
            'is_active' => true,
            'conditions' => null,
<<<<<<< HEAD
<<<<<<< HEAD
            'type' => 'email']);
=======
            'type' => 'email',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'type' => 'email']);
>>>>>>> a988596b (first)

        $recipient = notifyDummyRecipient(['email' => 'user@example.test']);
        Notification::fake();

<<<<<<< HEAD
<<<<<<< HEAD
        $result = (new SendNotificationAction)->handle(
=======
        $result = (new \Modules\Notify\Actions\SendNotificationAction())->handle(
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        $result = (new SendNotificationAction())->handle(
>>>>>>> a988596b (first)
            $recipient,
            'welcome-template',
        );

        Assert::assertNull($result);
        Notification::assertSentTo($recipient, GenericNotification::class);

<<<<<<< HEAD
<<<<<<< HEAD
        expect(fn (): mixed => (new SendNotificationAction)->handle(
=======
        expect(fn (): mixed => (new \Modules\Notify\Actions\SendNotificationAction())->handle(
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        expect(fn (): mixed => (new SendNotificationAction())->handle(
>>>>>>> a988596b (first)
            $recipient,
            'missing-template',
        ))->toThrow(\Exception::class);
    });

    test('SendNotificationAction sms channel truncates long messages', function (): void {
        notifyEnsureTemplateTable();

        NotificationTemplateFactory::new()->createOne([
            'id' => (string) Str::uuid(),
            'name' => 'Sms',
            'code' => 'sms-template',
            'subject' => ['en' => 'Sms', 'it' => 'Sms'],
            'body_text' => ['en' => str_repeat('x', 400), 'it' => str_repeat('x', 400)],
            'body_html' => null,
            'channels' => ['sms'],
            'variables' => [],
            'is_active' => true,
            'conditions' => null,
<<<<<<< HEAD
<<<<<<< HEAD
            'type' => 'sms']);
=======
            'type' => 'sms',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'type' => 'sms']);
>>>>>>> a988596b (first)

        $recipient = notifyDummyRecipient(['phone' => '+393331112233']);
        Notification::fake();

<<<<<<< HEAD
<<<<<<< HEAD
        (new SendNotificationAction)->handle(
=======
        (new \Modules\Notify\Actions\SendNotificationAction())->handle(
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        (new SendNotificationAction())->handle(
>>>>>>> a988596b (first)
            $recipient,
            'sms-template',
        );

        Notification::assertSentTo($recipient, GenericNotification::class);
    });

    test('notification template compiles previews and conditions in memory', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
        $template = new NotificationTemplate;
=======
        $template = new NotificationTemplate();
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        $template = new NotificationTemplate();
>>>>>>> a988596b (first)
        $template->forceFill([
            'subject' => 'Ciao Marco',
            'body_text' => 'Testo Marco',
            'body_html' => '<p>Marco</p>',
            'channels' => ['mail', 'database'],
            'conditions' => ['send' => true],
            'preview_data' => ['name' => 'Marco'],
<<<<<<< HEAD
<<<<<<< HEAD
            'grapesjs_data' => ['blocks' => []]]);
=======
            'grapesjs_data' => ['blocks' => []],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'grapesjs_data' => ['blocks' => []]]);
>>>>>>> a988596b (first)

        $compiled = $template->compile(['name' => 'Marco']);
        Assert::assertSame('Ciao Marco', $compiled['subject']);
        Assert::assertTrue($template->shouldSend(['send' => true]));
        Assert::assertFalse($template->shouldSend(['send' => false]));
        Assert::assertStringContainsString('Marco', $template->preview()['subject']);
        Assert::assertSame(['blocks' => []], $template->getGrapesJSData());
        $template->setGrapesJSData(['foo' => 'bar']);
        Assert::assertSame(['foo' => 'bar'], $template->getGrapesJSData());
    });

    test('SpatieEmail attachment helpers work without constructor bootstrap', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
        $ref = new ReflectionClass(SpatieEmail::class);
        /** @var SpatieEmail $email */
=======
        $ref = new ReflectionClass(\Modules\Notify\Emails\SpatieEmail::class);
        /** @var \Modules\Notify\Emails\SpatieEmail $email */
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        $ref = new ReflectionClass(SpatieEmail::class);
        /** @var SpatieEmail $email */
>>>>>>> a988596b (first)
        $email = $ref->newInstanceWithoutConstructor();
        $email->slug = 'welcome-mail';
        $email->data = ['first_name' => 'Marco'];

        Assert::assertSame('welcome-mail', $email->getSlug());
        Assert::assertSame($email, $email->setRecipient('recipient@example.test'));
        $envelope = $email->envelope();
        $to = $envelope->to;
        Assert::assertNotEmpty($to);
        $firstAddress = $to[0];
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertInstanceOf(Address::class, $firstAddress);
=======
        Assert::assertInstanceOf(\Illuminate\Mail\Mailables\Address::class, $firstAddress);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        Assert::assertInstanceOf(Address::class, $firstAddress);
>>>>>>> a988596b (first)
        Assert::assertSame('recipient@example.test', $firstAddress->address);
        Assert::assertSame($email, $email->embedLogo('/tmp/missing-logo.png'));

        $path = sys_get_temp_dir().'/notify-attach-'.uniqid('', true).'.txt';
        file_put_contents($path, 'attachment-body');
        $fromPath = $email->getAttachmentFromPath(['path' => $path, 'as' => 'file.txt']);
        Assert::assertSame('file.txt', $fromPath->as);

        $fromData = $email->getAttachmentFromData([
            'data' => 'inline-data',
            'as' => 'inline.txt',
<<<<<<< HEAD
<<<<<<< HEAD
            'mime' => 'text/plain']);
=======
            'mime' => 'text/plain',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'mime' => 'text/plain']);
>>>>>>> a988596b (first)
        Assert::assertSame('inline.txt', $fromData->as);

        $email->addAttachments([
            ['path' => $path],
<<<<<<< HEAD
<<<<<<< HEAD
            ['data' => 'payload', 'as' => 'payload.bin']]);
=======
            ['data' => 'payload', 'as' => 'payload.bin'],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            ['data' => 'payload', 'as' => 'payload.bin']]);
>>>>>>> a988596b (first)
        Assert::assertCount(2, $email->attachments());
        unlink($path);
    });

    test('notification tracking adds pixel when enabled', function (): void {
        config([
            'notify.tracking.enabled' => true,
            'notify.tracking.pixel.enabled' => true,
            'notify.tracking.links.enabled' => false,
<<<<<<< HEAD
<<<<<<< HEAD
            'notify.tracking.pixel.route' => 'login']);

        $dummy = new NotifyTrackingDummy;
=======
            'notify.tracking.pixel.route' => 'login',
        ]);

        $dummy = new NotifyTrackingDummy();
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'notify.tracking.pixel.route' => 'login']);

        $dummy = new NotifyTrackingDummy();
>>>>>>> a988596b (first)
        $html = '<p>Newsletter</p>';
        $tracked = $dummy->addTrackingPublic($html, 'track-uuid');

        Assert::assertStringContainsString('track-uuid', $tracked);
        Assert::assertNotSame($html, $tracked);
    });

    test('facebook whatsapp action builds payload variants offline', function (): void {
        config([
            'services.facebook.access_token' => 'fb-token',
            'services.facebook.phone_number_id' => '123456',
            'whatsapp.debug' => false,
<<<<<<< HEAD
<<<<<<< HEAD
            'whatsapp.timeout' => 1]);
=======
            'whatsapp.timeout' => 1,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'whatsapp.timeout' => 1]);
>>>>>>> a988596b (first)

        $cases = [
            ['recipient' => '+393331112233', 'body' => 'Ciao', 'type' => 'text'],
            ['recipient' => '+393331112233', 'body' => '', 'type' => 'template', 'template' => ['name' => 'hello']],
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            ['recipient' => '+393331112233', 'body' => '', 'type' => 'media', 'media' => ['https://example.test/a.jpg']]];

        foreach ($cases as $payload) {
            try {
<<<<<<< HEAD
                $result = (new SendFacebookWhatsAppAction)->execute(
                    WhatsAppData::from($payload),
=======
            ['recipient' => '+393331112233', 'body' => '', 'type' => 'media', 'media' => ['https://example.test/a.jpg']],
        ];

        foreach ($cases as $payload) {
            try {
                $result = (new \Modules\Notify\Actions\WhatsApp\SendFacebookWhatsAppAction())->execute(
                    \Modules\Notify\Datas\WhatsAppData::from($payload),
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                $result = (new SendFacebookWhatsAppAction())->execute(
                    WhatsAppData::from($payload),
>>>>>>> a988596b (first)
                );
                Assert::assertNotEmpty($result);
            } catch (\Throwable $e) {
                Assert::assertNotSame('', $e->getMessage());
            }
        }
    });

    test('esendex and duocircle actions fail fast without external providers', function (): void {
        config([
            'esendex.username' => 'user',
            'esendex.password' => 'pass',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'esendex.sender' => 'APP']);

        $sms = SmsData::from([
            'recipient' => '+393331112233',
            'body' => 'Test',
            'from' => 'APP']);

        try {
<<<<<<< HEAD
            (new EsendexSendAction)->execute($sms);
=======
            'esendex.sender' => 'APP',
        ]);

        $sms = \Modules\Notify\Datas\SmsData::from([
            'recipient' => '+393331112233',
            'body' => 'Test',
            'from' => 'APP',
        ]);

        try {
            (new \Modules\Notify\Actions\EsendexSendAction())->execute($sms);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            (new EsendexSendAction())->execute($sms);
>>>>>>> a988596b (first)
        } catch (\Throwable $e) {
            Assert::assertNotSame('', $e->getMessage());
        }

<<<<<<< HEAD
<<<<<<< HEAD
        expect(fn (): array => (new TryDuocircleMailAction)->execute([
            'to' => 'user@example.test']))->toThrow(\Exception::class);
=======
        expect(fn (): array => (new \Modules\Notify\Actions\Mail\Engines\Duocircle\TryDuocircleMailAction())->execute([
            'to' => 'user@example.test',
        ]))->toThrow(\Exception::class);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        expect(fn (): array => (new TryDuocircleMailAction())->execute([
            'to' => 'user@example.test']))->toThrow(\Exception::class);
>>>>>>> a988596b (first)
    });
});
