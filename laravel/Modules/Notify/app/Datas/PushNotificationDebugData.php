<?php

declare(strict_types=1);

namespace Modules\Notify\Datas;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Support\Str;
use Kreait\Firebase\Messaging\MulticastSendReport;
use Kreait\Firebase\Messaging\SendReport;
use Modules\Notify\Contracts\CanReceivePushNotifications;
use Modules\Notify\Contracts\MobilePushNotification;
use Spatie\LaravelData\Data;

/**
 * @implements Arrayable<string, mixed>
 */
final class PushNotificationDebugData extends Data implements Arrayable
{
    /**
     * @return void
     */
    public function __construct(
        private readonly CanReceivePushNotifications $notifiable,
        private readonly MobilePushNotification $notification,
        private readonly MulticastSendReport $sendReport,
    ) {}

    public static function make(
        CanReceivePushNotifications $notifiable,
        MobilePushNotification $notification,
        MulticastSendReport $sendReport,
    ): self {
        return new self($notifiable, $notification, $sendReport);
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        return [
            'notifiable' => $this->notifiable->getKey(),
            'notifiable_type' => Str::of($this->notifiable::class)->classBasename(),
            'tokens' => $this->notifiable->getMobileDeviceTokens()->toArray(),
            'notification_payload' => $this->notification->toArray(null),
            'response' => [
                'total' => $this->sendReport->count(),
                'successes' => $this->sendReport->successes()->count(),
                'failures' => $this->sendReport->failures()->count(),
                'successes_tokens' => $this->sendReport
                    ->successes()
                    ->map(static fn (SendReport $report): array => [
                        'type' => $report->target()->type(),
<<<<<<< HEAD
<<<<<<< HEAD
                        'value' => $report->target()->value()]),
=======
                        'value' => $report->target()->value(),
                    ]),
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                        'value' => $report->target()->value()]),
>>>>>>> a988596b (first)
                'failure_tokens' => $this->sendReport
                    ->failures()
                    ->map(static fn (SendReport $report): array => [
                        'type' => $report->target()->type(),
<<<<<<< HEAD
<<<<<<< HEAD
                        'value' => $report->target()->value()]),
=======
                        'value' => $report->target()->value(),
                    ]),
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
                        'value' => $report->target()->value()]),
>>>>>>> a988596b (first)
                'unknown_tokens' => $this->sendReport
                    ->filter(static fn (SendReport $report): bool => $report->messageWasSentToUnknownToken())
                    ->map(static fn (SendReport $report): array => [
                        'type' => $report->target()->type(),
<<<<<<< HEAD
<<<<<<< HEAD
=======
>>>>>>> a988596b (first)
                        'value' => $report->target()->value()]),
                'results' => $this->sendReport->map(static fn (SendReport $report): array => [
                    'target' => $report->target()->value(),
                    'result' => $report->result()])]];
<<<<<<< HEAD
=======
                        'value' => $report->target()->value(),
                    ]),
                'results' => $this->sendReport->map(static fn (SendReport $report): array => [
                    'target' => $report->target()->value(),
                    'result' => $report->result(),
                ]),
            ],
        ];
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
>>>>>>> a988596b (first)
    }
}
