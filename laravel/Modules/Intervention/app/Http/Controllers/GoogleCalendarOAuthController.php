<?php

declare(strict_types=1);

namespace Modules\Intervention\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Modules\Intervention\GoogleCalendar\Contracts\GoogleCalendarClient;

/**
 * Per-user Google Calendar OAuth: connect, callback, disconnect. The settings
 * page the user came from is remembered in the session so the callback returns
 * to the right panel (admin or operator).
 */
final class GoogleCalendarOAuthController
{
    public function __construct(
        private readonly GoogleCalendarClient $client,
    ) {}

    public function redirect(Request $request): RedirectResponse
    {
        $user = $this->user($request);
        $request->session()->put('gcal_return', url()->previous());

        return redirect()->away($this->client->authorizationUrl($user));
    }

    public function callback(Request $request): RedirectResponse
    {
        $user = $this->user($request);

        $state = (string) $request->query('state', '');
        abort_if($state !== '' && $state !== (string) $user->id, 403);

        $code = (string) $request->query('code', '');
        if ($code !== '') {
            $this->client->handleAuthorizationCode($user, $code);
        }

        return redirect()->to($this->returnUrl($request));
    }

    public function disconnect(Request $request): RedirectResponse
    {
        $user = $this->user($request);
        $this->client->disconnect($user);

        return redirect()->to($this->returnUrl($request));
    }

    private function user(Request $request): User
    {
        $user = $request->user();
        abort_unless($user instanceof User, 403);

        return $user;
    }

    private function returnUrl(Request $request): string
    {
        $return = $request->session()->pull('gcal_return');

        return is_string($return) && $return !== '' ? $return : url('/');
    }
}
