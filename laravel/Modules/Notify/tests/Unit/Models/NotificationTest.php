<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Models;

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

describe('Notification', function (): void {
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

<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        Assert::assertNotNull(\assertFreshModel($notification, Notification::class)->read_at);
        \assertNotifyTableHas('notifications', [
            'id' => $notification->id,
            'read_at' => \assertFreshModel($notification, Notification::class)->read_at]);
<<<<<<< HEAD
=======
        Assert::assertNotNull(XotBasePest::assertFreshModel($notification, Notification::class)->read_at);
        XotBasePest::assertTableHas('notify', 'notifications', [
            'id' => $notification->id,
            'read_at' => XotBasePest::assertFreshModel($notification, Notification::class)->read_at,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
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
=======
>>>>>>> a988596b (first)
            'status' => 'sent']);

        Assert::assertNotNull(\assertFreshModel($notification, Notification::class)->sent_at);
        Assert::assertEquals('sent', \assertFreshModel($notification, Notification::class)->status);
        \assertNotifyTableHas('notifications', [
            'id' => $notification->id,
            'sent_at' => \assertFreshModel($notification, Notification::class)->sent_at,
            'status' => 'sent']);
<<<<<<< HEAD
=======
            'status' => 'sent',
        ]);

        Assert::assertNotNull(XotBasePest::assertFreshModel($notification, Notification::class)->sent_at);
        Assert::assertEquals('sent', XotBasePest::assertFreshModel($notification, Notification::class)->status);
        XotBasePest::assertTableHas('notify', 'notifications', [
            'id' => $notification->id,
            'sent_at' => XotBasePest::assertFreshModel($notification, Notification::class)->sent_at,
            'status' => 'sent',
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
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
=======
>>>>>>> a988596b (first)
            'status' => 'sent']);

        Assert::assertEquals('Updated message', \assertFreshModel($notification, Notification::class)->message);
        Assert::assertEquals('warning', \assertFreshModel($notification, Notification::class)->type);
        Assert::assertEquals('sent', \assertFreshModel($notification, Notification::class)->status);
        Assert::assertEquals(['updated' => true], \assertFreshModel($notification, Notification::class)->data);
<<<<<<< HEAD
=======
            'status' => 'sent',
        ]);

        Assert::assertEquals('Updated message', XotBasePest::assertFreshModel($notification, Notification::class)->message);
        Assert::assertEquals('warning', XotBasePest::assertFreshModel($notification, Notification::class)->type);
        Assert::assertEquals('sent', XotBasePest::assertFreshModel($notification, Notification::class)->status);
        Assert::assertEquals(['updated' => true], XotBasePest::assertFreshModel($notification, Notification::class)->data);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
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
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertEquals('info', \assertFirstModel($infoNotifications, Notification::class)->type);
        Assert::assertEquals('warning', \assertFirstModel($warningNotifications, Notification::class)->type);
        Assert::assertEquals('error', \assertFirstModel($errorNotifications, Notification::class)->type);
=======
        Assert::assertEquals('info', XotBasePest::assertFirstModel($infoNotifications, Notification::class)->type);
        Assert::assertEquals('warning', XotBasePest::assertFirstModel($warningNotifications, Notification::class)->type);
        Assert::assertEquals('error', XotBasePest::assertFirstModel($errorNotifications, Notification::class)->type);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        Assert::assertEquals('info', \assertFirstModel($infoNotifications, Notification::class)->type);
        Assert::assertEquals('warning', \assertFirstModel($warningNotifications, Notification::class)->type);
        Assert::assertEquals('error', \assertFirstModel($errorNotifications, Notification::class)->type);
>>>>>>> a988596b (first)
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
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertEquals('pending', \assertFirstModel($pendingNotifications, Notification::class)->status);
        Assert::assertEquals('sent', \assertFirstModel($sentNotifications, Notification::class)->status);
        Assert::assertEquals('failed', \assertFirstModel($failedNotifications, Notification::class)->status);
