<?php

declare(strict_types=1);

namespace Modules\Auth\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Actions\StartEmailTwoFactorChallengeAction;
use Modules\User\Models\User;
use Symfony\Component\HttpFoundation\Response;

final class EnsureEmailTwoFactorVerified
{
    /**
     * @param  Closure(Request): Response  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! (bool) config('auth.email_2fa.enabled', true)) {
            return $next($request);
        }

        /** @var User|null $user */
        $user = Auth::user();
        if (! $user instanceof User) {
            return $next($request);
        }

        $verifiedUserId = (int) $request->session()->get('auth.email_2fa.verified_user_id', 0);
        if ($verifiedUserId === (int) $user->id) {
            return $next($request);
        }

        $request->session()->put('auth.email_2fa.intended_url', $request->fullUrl());
        app(StartEmailTwoFactorChallengeAction::class)->execute($user, $request);

        return redirect()->route('email-two-factor.challenge');
    }
}
