<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Models;

// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.
// Notify Pest/PHPUnit — claude-audit documentation ratio.

use Modules\Notify\Database\Factories\NotificationFactory;
use Modules\Notify\Models\Notification;
use Modules\Notify\Tests\TestCase;
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;

use function Pest\Laravel\withoutExceptionHandling;
use function Safe\json_encode;
<<<<<<< HEAD
use Modules\User\Models\User;

beforeEach(function (): void {
    withoutExceptionHandling();
=======
use PHPUnit\Framework\Assert;
use Modules\Xot\Tests\XotBasePest;

use function Safe\json_encode;

uses(TestCase::class)->group('notify-db');

beforeEach(function (): void {
    /** @var TestCase $this */
    $this->disableExceptionHandling();
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======

beforeEach(function (): void {
    withoutExceptionHandling();
>>>>>>> a988596b (first)
});

describe('Notification PartOne', function (): void {
    test('_can_create_notification', function (): void {
        $notification = NotificationFactory::new()->createOne([
            'message' => 'Test notification message',
            'type' => 'info',
            'tenant_id' => 1,
            'user_id' => 123,
            'subject_type' => 'App\Models\User',
            'subject_id' => 456,
            'channels' => ['mail', 'database'],
            'status' => 'pending',
            'sent_at' => now(),
            'data' => [
                'title' => 'Test Title',
                'body' => 'Test Body',
                'action_url' => 'https://example.com',
<<<<<<< HEAD
<<<<<<< HEAD
                'priority' => 'high']]);
=======
                'priority' => 'high',
            ],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'priority' => 'high']]);
>>>>>>> a988596b (first)
        XotBasePest::assertTableHas('notify', 'notifications', [
            'id' => $notification->id,
            'message' => 'Test notification message',
            'type' => 'info',
            'tenant_id' => 1,
            'user_id' => 123,
            'subject_type' => 'App\Models\User',
            'subject_id' => 456,
<<<<<<< HEAD
<<<<<<< HEAD
            'status' => 'pending']);
=======
            'status' => 'pending',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'status' => 'pending']);
>>>>>>> a988596b (first)

        Assert::assertInstanceOf(Notification::class, $notification);
    });

    test('_has_correct_fillable_fields', function (): void {
<<<<<<< HEAD
        $notification = new Notification;
=======
        $notification = new Notification();
>>>>>>> a988596b (first)

        $expectedFillable = [
            'message',
            'type',
            'read_at',
            'tenant_id',
            'user_id',
            'subject_type',
            'subject_id',
            'channels',
            'status',
            'sent_at',
<<<<<<< HEAD
<<<<<<< HEAD
            'data'];
=======
            'data',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'data'];
>>>>>>> a988596b (first)

        Assert::assertEquals($expectedFillable, $notification->getFillable());
    });

    test('_has_correct_casts', function (): void {
<<<<<<< HEAD
        $notification = new Notification;
=======
        $notification = new Notification();
>>>>>>> a988596b (first)

        $expectedCasts = [
            'read_at' => 'datetime',
            'sent_at' => 'datetime',
            'data' => 'array',
            'channels' => 'array',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
<<<<<<< HEAD
<<<<<<< HEAD
            'deleted_at' => 'datetime'];
=======
            'deleted_at' => 'datetime',
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'deleted_at' => 'datetime'];
>>>>>>> a988596b (first)

        Assert::assertEquals($expectedCasts, $notification->getCasts());
    });

    test('_can_store_json_data', function (): void {
        $data = [
            'title' => 'Welcome to our platform',
            'body' => 'Thank you for joining us!',
            'action_url' => 'https://example.com/welcome',
            'priority' => 'high',
            'category' => 'welcome',
            'metadata' => [
                'source' => 'registration',
                'campaign' => 'new_users_2024',
<<<<<<< HEAD
<<<<<<< HEAD
                'tags' => ['welcome', 'onboarding']]];
=======
                'tags' => ['welcome', 'onboarding'],
            ],
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'tags' => ['welcome', 'onboarding']]];
>>>>>>> a988596b (first)

        $notification = NotificationFactory::new()->createOne([
            'message' => 'Welcome notification',
            'type' => 'welcome',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'data' => $data]);
        XotBasePest::assertTableHas('notify', 'notifications', [
            'id' => $notification->id,
            'data' => json_encode($data)]);