=======
        Assert::assertEquals('pending', XotBasePest::assertFirstModel($pendingNotifications, Notification::class)->status);
        Assert::assertEquals('sent', XotBasePest::assertFirstModel($sentNotifications, Notification::class)->status);
        Assert::assertEquals('failed', XotBasePest::assertFirstModel($failedNotifications, Notification::class)->status);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        Assert::assertEquals('pending', \assertFirstModel($pendingNotifications, Notification::class)->status);
        Assert::assertEquals('sent', \assertFirstModel($sentNotifications, Notification::class)->status);
        Assert::assertEquals('failed', \assertFirstModel($failedNotifications, Notification::class)->status);
>>>>>>> a988596b (first)
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
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertEquals(1, \assertFirstModel($tenant1Notifications, Notification::class)->tenant_id);
        Assert::assertEquals(1, \assertFirstModel($tenant1Notifications->slice(1), Notification::class)->tenant_id);
        Assert::assertEquals(2, \assertFirstModel($tenant2Notifications, Notification::class)->tenant_id);
=======
        Assert::assertEquals(1, XotBasePest::assertFirstModel($tenant1Notifications, Notification::class)->tenant_id);
        Assert::assertEquals(1, XotBasePest::assertFirstModel($tenant1Notifications->slice(1), Notification::class)->tenant_id);
        Assert::assertEquals(2, XotBasePest::assertFirstModel($tenant2Notifications, Notification::class)->tenant_id);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        Assert::assertEquals(1, \assertFirstModel($tenant1Notifications, Notification::class)->tenant_id);
        Assert::assertEquals(1, \assertFirstModel($tenant1Notifications->slice(1), Notification::class)->tenant_id);
        Assert::assertEquals(2, \assertFirstModel($tenant2Notifications, Notification::class)->tenant_id);
>>>>>>> a988596b (first)
    });

    test('_can_find_by_user_id', function (): void {
        NotificationFactory::new()->createOne([
            'message' => 'User 123 notification',
            'type' => 'info',
<<<<<<< HEAD
<<<<<<< HEAD
            'user_id' => 123]);
=======
            'user_id' => 123,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'user_id' => 123]);
>>>>>>> a988596b (first)

        NotificationFactory::new()->createOne([
            'message' => 'User 456 notification',
            'type' => 'info',
<<<<<<< HEAD
<<<<<<< HEAD
            'user_id' => 456]);
=======
            'user_id' => 456,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'user_id' => 456]);
>>>>>>> a988596b (first)

        NotificationFactory::new()->createOne([
            'message' => 'User 123 another notification',
            'type' => 'warning',
<<<<<<< HEAD
<<<<<<< HEAD
            'user_id' => 123]);
=======
            'user_id' => 123,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'user_id' => 123]);
>>>>>>> a988596b (first)

        $user123Notifications = Notification::where('user_id', 123)->get();
        $user456Notifications = Notification::where('user_id', 456)->get();

        Assert::assertCount(2, $user123Notifications);
        Assert::assertCount(1, $user456Notifications);
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertEquals(123, \assertFirstModel($user123Notifications, Notification::class)->user_id);
        Assert::assertEquals(123, \assertFirstModel($user123Notifications->slice(1), Notification::class)->user_id);
        Assert::assertEquals(456, \assertFirstModel($user456Notifications, Notification::class)->user_id);
=======
        Assert::assertEquals(123, XotBasePest::assertFirstModel($user123Notifications, Notification::class)->user_id);
        Assert::assertEquals(123, XotBasePest::assertFirstModel($user123Notifications->slice(1), Notification::class)->user_id);
        Assert::assertEquals(456, XotBasePest::assertFirstModel($user456Notifications, Notification::class)->user_id);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        Assert::assertEquals(123, \assertFirstModel($user123Notifications, Notification::class)->user_id);
        Assert::assertEquals(123, \assertFirstModel($user123Notifications->slice(1), Notification::class)->user_id);
        Assert::assertEquals(456, \assertFirstModel($user456Notifications, Notification::class)->user_id);
