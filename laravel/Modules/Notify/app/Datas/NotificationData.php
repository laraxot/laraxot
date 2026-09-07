<?php

declare(strict_types=1);

namespace Modules\Notify\Datas;

use Illuminate\Notifications\Notification;
use Modules\Notify\Models\Notification as NotificationModel;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class NotificationData extends Data
{
    // public string $mobile_phone;
    // public string $token;
    // public int $q;
    public string $from;

    public ?string $from_email = null;

    public string $recipient;

    public ?string $subject = null;

    public ?string $body_html = null;

    public string $body;

    /** @var list<string> */
    public array $channels = [];

    /**
     * @var DataCollection<int, AttachmentData>
     */
    public ?DataCollection $attachments = null;

    // public ?array $attachment_paths = [];

    /**
     * Get the notification routing information for the given driver.
     */
    public function routeNotificationFor(string $driver, Notification $notification): string|NotificationModel
    {
        // dddx(['driver'=>$driver,'a'=>$a]);
        // return $this->routes[$driver] ?? null;
        if ($driver === 'database') {
            return app(NotificationModel::class);
        }

        return $this->recipient;
    }

    public function getSmsData(): SmsData
    {
        return SmsData::from([
            'from' => $this->from,
            'recipient' => $this->recipient,
<<<<<<< HEAD
<<<<<<< HEAD
            'body' => $this->body]);
=======
            'body' => $this->body,
        ]);
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'body' => $this->body]);
>>>>>>> a988596b (first)
    }
}