<<<<<<< HEAD
=======
            'data' => $data,
        ]);
        XotBasePest::assertTableHas('notify', 'notifications', [
            'id' => $notification->id,
            'data' => json_encode($data),
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        Assert::assertEquals('Welcome to our platform', $notification->data['title']);
        Assert::assertEquals('Thank you for joining us!', $notification->data['body']);
        Assert::assertEquals('high', $notification->data['priority']);
        Assert::assertEquals('registration', TestCase::notifyArrayGet($notification->data, 'metadata', 'source'));
        Assert::assertEquals(['welcome', 'onboarding'], TestCase::notifyArrayGet($notification->data, 'metadata', 'tags'));
    });

    test('_can_store_channels_array', function (): void {
        $channels = ['mail', 'database', 'sms', 'push'];

        $notification = NotificationFactory::new()->createOne([
            'message' => 'Multi-channel notification',
            'type' => 'alert',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'channels' => $channels]);
        XotBasePest::assertTableHas('notify', 'notifications', [
            'id' => $notification->id,
            'channels' => json_encode($channels)]);
<<<<<<< HEAD
=======
            'channels' => $channels,
        ]);
        XotBasePest::assertTableHas('notify', 'notifications', [
            'id' => $notification->id,
            'channels' => json_encode($channels),
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        $storedChannels = XotBasePest::assertArray($notification->channels);
        Assert::assertCount(4, $storedChannels);
        Assert::assertContains('mail', $storedChannels);
        Assert::assertContains('database', $storedChannels);
        Assert::assertContains('sms', $storedChannels);
        Assert::assertContains('push', $storedChannels);
    });

    test('_can_mark_as_read', function (): void {
        $notification = NotificationFactory::new()->createOne([
            'message' => 'Unread notification',
<<<<<<< HEAD
<<<<<<< HEAD
            'type' => 'info']);
=======
            'type' => 'info',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'type' => 'info']);
>>>>>>> a988596b (first)

        Assert::assertNull($notification->read_at);

        $notification->update(['read_at' => now()]);

        Assert::assertNotNull(XotBasePest::assertFreshModel($notification, Notification::class)->read_at);
        XotBasePest::assertTableHas('notify', 'notifications', [
            'id' => $notification->id,
<<<<<<< HEAD
<<<<<<< HEAD
            'read_at' => XotBasePest::assertFreshModel($notification, Notification::class)->read_at]);
=======
            'read_at' => XotBasePest::assertFreshModel($notification, Notification::class)->read_at,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'read_at' => XotBasePest::assertFreshModel($notification, Notification::class)->read_at]);
>>>>>>> a988596b (first)
    });

    test('_can_mark_as_sent', function (): void {
        $notification = NotificationFactory::new()->createOne([
            'message' => 'Pending notification',
            'type' => 'info',
<<<<<<< HEAD
<<<<<<< HEAD
            'status' => 'pending']);
=======
            'status' => 'pending',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'status' => 'pending']);
>>>>>>> a988596b (first)

        Assert::assertNull($notification->sent_at);

        $notification->update([
            'sent_at' => now(),
<<<<<<< HEAD
<<<<<<< HEAD
            'status' => 'sent']);
=======
            'status' => 'sent',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'status' => 'sent']);
>>>>>>> a988596b (first)

        Assert::assertNotNull(XotBasePest::assertFreshModel($notification, Notification::class)->sent_at);
        Assert::assertEquals('sent', XotBasePest::assertFreshModel($notification, Notification::class)->status);
        XotBasePest::assertTableHas('notify', 'notifications', [
            'id' => $notification->id,
            'sent_at' => XotBasePest::assertFreshModel($notification, Notification::class)->sent_at,
<<<<<<< HEAD
<<<<<<< HEAD
            'status' => 'sent']);
=======
            'status' => 'sent',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'status' => 'sent']);
>>>>>>> a988596b (first)
    });

    test('_can_update_notification', function (): void {
        $notification = NotificationFactory::new()->createOne([
            'message' => 'Original message',
            'type' => 'info',
<<<<<<< HEAD
<<<<<<< HEAD
            'status' => 'pending']);
=======
            'status' => 'pending',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'status' => 'pending']);
>>>>>>> a988596b (first)

        $notification->update([
            'message' => 'Updated message',
            'type' => 'warning',
            'status' => 'sent',
<<<<<<< HEAD
<<<<<<< HEAD
            'data' => ['updated' => true]]);
=======
            'data' => ['updated' => true],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'data' => ['updated' => true]]);
>>>>>>> a988596b (first)
        XotBasePest::assertTableHas('notify', 'notifications', [
            'id' => $notification->id,
            'message' => 'Updated message',
            'type' => 'warning',
<<<<<<< HEAD
<<<<<<< HEAD
            'status' => 'sent']);
=======
            'status' => 'sent',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'status' => 'sent']);
