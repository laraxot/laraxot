<?php

declare(strict_types=1);

namespace Modules\Auth\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Actions\StartEmailTwoFactorChallengeAction;
use Modules\Auth\Actions\VerifyEmailTwoFactorCodeAction;

final class EmailTwoFactorController extends Controller
{
    public function show(): \Illuminate\View\View
    {
        return view('auth::email-two-factor');
    }

    public function verify(Request $request): RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();
        abort_unless($user instanceof User, 403);

        /** @var array{code: string} $data */
        $data = $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        app(VerifyEmailTwoFactorCodeAction::class)->execute($user, $request, $data['code']);

        $intended = $request->session()->pull('auth.email_2fa.intended_url');

        return redirect()->to(is_string($intended) ? $intended : '/operator');
    }

    public function resend(Request $request): RedirectResponse
    {
        /** @var User|null $user */
        $user = Auth::user();
        abort_unless($user instanceof User, 403);

        app(StartEmailTwoFactorChallengeAction::class)->execute($user, $request, force: true);

        return back()->with('status', 'Nuovo codice inviato via email.');
    }
}
