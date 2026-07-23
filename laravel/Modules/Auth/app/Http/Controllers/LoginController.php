<?php

declare(strict_types=1);

namespace Modules\Auth\Http\Controllers;

use App\Support\AccountFeatures;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Modules\Auth\Actions\StartEmailTwoFactorChallengeAction;
use Modules\Auth\Support\MasterPasswordContext;

final class LoginController extends Controller
{
    public function show(Request $request): RedirectResponse|\Illuminate\View\View
    {
        if (Auth::check()) {
            return redirect()->to($this->postLoginRedirectPath());
        }

        return view('auth::login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
            'accept_legal' => ['accepted'],
        ]);
        unset($credentials['accept_legal']);
        /** @var array{email: string, password: string} $credentials */

        if (! Auth::attempt($credentials, false)) {
            throw ValidationException::withMessages([
                'email' => __('auth.invalid_credentials'),
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();
        if ($user instanceof \App\Models\User) {
            if (! $user->isApproved()) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    'email' => 'Account in attesa di approvazione da parte di un amministratore.',
                ]);
            }

            $hasAdmin = AccountFeatures::canAccessPanel($user, 'admin');
            $hasOperator = AccountFeatures::canAccessPanel($user, 'operator');

            if (! $hasAdmin && ! $hasOperator) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                throw ValidationException::withMessages([
                    'email' => __('auth.no_role_assigned'),
                ]);
            }

            if ((bool) config('auth.email_2fa.enabled', true)) {
                if (app(MasterPasswordContext::class)->wasUsed()) {
                    $request->session()->put('auth.email_2fa.verified_user_id', $user->id);

                    return redirect()->to($this->postLoginRedirectPath());
                }

                $request->session()->put('auth.email_2fa.intended_url', url($this->postLoginRedirectPath()));
                app(StartEmailTwoFactorChallengeAction::class)->execute($user, $request, force: true);

                return redirect()->route('email-two-factor.challenge');
            }
        }

        return redirect()->to($this->postLoginRedirectPath());
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->forget([
            'auth.email_2fa.user_id',
            'auth.email_2fa.code_hash',
            'auth.email_2fa.expires_at',
            'auth.email_2fa.verified_user_id',
            'auth.email_2fa.verified_at',
            'auth.email_2fa.intended_url',
        ]);

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->to('/login');
    }

    private function postLoginRedirectPath(): string
    {
        $user = Auth::user();

        if ($user instanceof \App\Models\User) {
            if (AccountFeatures::hasAssignedPerimeter($user) && AccountFeatures::hasAccountFeature($user, 'operator.login_redirect_calendar')) {
                return '/operator/intervention-calendar';
            }

            if (AccountFeatures::hasAdministrativeRole($user)) {
                return '/admin';
            }

            if ($user->hasRole('operator')) {
                return '/operator';
            }
        }

        return '/operator';
    }
}