>>>>>>> a988596b (first)
    });

    test('_can_find_by_subject', function (): void {
        NotificationFactory::new()->createOne([
            'message' => 'User subject notification',
            'type' => 'info',
            'subject_type' => 'App\Models\User',
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

        NotificationFactory::new()->createOne([
            'message' => 'Company subject notification',
            'type' => 'info',
            'subject_type' => 'App\Models\Company',
<<<<<<< HEAD
<<<<<<< HEAD
            'subject_id' => 456]);
=======
            'subject_id' => 456,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'subject_id' => 456]);
>>>>>>> a988596b (first)

        NotificationFactory::new()->createOne([
            'message' => 'User subject another notification',
            'type' => 'warning',
            'subject_type' => 'App\Models\User',
<<<<<<< HEAD
<<<<<<< HEAD
            'subject_id' => 789]);
=======
            'subject_id' => 789,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'subject_id' => 789]);
>>>>>>> a988596b (first)

        $userSubjectNotifications = Notification::where('subject_type', 'App\Models\User')->get();
        $companySubjectNotifications = Notification::where('subject_type', 'App\Models\Company')->get();

        Assert::assertCount(2, $userSubjectNotifications);
        Assert::assertCount(1, $companySubjectNotifications);
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertEquals('App\Models\User', \assertFirstModel($userSubjectNotifications, Notification::class)->subject_type);
        Assert::assertEquals('App\Models\User', \assertFirstModel($userSubjectNotifications->slice(1), Notification::class)->subject_type);
        Assert::assertEquals('App\Models\Company', \assertFirstModel($companySubjectNotifications, Notification::class)->subject_type);
=======
        Assert::assertEquals('App\Models\User', XotBasePest::assertFirstModel($userSubjectNotifications, Notification::class)->subject_type);
        Assert::assertEquals('App\Models\User', XotBasePest::assertFirstModel($userSubjectNotifications->slice(1), Notification::class)->subject_type);
        Assert::assertEquals('App\Models\Company', XotBasePest::assertFirstModel($companySubjectNotifications, Notification::class)->subject_type);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        Assert::assertEquals('App\Models\User', \assertFirstModel($userSubjectNotifications, Notification::class)->subject_type);
        Assert::assertEquals('App\Models\User', \assertFirstModel($userSubjectNotifications->slice(1), Notification::class)->subject_type);
        Assert::assertEquals('App\Models\Company', \assertFirstModel($companySubjectNotifications, Notification::class)->subject_type);
>>>>>>> a988596b (first)
    });

    test('_can_find_by_channel', function (): void {
        NotificationFactory::new()->createOne([
            'message' => 'Mail notification',
            'type' => 'info',
<<<<<<< HEAD
<<<<<<< HEAD
            'channels' => ['mail']]);
=======
            'channels' => ['mail'],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'channels' => ['mail']]);
>>>>>>> a988596b (first)

        NotificationFactory::new()->createOne([
            'message' => 'SMS notification',
            'type' => 'info',
<<<<<<< HEAD
<<<<<<< HEAD
            'channels' => ['sms']]);
=======
            'channels' => ['sms'],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'channels' => ['sms']]);
>>>>>>> a988596b (first)

        NotificationFactory::new()->createOne([
            'message' => 'Multi-channel notification',
            'type' => 'info',
<<<<<<< HEAD
<<<<<<< HEAD
            'channels' => ['mail', 'database', 'sms']]);
=======
            'channels' => ['mail', 'database', 'sms'],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'channels' => ['mail', 'database', 'sms']]);
>>>>>>> a988596b (first)

        $mailNotifications = Notification::whereJsonContains('channels', 'mail')->get();
        $smsNotifications = Notification::whereJsonContains('channels', 'sms')->get();
        $databaseNotifications = Notification::whereJsonContains('channels', 'database')->get();

        Assert::assertCount(2, $mailNotifications);
        Assert::assertCount(2, $smsNotifications);
        Assert::assertCount(1, $databaseNotifications);
    });

    test('_can_find_by_data_pattern', function (): void {
        NotificationFactory::new()->createOne([
            'message' => 'High priority notification',
            'type' => 'alert',
            'data' => [
                'priority' => 'high',
<<<<<<< HEAD
<<<<<<< HEAD
                'category' => 'security']]);
=======
                'category' => 'security',
            ],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'category' => 'security']]);
