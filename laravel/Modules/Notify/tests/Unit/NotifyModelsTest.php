<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Modules\Notify\Database\Factories\NotificationChannelFactory;
use Modules\Notify\Database\Factories\NotificationFactory;
use Modules\Notify\Database\Factories\NotificationLogFactory;
use Modules\Notify\Database\Factories\NotificationTemplateFactory;
use Modules\Notify\Models\Notification;
use Modules\Notify\Models\NotificationChannel;
use Modules\Notify\Models\NotificationLog;
use Modules\Notify\Models\NotificationTemplate;
<<<<<<< HEAD
<<<<<<< HEAD
=======
use Modules\Notify\Tests\TestCase;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
use PHPUnit\Framework\Assert;

use function Safe\json_encode;

<<<<<<< HEAD
<<<<<<< HEAD
=======
uses(TestCase::class)->group('notify-db');

>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
it('can create a notification', function () {
    $notification = NotificationFactory::new()->createOne([
        'type' => 'App\Notifications\UserRegistered',
        'notifiable_type' => 'Modules\User\Models\User',
        'notifiable_id' => 1,
<<<<<<< HEAD
<<<<<<< HEAD
        'data' => json_encode(['message' => 'User registered'])]);
=======
        'data' => json_encode(['message' => 'User registered']),
    ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'data' => json_encode(['message' => 'User registered'])]);
>>>>>>> a988596b (first)

    Assert::assertInstanceOf(Notification::class, $notification);
    Assert::assertSame('App\Notifications\UserRegistered', $notification->type);
    Assert::assertSame('Modules\User\Models\User', $notification->notifiable_type);
    Assert::assertSame(1, $notification->notifiable_id);
});

it('can create a notification with read status', function () {
    $notification = NotificationFactory::new()->createOne([
        'type' => 'App\Notifications\Welcome',
        'notifiable_type' => 'Modules\User\Models\User',
        'notifiable_id' => 1,
<<<<<<< HEAD
<<<<<<< HEAD
        'read_at' => now()]);
=======
        'read_at' => now(),
    ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'read_at' => now()]);
>>>>>>> a988596b (first)

    Assert::assertInstanceOf(Carbon::class, $notification->read_at);
});

it('can create a notification template', function () {
    $template = NotificationTemplateFactory::new()->createOne([
        'name' => 'Welcome Email',
        'type' => 'email',
        'subject' => json_encode(['en' => 'Welcome to our platform']),
<<<<<<< HEAD
<<<<<<< HEAD
        'body_html' => json_encode(['en' => 'Welcome {{user.name}}!'])]);
=======
        'body_html' => json_encode(['en' => 'Welcome {{user.name}}!']),
    ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'body_html' => json_encode(['en' => 'Welcome {{user.name}}!'])]);
>>>>>>> a988596b (first)

    Assert::assertInstanceOf(NotificationTemplate::class, $template);
    Assert::assertSame('Welcome Email', $template->name);
    Assert::assertSame('email', $template->type->value ?? $template->type);
});

it('can make a notification channel without persisting', function () {
    $channel = NotificationChannelFactory::new()->makeOne([
        'name' => 'SMS',
        'driver' => 'sms',
<<<<<<< HEAD
<<<<<<< HEAD
        'is_enabled' => true]);
=======
        'is_enabled' => true,
    ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'is_enabled' => true]);
>>>>>>> a988596b (first)

    Assert::assertInstanceOf(NotificationChannel::class, $channel);
    Assert::assertSame('SMS', $channel->name);
    Assert::assertSame('sms', $channel->driver);
});

it('can create a notification log', function () {
    $log = NotificationLogFactory::new()->createOne([
        'status' => 'sent',
<<<<<<< HEAD
<<<<<<< HEAD
        'content' => 'Notification sent successfully']);
=======
        'content' => 'Notification sent successfully',
    ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'content' => 'Notification sent successfully']);
>>>>>>> a988596b (first)

    Assert::assertInstanceOf(NotificationLog::class, $log);
    Assert::assertSame('sent', $log->status);
});

it('can create a notification with custom data', function () {
    $payload = [
        'user_id' => 1,
        'action' => 'profile_updated',
<<<<<<< HEAD
<<<<<<< HEAD
        'details' => ['field' => 'email', 'old_value' => 'old@example.com']];
=======
        'details' => ['field' => 'email', 'old_value' => 'old@example.com'],
    ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'details' => ['field' => 'email', 'old_value' => 'old@example.com']];
>>>>>>> a988596b (first)

    $notification = NotificationFactory::new()->createOne([
        'type' => 'App\Notifications\Custom',
        'notifiable_type' => 'Modules\User\Models\User',
        'notifiable_id' => (string) Str::uuid(),
<<<<<<< HEAD
<<<<<<< HEAD
        'data' => $payload]);
=======
        'data' => $payload,
    ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        'data' => $payload]);
>>>>>>> a988596b (first)

    Assert::assertSame($payload, $notification->data);
});
