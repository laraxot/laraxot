<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Models;

use Modules\Notify\Models\Notification;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;
use Modules\User\Models\User;
=======
use Modules\Notify\Tests\TestCase;
use PHPUnit\Framework\Assert;
use Modules\Xot\Tests\XotBasePest;

uses(TestCase::class)->group('notify-db');
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])

describe('Notification Business Logic', function () {
    test('notification extends xot base model', function () {
        $notification = new Notification;
=======
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;

describe('Notification Business Logic', function () {
    test('notification extends xot base model', function () {
        $notification = new Notification();
>>>>>>> a988596b (first)

        Assert::assertInstanceOf(Notification::class, $notification);
    });

    test('notification can store polymorphic notifiable relationships', function () {
        $notification = new Notification([
            'notifiable_type' => 'App\\Models\\User',
<<<<<<< HEAD
<<<<<<< HEAD
            'notifiable_id' => 1]);
=======
            'notifiable_id' => 1,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'notifiable_id' => 1]);
>>>>>>> a988596b (first)

        Assert::assertSame('App\\Models\\User', $notification->notifiable_type);
        Assert::assertSame(1, $notification->notifiable_id);
    });

    test('notification has notification type', function () {
        $notification = new Notification([
<<<<<<< HEAD
<<<<<<< HEAD
            'type' => 'App\\Notifications\\OrderConfirmation']);
=======
            'type' => 'App\\Notifications\\OrderConfirmation',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'type' => 'App\\Notifications\\OrderConfirmation']);
>>>>>>> a988596b (first)

        Assert::assertSame('App\\Notifications\\OrderConfirmation', $notification->type);
    });

    test('notification can store data payload', function () {
        $notification = new Notification([
<<<<<<< HEAD
<<<<<<< HEAD
            'data' => ['title' => 'Test', 'message' => 'Hello World']]);
=======
            'data' => ['title' => 'Test', 'message' => 'Hello World'],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'data' => ['title' => 'Test', 'message' => 'Hello World']]);
>>>>>>> a988596b (first)

        $data = XotBasePest::assertArray($notification->data);
        Assert::assertSame('Test', $data['title']);
    });

    test('notification can track read status', function () {
        $notification = new Notification([
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'read_at' => '2023-01-01 12:00:00']);

        Assert::assertSame('2023-01-01 12:00:00', $notification->read_at instanceof \DateTimeInterface
            ? $notification->read_at->format('Y-m-d H:i:s')
            : (is_string($notification->read_at) ? $notification->read_at : ''));
<<<<<<< HEAD
=======
            'read_at' => '2023-01-01 12:00:00',
        ]);

        Assert::assertSame('2023-01-01 12:00:00', (string) $notification->read_at);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    });

    test('notification can track tenant and user', function () {
        $notification = new Notification([
            'tenant_id' => 1,
<<<<<<< HEAD
<<<<<<< HEAD
            'user_id' => 5]);
=======
            'user_id' => 5,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'user_id' => 5]);
>>>>>>> a988596b (first)

        Assert::assertSame(1, $notification->tenant_id);
        Assert::assertSame(5, $notification->user_id);
    });

    test('notification can store polymorphic subject relationships', function () {
        $notification = new Notification([
            'subject_type' => 'App\\Models\\Order',
<<<<<<< HEAD
<<<<<<< HEAD
            'subject_id' => 123]);
=======
            'subject_id' => 123,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'subject_id' => 123]);
>>>>>>> a988596b (first)

        Assert::assertSame('App\\Models\\Order', $notification->subject_type);
        Assert::assertSame(123, $notification->subject_id);
    });

    test('notification can track multiple channels', function () {
        $notification = new Notification([
<<<<<<< HEAD
<<<<<<< HEAD
            'channels' => ['mail', 'sms', 'database']]);
=======
            'channels' => ['mail', 'sms', 'database'],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'channels' => ['mail', 'sms', 'database']]);
>>>>>>> a988596b (first)

        $channels = XotBasePest::assertArray($notification->channels);
        Assert::assertContains('mail', $channels);
        Assert::assertContains('sms', $channels);
    });

    test('notification can track status and sent time', function () {
        $notification = new Notification([
            'status' => 'sent',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'sent_at' => '2023-01-01 14:00:00']);

        Assert::assertSame('sent', $notification->status);
        Assert::assertSame('2023-01-01 14:00:00', $notification->sent_at instanceof \DateTimeInterface
            ? $notification->sent_at->format('Y-m-d H:i:s')
            : (is_string($notification->sent_at) ? $notification->sent_at : ''));
<<<<<<< HEAD
=======
            'sent_at' => '2023-01-01 14:00:00',
        ]);

        Assert::assertSame('sent', $notification->status);
        Assert::assertSame('2023-01-01 14:00:00', (string) $notification->sent_at);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    });

    test('notification has factory for testing', function () {
        $reflection = new \ReflectionClass(Notification::class);

        Assert::assertTrue($reflection->hasMethod('factory'));
    });
});
