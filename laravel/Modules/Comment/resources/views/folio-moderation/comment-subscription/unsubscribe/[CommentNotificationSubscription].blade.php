<?php

declare(strict_types=1);

use Modules\Comment\Models\CommentNotificationSubscription;
use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use function Laravel\Folio\render;

name('comment::notifications.unsubscribe');
middleware(['signed']);

render(function (CommentNotificationSubscription $commentNotificationSubscription) {
    if (request()->isMethod('post')) {
        $commentNotificationSubscription->delete();

        return view('comment::signed.notification-subscription.unsubscribe', [
            'commentNotificationSubscription' => $commentNotificationSubscription,
        ]);
    }

    return view('comment::signed.notification-subscription.unsubscribe-approval', [
        'commentNotificationSubscription' => $commentNotificationSubscription,
    ]);
});
