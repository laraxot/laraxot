<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use function Laravel\Folio\render;

name('comment::notifications.unsubscribeAll');
middleware(['signed']);

render(function () {
    if (request()->isMethod('post')) {
        $subscriberType = request()->string('subscriber_type')->toString();
        $subscriberId = request()->string('subscriber')->toString();

        if ($subscriberType !== '' && $subscriberId !== '') {
            /** @var class-string<Model>|null $modelClass */
            $modelClass = $subscriberType;
            if (class_exists($modelClass)) {
                $subscriber = $modelClass::query()->find($subscriberId);
                if ($subscriber !== null && method_exists($subscriber, 'unsubscribeFromAllCommentNotifications')) {
                    $subscriber->unsubscribeFromAllCommentNotifications();
                }
            }
        }

        return view('comment::signed.notification-subscription.unsubscribe-all');
    }

    return view('comment::signed.notification-subscription.unsubscribe-all-approval');
});