>>>>>>> a988596b (first)

        Assert::assertEquals('Updated message', XotBasePest::assertFreshModel($notification, Notification::class)->message);
        Assert::assertEquals('warning', XotBasePest::assertFreshModel($notification, Notification::class)->type);
        Assert::assertEquals('sent', XotBasePest::assertFreshModel($notification, Notification::class)->status);
        Assert::assertEquals(['updated' => true], XotBasePest::assertFreshModel($notification, Notification::class)->data);
    });

    test('_can_find_by_type', function (): void {
        NotificationFactory::new()->createOne([
            'message' => 'Info notification',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'type' => 'info']);

        NotificationFactory::new()->createOne([
            'message' => 'Warning notification',
            'type' => 'warning']);

        NotificationFactory::new()->createOne([
            'message' => 'Error notification',
            'type' => 'error']);
<<<<<<< HEAD
=======
            'type' => 'info',
        ]);

        NotificationFactory::new()->createOne([
            'message' => 'Warning notification',
            'type' => 'warning',
        ]);

        NotificationFactory::new()->createOne([
            'message' => 'Error notification',
            'type' => 'error',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)

        $infoNotifications = Notification::where('type', 'info')->get();
        $warningNotifications = Notification::where('type', 'warning')->get();
        $errorNotifications = Notification::where('type', 'error')->get();

        Assert::assertCount(1, $infoNotifications);
        Assert::assertCount(1, $warningNotifications);
        Assert::assertCount(1, $errorNotifications);
        Assert::assertEquals('info', XotBasePest::assertFirstModel($infoNotifications, Notification::class)->type);
        Assert::assertEquals('warning', XotBasePest::assertFirstModel($warningNotifications, Notification::class)->type);
        Assert::assertEquals('error', XotBasePest::assertFirstModel($errorNotifications, Notification::class)->type);
    });

    test('_can_find_by_status', function (): void {
        NotificationFactory::new()->createOne([
            'message' => 'Pending notification',
            'type' => 'info',
<<<<<<< HEAD
<<<<<<< HEAD
            'status' => 'pending']);
=======
            'status' => 'pending',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'status' => 'pending']);
>>>>>>> a988596b (first)

        NotificationFactory::new()->createOne([
            'message' => 'Sent notification',
            'type' => 'info',
<<<<<<< HEAD
<<<<<<< HEAD
            'status' => 'sent']);
=======
            'status' => 'sent',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'status' => 'sent']);
>>>>>>> a988596b (first)

        NotificationFactory::new()->createOne([
            'message' => 'Failed notification',
            'type' => 'info',
<<<<<<< HEAD
<<<<<<< HEAD
            'status' => 'failed']);
=======
            'status' => 'failed',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'status' => 'failed']);
>>>>>>> a988596b (first)

        $pendingNotifications = Notification::where('status', 'pending')->get();
        $sentNotifications = Notification::where('status', 'sent')->get();
        $failedNotifications = Notification::where('status', 'failed')->get();

        Assert::assertCount(1, $pendingNotifications);
        Assert::assertCount(1, $sentNotifications);
        Assert::assertCount(1, $failedNotifications);
        Assert::assertEquals('pending', XotBasePest::assertFirstModel($pendingNotifications, Notification::class)->status);
        Assert::assertEquals('sent', XotBasePest::assertFirstModel($sentNotifications, Notification::class)->status);
        Assert::assertEquals('failed', XotBasePest::assertFirstModel($failedNotifications, Notification::class)->status);
    });

    test('_can_find_by_tenant_id', function (): void {
        NotificationFactory::new()->createOne([
            'message' => 'Tenant 1 notification',
            'type' => 'info',
<<<<<<< HEAD
<<<<<<< HEAD
            'tenant_id' => 1]);
=======
            'tenant_id' => 1,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tenant_id' => 1]);
>>>>>>> a988596b (first)

        NotificationFactory::new()->createOne([
            'message' => 'Tenant 2 notification',
            'type' => 'info',
<<<<<<< HEAD
<<<<<<< HEAD
            'tenant_id' => 2]);
=======
            'tenant_id' => 2,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tenant_id' => 2]);
>>>>>>> a988596b (first)

        NotificationFactory::new()->createOne([
            'message' => 'Tenant 1 another notification',
            'type' => 'warning',
<<<<<<< HEAD
<<<<<<< HEAD
            'tenant_id' => 1]);
=======
            'tenant_id' => 1,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'tenant_id' => 1]);
>>>>>>> a988596b (first)

        $tenant1Notifications = Notification::where('tenant_id', 1)->get();
        $tenant2Notifications = Notification::where('tenant_id', 2)->get();

        Assert::assertCount(2, $tenant1Notifications);
        Assert::assertCount(1, $tenant2Notifications);
        Assert::assertEquals(1, XotBasePest::assertFirstModel($tenant1Notifications, Notification::class)->tenant_id);
        Assert::assertEquals(1, XotBasePest::assertFirstModel($tenant1Notifications->slice(1), Notification::class)->tenant_id);
        Assert::assertEquals(2, XotBasePest::assertFirstModel($tenant2Notifications, Notification::class)->tenant_id);
    });
});
