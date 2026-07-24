<?php

declare(strict_types=1);

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Modules\Auth\Actions\StartEmailTwoFactorChallengeAction;
use Modules\Auth\Actions\VerifyEmailTwoFactorCodeAction;
use Modules\User\Models\User;

final class EmailTwoFactorController extends Controller
{
    public function show(): View
    {
        return view($this->emailTwoFactorViewName());
    }

    /**
     * @return view-string
     */
    private function emailTwoFactorViewName(): string
    {
        /** @phpstan-var view-string $view */
        $view = 'auth::email-two-factor';

        return $view;
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
