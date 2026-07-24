<?php

declare(strict_types=1);

namespace Modules\Auth\Actions;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\Notifications\EmailTwoFactorCodeNotification;
use Spatie\QueueableAction\QueueableAction;

final class StartEmailTwoFactorChallengeAction
{
    use QueueableAction;

    public function execute(User $user, Request $request, bool $force = false): void
    {
        if (! $force && $request->session()->has('auth.email_2fa.code_hash')) {
            return;
        }

        $code = (string) random_int(100000, 999999);
        $minutes = (int) config('auth.email_2fa.expire_minutes', 10);

        $request->session()->put([
            'auth.email_2fa.user_id' => $user->id,
            'auth.email_2fa.code_hash' => Hash::make($code),
            'auth.email_2fa.expires_at' => now()->addMinutes($minutes)->timestamp,
        ]);

        $user->notify(new EmailTwoFactorCodeNotification($code, $minutes));
    }
}
