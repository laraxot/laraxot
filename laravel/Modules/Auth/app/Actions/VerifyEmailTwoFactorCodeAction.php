<?php

declare(strict_types=1);

namespace Modules\Auth\Actions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Modules\User\Models\User;
use Spatie\QueueableAction\QueueableAction;

final class VerifyEmailTwoFactorCodeAction
{
    use QueueableAction;

    public function execute(User $user, Request $request, string $code): void
    {
        $sessionUserId = (int) $request->session()->get('auth.email_2fa.user_id', 0);
        $hash = $request->session()->get('auth.email_2fa.code_hash');
        $expiresAt = (int) $request->session()->get('auth.email_2fa.expires_at', 0);

        if ($sessionUserId !== (int) $user->id || ! is_string($hash) || $expiresAt < now()->timestamp) {
            throw ValidationException::withMessages([
                'code' => 'Codice scaduto. Richiedi un nuovo codice.',
            ]);
        }

        if (! Hash::check($code, $hash)) {
            throw ValidationException::withMessages([
                'code' => 'Codice non valido.',
            ]);
        }

        $request->session()->forget([
            'auth.email_2fa.user_id',
            'auth.email_2fa.code_hash',
            'auth.email_2fa.expires_at',
        ]);
        $request->session()->put('auth.email_2fa.verified_user_id', $user->id);
        $request->session()->put('auth.email_2fa.verified_at', now()->timestamp);

        if ($user->email_verified_at === null) {
            $user->forceFill(['email_verified_at' => now()])->save();
        }
    }
}
