<?php

declare(strict_types=1);

namespace Modules\Auth\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;
use Modules\Auth\Actions\StartEmailTwoFactorChallengeAction;
use Modules\User\Models\User;
use Modules\User\Models\Role;

final class RegisterController extends Controller
{
    public function show(Request $request): RedirectResponse|View
    {
        abort_if($this->registrationIsBlockedForHost($request), 404);

        if (Auth::check()) {
            return redirect()->to('/operator');
        }

        return view($this->registerViewName(), [
            'isDemoRegistration' => $this->isDemoRegistration(),
        ]);
    }

    /**
     * @return view-string
     */
    private function registerViewName(): string
    {
        /** @phpstan-var view-string $view */
        $view = 'auth::register';

        return $view;
    }

    public function store(Request $request): RedirectResponse
    {
        abort_if($this->registrationIsBlockedForHost($request), 404);

        /** @var array{name: string, email: string, password: string} $data */
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(10)->letters()->numbers()],
            'accept_legal' => ['accepted'],
        ]);

        /** @var User $user */
        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'approved_at' => $this->isDemoRegistration() ? now() : null,
        ]);

        if (! $this->isDemoRegistration()) {
            return redirect()->route('account.registration.pending');
        }

        $adminRole = Role::findOrCreate('admin');
        $operatorRole = Role::findOrCreate('operator');
        $user->syncRoles([$adminRole, $operatorRole]);

        Auth::login($user);
        $request->session()->regenerate();

        $request->session()->put('auth.email_2fa.intended_url', url('/operator'));
        app(StartEmailTwoFactorChallengeAction::class)->execute($user, $request, force: true);

        return redirect()->route('email-two-factor.challenge');
    }

    private function registrationIsBlockedForHost(Request $request): bool
    {
        return in_array($request->getHost(), $this->blockedHosts(), true);
    }

    /**
     * @return array<int, string>
     */
    private function blockedHosts(): array
    {
        /** @var array<int, string> $hosts */
        $hosts = config('auth.registration.blocked_hosts', []);

        return $hosts;
    }

    private function isDemoRegistration(): bool
    {
        return (bool) config('app.demo_mode');
    }
}
