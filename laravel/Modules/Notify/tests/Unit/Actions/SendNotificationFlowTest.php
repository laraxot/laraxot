<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Actions;

use Exception;
use Illuminate\Database\Eloquent\Collection;
<<<<<<< HEAD
<<<<<<< HEAD
use Mockery;
=======
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use Mockery;
>>>>>>> a988596b (first)
use Modules\Notify\Actions\SendNotificationAction;
use Modules\Notify\Database\Factories\NotificationFactory;
use Modules\Notify\Database\Factories\NotificationTemplateFactory;
use Modules\Notify\Models\Notification;
use Modules\Notify\Models\NotificationTemplate;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\User\Database\Factories\UserFactory;
use PHPUnit\Framework\Assert;
use Modules\User\Models\User;
=======
use Modules\Notify\Tests\TestCase;
use Modules\User\Database\Factories\UserFactory;
use PHPUnit\Framework\Assert;

uses(TestCase::class)->group('notify-db');
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use Modules\User\Database\Factories\UserFactory;
use PHPUnit\Framework\Assert;
>>>>>>> a988596b (first)

describe('Send notification flow', function (): void {
    test('template lookup returns null when code missing', function (): void {
        $result = NotificationTemplate::query()
            ->where('code', 'missing-template-code')
            ->where('is_active', true)
            ->first();

        Assert::assertNull($result);
    });

    test('template lookup returns model when code exists', function (): void {
        $template = NotificationTemplateFactory::new()->createOne([
            'code' => 'send-test-template',
<<<<<<< HEAD
<<<<<<< HEAD
            'is_active' => true]);
=======
            'is_active' => true,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'is_active' => true]);
>>>>>>> a988596b (first)

        $result = NotificationTemplate::query()
            ->where('code', 'send-test-template')
            ->where('is_active', true)
            ->first();

        Assert::assertInstanceOf(NotificationTemplate::class, $result);
        Assert::assertSame($template->id, $result->id);
    });

    test('returns collection from category query', function (): void {
        $result = NotificationTemplate::query()
            ->where('category', 'general')
            ->where('is_active', true)
            ->get();

        Assert::assertInstanceOf(Collection::class, $result);
    });

    test('send action can be invoked with mocked handle', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
        NotificationTemplateFactory::new()->createOne([
            'code' => 'action-send-template',
            'is_active' => true]);
=======
        /** @var TestCase $this */
        NotificationTemplateFactory::new()->createOne([
            'code' => 'action-send-template',
            'is_active' => true,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        NotificationTemplateFactory::new()->createOne([
            'code' => 'action-send-template',
            'is_active' => true]);
>>>>>>> a988596b (first)

        $recipient = UserFactory::new()->createOne();
        $notification = NotificationFactory::new()->createOne();

        $calls = 0;
<<<<<<< HEAD
<<<<<<< HEAD
        $action = Mockery::mock(SendNotificationAction::class);
        $action->shouldReceive('handle')->andReturnUsing(function () use (&$calls, $notification): Notification {
=======
        $action = $this->createUnitMock(SendNotificationAction::class);
        $action->method('handle')->willReturnCallback(function () use (&$calls, $notification): Notification {
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        $action = Mockery::mock(SendNotificationAction::class);
        $action->shouldReceive('handle')->andReturnUsing(function () use (&$calls, $notification): Notification {
>>>>>>> a988596b (first)
            $calls++;

            return $notification;
        });

        app()->instance(SendNotificationAction::class, $action);

        $result = app(SendNotificationAction::class)->handle(
            $recipient,
            'action-send-template',
            [],
            [],
            [],
        );

        Assert::assertSame(1, $calls);
        Assert::assertInstanceOf(Notification::class, $result);
    });

    test('send action throws when template missing', function (): void {
<<<<<<< HEAD
<<<<<<< HEAD
        $recipient = UserFactory::new()->createOne();

        expect(fn () => app(SendNotificationAction::class)->handle(
=======
        /** @var TestCase $this */
        $this->expectApplicationException(Exception::class);

        $recipient = UserFactory::new()->createOne();

        app(SendNotificationAction::class)->handle(
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        $recipient = UserFactory::new()->createOne();

        expect(fn () => app(SendNotificationAction::class)->handle(
>>>>>>> a988596b (first)
            $recipient,
            'invalid_template',
            [],
            [],
            [],
<<<<<<< HEAD
<<<<<<< HEAD
        ))->toThrow(Exception::class);
=======
        );
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
        ))->toThrow(Exception::class);
>>>>>>> a988596b (first)
    });
});
