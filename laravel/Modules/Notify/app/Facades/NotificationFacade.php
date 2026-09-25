<?php

declare(strict_types=1);

namespace Modules\Notify\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Facade for Notify module services.
 * Provides unified interface for notifications across channels (email, SMS, push).
 *
 * @method static void sendEmail(string|array<string, mixed> $recipient, string $subject, string $body, array<string, mixed> $data = [])
 * @method static void sendSms(string $phone, string $message)
 * @method static void sendPushNotification(string $userId, string $title, string $body)
 * @method static void dispatch(string $notificationClass, array<int|string, mixed> $recipients, array<string, mixed> $data = [])
 *
 * @see \Modules\Notify\Services\NotificationService
 */
class NotificationFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'notify.service';
    }
}
