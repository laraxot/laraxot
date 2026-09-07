<?php

declare(strict_types=1);

namespace Modules\Notify\Tests\Unit\Actions;

use Illuminate\Notifications\AnonymousNotifiable;
use Illuminate\Notifications\Notification as IlluminateNotification;
use Illuminate\Support\Facades\Notification;
use InvalidArgumentException;
use Modules\Notify\Actions\SendNotificationToRecipientAction;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;
=======
use Modules\Notify\Tests\TestCase;
use PHPUnit\Framework\Assert;
use Modules\Xot\Tests\XotBasePest;

uses(TestCase::class)->group('no-notify-db');
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])

function makeDummyNotificationForRecipient(): IlluminateNotification
{
    return new class extends IlluminateNotification
=======
use Modules\Xot\Tests\XotBasePest;
use PHPUnit\Framework\Assert;

function makeDummyNotificationForRecipient(): IlluminateNotification
{
    return new class() extends IlluminateNotification
>>>>>>> a988596b (first)
    {
        /** @return list<string> */
        public function via(object $notifiable): array
        {
            return ['mail'];
        }
    };
}

test('send notification to recipient returns true and routes mail', function () {
    Notification::fake();
    $notification = makeDummyNotificationForRecipient();

    $result = app(SendNotificationToRecipientAction::class)->execute(
        'user@example.test',
        $notification,
    );

    Assert::assertTrue($result);
    Notification::assertSentOnDemand(
        $notification::class,
        static function (IlluminateNotification $notification, array $channels, AnonymousNotifiable $notifiable): bool {
            return ($notifiable->routes['mail'] ?? null) === 'user@example.test';
        }
    );
});

test('send notification to recipient throws for invalid email', function () {
    XotBasePest::assertThrows(
        fn () => app(SendNotificationToRecipientAction::class)->execute(
            'invalid-email',
            makeDummyNotificationForRecipient(),
        ),
        InvalidArgumentException::class,
    );
});
