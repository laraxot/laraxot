<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Messages\MailMessage;
use Modules\Notify\Contracts\CanThemeNotificationContract;
use Modules\Notify\Datas\EmailData;
use Modules\Notify\Datas\NotificationData;
use Modules\Notify\Datas\SmsData;
use Modules\Notify\Datas\WhatsAppData;
use Modules\Notify\Notifications\EmailDataNotification;
use Modules\Notify\Notifications\GenericNotification;
use Modules\Notify\Notifications\RecordNotification;
use Modules\Notify\Notifications\SmsNotification;
use Modules\Notify\Notifications\TelegramNotification;
use Modules\Notify\Notifications\ThemeNotification;
use Modules\Notify\Notifications\TicketAssignedNotification;
use Modules\Notify\Notifications\TicketStatusChangedNotification;
use Modules\Notify\Notifications\WhatsAppNotification;
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
use Modules\User\Models\User;
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;

use function Safe\class_uses;

<<<<<<< HEAD
=======
use Modules\Notify\Tests\TestCase;
use Modules\User\Models\User;
use PHPUnit\Framework\Assert;
use Modules\Xot\Tests\XotBasePest;

use function Safe\class_uses;

uses(TestCase::class)->group('no-notify-db');

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
function notificationsCoverageTicketModel(int $id = 10): Model
{
    $ticket = new class extends Model
=======
function notificationsCoverageTicketModel(int $id = 10): Model
{
    $ticket = new class() extends Model
>>>>>>> a988596b (first)
    {
        protected $guarded = [];

        public $timestamps = false;
    };
    $ticket->setAttribute('id', $id);

    return $ticket;
}

function makeThemeNotifiableDummy(): CanThemeNotificationContract
{
<<<<<<< HEAD
    return new class extends Model implements CanThemeNotificationContract
=======
    return new class() extends Model implements CanThemeNotificationContract
>>>>>>> a988596b (first)
    {
        protected $guarded = [];

        public bool $emailCallbackCalled = false;

        public bool $smsCallbackCalled = false;

        public function getNotificationData(string $name, array $view_params = []): NotificationData
        {
            return NotificationData::from([
                'from' => 'System',
                'recipient' => 'user@example.test',
                'body' => 'Body',
<<<<<<< HEAD
<<<<<<< HEAD
                'channels' => ['mail', 'sms']]);
=======
                'channels' => ['mail', 'sms'],
            ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'channels' => ['mail', 'sms']]);
>>>>>>> a988596b (first)
        }

        public function getModel(): Model
        {
            return $this;
        }

        public function sendEmailCallback(): void
        {
            $this->emailCallbackCalled = true;
        }

        public function sendSmsCallback(): void
        {
            $this->smsCallbackCalled = true;
        }

        public function increase(string $what, array $data): void {}
    };
}

function makeGenericNotifiableDummy(): Model
{
<<<<<<< HEAD
    return new class extends Model
=======
    return new class() extends Model
>>>>>>> a988596b (first)
    {
        protected $guarded = [];

        public function getFullName(): string
        {
            return 'Mario Rossi';
        }

        public function routeNotificationForTwilio(mixed $notification): string
        {
            return '+39000111222';
        }
    };
}

test('email data notification exposes mail channel and array payload', function () {
    $emailData = EmailData::from([
        'recipient' => 'recipient@example.test',
        'from' => 'Sender Name',
        'from_email' => 'from@example.test',
        'subject' => 'Subject',
        'body_html' => '<p>Body</p>',
<<<<<<< HEAD
<<<<<<< HEAD
        'body' => 'Body']);
=======
        'body' => 'Body',
    ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])

    $notification = new EmailDataNotification($emailData);

    Assert::assertSame(['mail'], $notification->via(new \stdClass));
=======
        'body' => 'Body']);

    $notification = new EmailDataNotification($emailData);

    Assert::assertSame(['mail'], $notification->via(new \stdClass()));