>>>>>>> a988596b (first)

        NotificationFactory::new()->createOne([
            'message' => 'Low priority notification',
            'type' => 'info',
            'data' => [
                'priority' => 'low',
<<<<<<< HEAD
<<<<<<< HEAD
                'category' => 'general']]);
=======
                'category' => 'general',
            ],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'category' => 'general']]);
>>>>>>> a988596b (first)

        NotificationFactory::new()->createOne([
            'message' => 'Medium priority notification',
            'type' => 'warning',
            'data' => [
                'priority' => 'medium',
<<<<<<< HEAD
<<<<<<< HEAD
                'category' => 'maintenance']]);
=======
                'category' => 'maintenance',
            ],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'category' => 'maintenance']]);
>>>>>>> a988596b (first)

        $highPriorityNotifications = Notification::whereJsonPath('data.priority', 'high')->get();
        $securityNotifications = Notification::whereJsonPath('data.category', 'security')->get();

        Assert::assertCount(1, $highPriorityNotifications);
        Assert::assertCount(1, $securityNotifications);
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertEquals('high', \assertFirstModel($highPriorityNotifications, Notification::class)->data['priority']);
        Assert::assertEquals('security', \assertFirstModel($securityNotifications, Notification::class)->data['category']);
=======
        Assert::assertEquals('high', XotBasePest::assertFirstModel($highPriorityNotifications, Notification::class)->data['priority']);
        Assert::assertEquals('security', XotBasePest::assertFirstModel($securityNotifications, Notification::class)->data['category']);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        Assert::assertEquals('high', \assertFirstModel($highPriorityNotifications, Notification::class)->data['priority']);
        Assert::assertEquals('security', \assertFirstModel($securityNotifications, Notification::class)->data['category']);
>>>>>>> a988596b (first)
    });

    test('_can_find_by_read_status', function (): void {
        NotificationFactory::new()->createOne([
            'message' => 'Unread notification',
            'type' => 'info',
<<<<<<< HEAD
<<<<<<< HEAD
            'read_at' => null]);
=======
            'read_at' => null,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'read_at' => null]);
>>>>>>> a988596b (first)

        NotificationFactory::new()->createOne([
            'message' => 'Read notification',
            'type' => 'info',
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

        NotificationFactory::new()->createOne([
            'message' => 'Another unread notification',
            'type' => 'warning',
<<<<<<< HEAD
<<<<<<< HEAD
            'read_at' => null]);
=======
            'read_at' => null,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'read_at' => null]);
>>>>>>> a988596b (first)

        $unreadNotifications = Notification::whereNull('read_at')->get();
        $readNotifications = Notification::whereNotNull('read_at')->get();

        Assert::assertCount(2, $unreadNotifications);
        Assert::assertCount(1, $readNotifications);
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertNull(\assertFirstModel($unreadNotifications, Notification::class)->read_at);
        Assert::assertNull(\assertFirstModel($unreadNotifications, Notification::class)->read_at);
        Assert::assertNotNull(\assertFirstModel($readNotifications, Notification::class)->read_at);
=======
        Assert::assertNull(XotBasePest::assertFirstModel($unreadNotifications, Notification::class)->read_at);
        Assert::assertNull(XotBasePest::assertFirstModel($unreadNotifications, Notification::class)->read_at);
        Assert::assertNotNull(XotBasePest::assertFirstModel($readNotifications, Notification::class)->read_at);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        Assert::assertNull(\assertFirstModel($unreadNotifications, Notification::class)->read_at);
        Assert::assertNull(\assertFirstModel($unreadNotifications, Notification::class)->read_at);
        Assert::assertNotNull(\assertFirstModel($readNotifications, Notification::class)->read_at);
>>>>>>> a988596b (first)
    });

    test('_can_find_by_sent_status', function (): void {
        NotificationFactory::new()->createOne([
            'message' => 'Unsent notification',
            'type' => 'info',
<<<<<<< HEAD
<<<<<<< HEAD
            'sent_at' => null]);
=======
            'sent_at' => null,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'sent_at' => null]);
>>>>>>> a988596b (first)

        NotificationFactory::new()->createOne([
            'message' => 'Sent notification',
            'type' => 'info',
<<<<<<< HEAD
<<<<<<< HEAD
            'sent_at' => now()]);
