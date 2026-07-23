<?php

namespace Spatie\Comments\Http\Controllers;

use Spatie\Comments\Models\Concerns\Interfaces\CanComment;

class UnsubscribeFromAllNotificationsController
{
    public function askConfirmation()
    {
        return view('comments::signed.notificationSubscription.unsubscribeAllApproval');
    }

    public function unsubscribeAll(string $userClass, string $userId)
    {
        /** @var CanComment $user */
        $user = $userClass::find($userId);

        $user->unsubscribeFromAllCommentNotifications();

        return view('comments::signed.notificationSubscription.unsubscribe');
    }
}