>>>>>>> a988596b (first)
    Assert::assertEquals([
        'recipient' => 'recipient@example.test',
        'subject' => 'Subject',
        'from' => 'Sender Name',
        'from_email' => 'from@example.test',
<<<<<<< HEAD
<<<<<<< HEAD
        'body' => 'Body'], XotBasePest::assertArray($notification->toArray(new \stdClass)));
=======
        'body' => 'Body',
    ], XotBasePest::assertArray($notification->toArray(new \stdClass)));
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'body' => 'Body'], XotBasePest::assertArray($notification->toArray(new \stdClass())));
>>>>>>> a988596b (first)
});

test('sms notification builds sms payload and provider config', function () {
    $notification = new SmsNotification('Test SMS', [
        'recipient' => '+39123',
        'from' => 'Xot',
<<<<<<< HEAD
<<<<<<< HEAD
        'provider' => 'netfun']);
=======
        'provider' => 'netfun',
    ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])

    $sms = $notification->toSms(new \stdClass);

    Assert::assertInstanceOf(SmsData::class, $sms);
    Assert::assertSame(['sms'], $notification->via(new \stdClass));
=======
        'provider' => 'netfun']);

    $sms = $notification->toSms(new \stdClass());

    Assert::assertInstanceOf(SmsData::class, $sms);
    Assert::assertSame(['sms'], $notification->via(new \stdClass()));
>>>>>>> a988596b (first)
    Assert::assertSame('+39123', $sms->recipient);
    Assert::assertSame('netfun', $notification->getProvider());
    Assert::assertArrayHasKey('provider', $notification->getConfig());
});

test('telegram notification uses telegram channel class and returns message', function () {
    $notification = new TelegramNotification('Hello telegram');

<<<<<<< HEAD
    $channels = XotBasePest::assertArray($notification->via(new \stdClass));
    Assert::assertCount(1, $channels);
    Assert::assertNotEmpty($channels[0] ?? null);
    Assert::assertNotEmpty($notification->toTelegram(new \stdClass));
=======
    $channels = XotBasePest::assertArray($notification->via(new \stdClass()));
    Assert::assertCount(1, $channels);
    Assert::assertNotEmpty($channels[0] ?? null);
    Assert::assertNotEmpty($notification->toTelegram(new \stdClass()));
>>>>>>> a988596b (first)
});

test('whatsapp notification exposes whatsapp channel and provider', function () {
    $notification = new WhatsAppNotification('Hello WA', [
        'recipient' => '+39999',
<<<<<<< HEAD
<<<<<<< HEAD
        'provider' => 'twilio']);
=======
        'provider' => 'twilio',
    ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])

    $wa = $notification->toWhatsApp(new \stdClass);

    Assert::assertInstanceOf(WhatsAppData::class, $wa);
    Assert::assertSame(['whatsapp'], $notification->via(new \stdClass));
=======
        'provider' => 'twilio']);

    $wa = $notification->toWhatsApp(new \stdClass());

    Assert::assertInstanceOf(WhatsAppData::class, $wa);
    Assert::assertSame(['whatsapp'], $notification->via(new \stdClass()));
>>>>>>> a988596b (first)
    Assert::assertSame('+39999', $wa->recipient);
    Assert::assertSame('twilio', $notification->getProvider());
});

test('theme notification returns channels and array payload', function () {
    $notification = new ThemeNotification('welcome-email', ['foo' => 'bar']);
    $notifiable = makeThemeNotifiableDummy();

    Assert::assertSame(['mail', 'sms'], $notification->via($notifiable));
    Assert::assertSame([
        'foo' => 'bar',
<<<<<<< HEAD
<<<<<<< HEAD
        '_name' => 'welcome-email'], $notification->toArray($notifiable));
=======
        '_name' => 'welcome-email',
    ], $notification->toArray($notifiable));
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        '_name' => 'welcome-email'], $notification->toArray($notifiable));
>>>>>>> a988596b (first)
    Assert::assertTrue(in_array(Queueable::class, class_uses($notification), true));
});