=======
            'sent_at' => now(),
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'sent_at' => now()]);
>>>>>>> a988596b (first)

        NotificationFactory::new()->createOne([
            'message' => 'Another unsent notification',
            'type' => 'warning',
<<<<<<< HEAD
<<<<<<< HEAD
            'sent_at' => null]);
=======
            'sent_at' => null,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'sent_at' => null]);
>>>>>>> a988596b (first)

        $unsentNotifications = Notification::whereNull('sent_at')->get();
        $sentNotifications = Notification::whereNotNull('sent_at')->get();

        Assert::assertCount(2, $unsentNotifications);
        Assert::assertCount(1, $sentNotifications);
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertNull(\assertFirstModel($unsentNotifications, Notification::class)->sent_at);
        Assert::assertNull(\assertFirstModel($unsentNotifications, Notification::class)->sent_at);
        Assert::assertNotNull(\assertFirstModel($sentNotifications, Notification::class)->sent_at);
=======
        Assert::assertNull(XotBasePest::assertFirstModel($unsentNotifications, Notification::class)->sent_at);
        Assert::assertNull(XotBasePest::assertFirstModel($unsentNotifications, Notification::class)->sent_at);
        Assert::assertNotNull(XotBasePest::assertFirstModel($sentNotifications, Notification::class)->sent_at);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        Assert::assertNull(\assertFirstModel($unsentNotifications, Notification::class)->sent_at);
        Assert::assertNull(\assertFirstModel($unsentNotifications, Notification::class)->sent_at);
        Assert::assertNotNull(\assertFirstModel($sentNotifications, Notification::class)->sent_at);
>>>>>>> a988596b (first)
    });

    test('_can_find_by_date_range', function (): void {
        $yesterday = now()->subDay();
        $today = now();
        $tomorrow = now()->addDay();

        NotificationFactory::new()->createOne([
            'message' => 'Yesterday notification',
            'type' => 'info',
<<<<<<< HEAD
<<<<<<< HEAD
            'created_at' => $yesterday]);
=======
            'created_at' => $yesterday,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'created_at' => $yesterday]);
>>>>>>> a988596b (first)

        NotificationFactory::new()->createOne([
            'message' => 'Today notification',
            'type' => 'info',
<<<<<<< HEAD
<<<<<<< HEAD
            'created_at' => $today]);
=======
            'created_at' => $today,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'created_at' => $today]);
>>>>>>> a988596b (first)

        NotificationFactory::new()->createOne([
            'message' => 'Tomorrow notification',
            'type' => 'info',
<<<<<<< HEAD
<<<<<<< HEAD
            'created_at' => $tomorrow]);
=======
            'created_at' => $tomorrow,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'created_at' => $tomorrow]);
>>>>>>> a988596b (first)

        $todayNotifications = Notification::whereDate('created_at', $today->toDateString())->get();
        $recentNotifications = Notification::where('created_at', '>=', $yesterday)->get();

        Assert::assertCount(1, $todayNotifications);
        Assert::assertCount(2, $recentNotifications); // yesterday and today
<<<<<<< HEAD
<<<<<<< HEAD
        Assert::assertEquals('Today notification', \assertFirstModel($todayNotifications, Notification::class)->message);
=======
        Assert::assertEquals('Today notification', XotBasePest::assertFirstModel($todayNotifications, Notification::class)->message);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        Assert::assertEquals('Today notification', \assertFirstModel($todayNotifications, Notification::class)->message);
>>>>>>> a988596b (first)
    });

    test('_can_find_by_multiple_criteria', function (): void {
        NotificationFactory::new()->createOne([
            'message' => 'High priority security alert',
            'type' => 'alert',
            'status' => 'pending',
            'tenant_id' => 1,
            'data' => [
                'priority' => 'high',
<<<<<<< HEAD
<<<<<<< HEAD
                'category' => 'security']]);
=======
                'category' => 'security',
            ],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'category' => 'security']]);
>>>>>>> a988596b (first)

        NotificationFactory::new()->createOne([
            'message' => 'Low priority general info',
            'type' => 'info',
            'status' => 'sent',
            'tenant_id' => 1,
            'data' => [
                'priority' => 'low',
<<<<<<< HEAD
<<<<<<< HEAD
                'category' => 'general']]);
