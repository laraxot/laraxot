<?php

declare(strict_types=1);

namespace Modules\Notify\Actions\Push;

use DateTime;
use Illuminate\Support\Facades\Cache;
use Modules\Notify\Datas\PushNotificationData;
<<<<<<< HEAD
<<<<<<< HEAD
use Spatie\QueueableAction\ActionJob;
=======
use Modules\Notify\Jobs\SendScheduledPushNotification;
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
use Modules\Notify\Jobs\SendScheduledPushNotification;
>>>>>>> a988596b (first)
use Spatie\QueueableAction\QueueableAction;

/**
 * Programma l'invio futuro di una notifica push.
 */
class SchedulePushNotificationAction
{
    use QueueableAction;

    /**
     * @param  list<string>  $tokens
     * @param  array<string, mixed>  $data
     */
    public function execute(array $tokens, PushNotificationData $notification, array $data, DateTime $scheduleTime): string
    {
        $jobId = uniqid('push_', true);

        Cache::put("scheduled_push:{$jobId}", [
            'tokens' => $tokens,
            'notification' => $notification->toArray(),
            'data' => $data,
<<<<<<< HEAD
<<<<<<< HEAD
            'schedule_time' => $scheduleTime->getTimestamp()], $scheduleTime);

        // ActionJob::dispatch(...) invece di onQueue()->execute(...): il docblock
        // `@return static` di QueueableAction::onQueue() e' impreciso (ritorna una
        // classe anonima, non $this), PHPStan crede che ->execute() richiami di
        // nuovo il nostro metodo (void) e boccia ->delay() su null. Dispatchable::
        // dispatch() di Laravel e' tipizzato correttamente e supporta ->delay().
        ActionJob::dispatch(app(SendScheduledPushNotificationAction::class), [$jobId])
=======
            'schedule_time' => $scheduleTime->getTimestamp(),
        ], $scheduleTime);

        SendScheduledPushNotification::dispatch($jobId)
>>>>>>> 3096f6ae (chore(gitattributes): sync dal prototipo canonico, no git-lfs [graft: broken parent 2c641c73 missing upstream, treated as root - local-only replace, not pushed])
=======
            'schedule_time' => $scheduleTime->getTimestamp()], $scheduleTime);

        SendScheduledPushNotification::dispatch($jobId)
>>>>>>> a988596b (first)
            ->delay($scheduleTime);

        return $jobId;
    }
}