test('generic notification supports channels mail twilio and database payload', function () {
    $notification = new GenericNotification(
        'System alert',
        'Body text',
        ['mail', 'database'],
        ['action_text' => 'Open', 'action_url' => 'https://example.test']
    );

    $notifiable = makeGenericNotifiableDummy();

    $mail = $notification->toMail($notifiable);
    $twilio = $notification->toTwilio($notifiable);
    $database = $notification->toDatabase($notifiable);

    Assert::assertInstanceOf(MailMessage::class, $mail);
    Assert::assertArrayHasKey('content', $twilio);
    Assert::assertArrayHasKey('to', $twilio);
    Assert::assertSame('+39000111222', $twilio['to']);
    Assert::assertArrayHasKey('title', $database);
    Assert::assertArrayHasKey('message', $database);
    Assert::assertArrayHasKey('data', $database);
    Assert::assertArrayHasKey('created_at', $database);
    Assert::assertSame(['mail', 'database'], $notification->via($notifiable));
});

test('record notification manages channels and merged payloads', function () {
<<<<<<< HEAD
    $record = new class extends Model
=======
    $record = new class() extends Model
>>>>>>> a988596b (first)
    {
        protected $table = 'notify_record_dummy';
    };

    $notification = new RecordNotification($record, 'My Slug Name');

<<<<<<< HEAD
    $notifiable = new class
=======
    $notifiable = new class()
>>>>>>> a988596b (first)
    {
        public function routeNotificationFor(string $channel): ?string
        {
            return match ($channel) {
                'mail' => 'dest@example.test',
                'sms' => '+391234',
                default => null,
            };
        }
    };

    $channels = $notification->via($notifiable);

    Assert::assertCount(2, $channels);
    Assert::assertContains('mail', $channels);

    $notification->mergeData(['a' => 'b'])->addAttachments([['as' => 'file.pdf', 'path' => base_path('storage/app/f.pdf')]]);

    Assert::assertArrayHasKey('a', $notification->data);
    Assert::assertSame('b', $notification->data['a']);
    Assert::assertCount(1, $notification->attachments);
});

test('ticket notifications expose channels and array payload', function () {
<<<<<<< HEAD
    $user = new User;
=======
    $user = new User();
>>>>>>> a988596b (first)
    $user->id = 'user-1';
    $user->name = 'Assigner User';

    $assigned = new TicketAssignedNotification((object) ['id' => 10], $user);
    $changed = new TicketStatusChangedNotification(notificationsCoverageTicketModel(), 'open', 'closed');

<<<<<<< HEAD
    Assert::assertSame(['mail', 'database'], $assigned->via(new \stdClass));
    Assert::assertArrayHasKey('assigned_by', $assigned->toArray(new \stdClass));
    Assert::assertSame('user-1', $assigned->toArray(new \stdClass)['assigned_by']);
    Assert::assertSame(['mail', 'database'], $changed->via(new \stdClass));
    Assert::assertArrayHasKey('old_status', $changed->toArray(new \stdClass));
    Assert::assertSame('open', $changed->toArray(new \stdClass)['old_status']);
    Assert::assertSame('closed', $changed->toArray(new \stdClass)['new_status']);
=======
    Assert::assertSame(['mail', 'database'], $assigned->via(new \stdClass()));
    Assert::assertArrayHasKey('assigned_by', $assigned->toArray(new \stdClass()));
    Assert::assertSame('user-1', $assigned->toArray(new \stdClass())['assigned_by']);
    Assert::assertSame(['mail', 'database'], $changed->via(new \stdClass()));
    Assert::assertArrayHasKey('old_status', $changed->toArray(new \stdClass()));
    Assert::assertSame('open', $changed->toArray(new \stdClass())['old_status']);
    Assert::assertSame('closed', $changed->toArray(new \stdClass())['new_status']);
>>>>>>> a988596b (first)
});