=======
                'category' => 'general',
            ],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'category' => 'general']]);
>>>>>>> a988596b (first)

        NotificationFactory::new()->createOne([
            'message' => 'Medium priority maintenance warning',
            'type' => 'warning',
            'status' => 'pending',
            'tenant_id' => 2,
            'data' => [
                'priority' => 'medium',
<<<<<<< HEAD
<<<<<<< HEAD
                'category' => 'maintenance']]);
=======
                'category' => 'maintenance',
            ],
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                'category' => 'maintenance']]);
>>>>>>> a988596b (first)

        $pendingHighPriorityTenant1 = Notification::where('status', 'pending')
            ->where('tenant_id', 1)
            ->whereJsonPath('data.priority', 'high')
            ->get();

        Assert::assertCount(1, $pendingHighPriorityTenant1);
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
        Assert::assertEquals('High priority security alert', \assertFirstModel($pendingHighPriorityTenant1, Notification::class)->message);
        Assert::assertEquals('pending', \assertFirstModel($pendingHighPriorityTenant1, Notification::class)->status);
        Assert::assertEquals(1, \assertFirstModel($pendingHighPriorityTenant1, Notification::class)->tenant_id);
        Assert::assertEquals('high', \notifyArrayGet(\assertFirstModel($pendingHighPriorityTenant1, Notification::class)->data, 'priority'));
<<<<<<< HEAD
=======
        Assert::assertEquals('High priority security alert', XotBasePest::assertFirstModel($pendingHighPriorityTenant1, Notification::class)->message);
        Assert::assertEquals('pending', XotBasePest::assertFirstModel($pendingHighPriorityTenant1, Notification::class)->status);
        Assert::assertEquals(1, XotBasePest::assertFirstModel($pendingHighPriorityTenant1, Notification::class)->tenant_id);
        Assert::assertEquals('high', TestCase::notifyArrayGet(XotBasePest::assertFirstModel($pendingHighPriorityTenant1, Notification::class)->data, 'priority'));
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    });

    test('_can_handle_empty_data', function (): void {
        $notification = NotificationFactory::new()->createOne([
            'message' => 'Empty data notification',
            'type' => 'info',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'data' => []]);
        XotBasePest::assertTableHas('notify', 'notifications', [
            'id' => $notification->id,
            'data' => json_encode([])]);
<<<<<<< HEAD
=======
            'data' => [],
        ]);
        XotBasePest::assertTableHas('notify', 'notifications', [
            'id' => $notification->id,
            'data' => json_encode([]),
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        Assert::assertEmpty($notification->data);
    });

    test('_can_handle_empty_channels', function (): void {
        $notification = NotificationFactory::new()->createOne([
            'message' => 'No channels notification',
            'type' => 'info',
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
            'channels' => []]);
        XotBasePest::assertTableHas('notify', 'notifications', [
            'id' => $notification->id,
            'channels' => json_encode([])]);
<<<<<<< HEAD
=======
            'channels' => [],
        ]);
        XotBasePest::assertTableHas('notify', 'notifications', [
            'id' => $notification->id,
            'channels' => json_encode([]),
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
        Assert::assertEmpty($notification->channels);
    });

    test('_can_handle_null_values', function (): void {
        $notification = NotificationFactory::new()->createOne([
            'message' => 'Null values notification',
            'type' => 'info',
            'tenant_id' => null,
            'user_id' => null,
            'subject_type' => null,
            'subject_id' => null,
            'channels' => null,
            'status' => null,
            'sent_at' => null,
<<<<<<< HEAD
<<<<<<< HEAD
            'data' => null]);
=======
            'data' => null,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'data' => null]);
>>>>>>> a988596b (first)

        Assert::assertNull($notification->tenant_id);
        Assert::assertNull($notification->user_id);
        Assert::assertNull($notification->subject_type);
        Assert::assertNull($notification->subject_id);
        Assert::assertNull($notification->channels);
        Assert::assertNull($notification->status);
        Assert::assertNull($notification->sent_at);
        Assert::assertNull($notification->data);
    });
});
