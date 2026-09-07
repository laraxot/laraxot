<?php

/**
 * @see https://github.com/DutchCodingCompany/filament-socialite
 */

declare(strict_types=1);

namespace Modules\User\Actions\Socialite;

// use DutchCodingCompany\FilamentSocialite\FilamentSocialite;
use Filament\Notifications\Notification;
use Illuminate\Http\RedirectResponse;
use Spatie\QueueableAction\QueueableAction;
use Webmozart\Assert\Assert;

class RedirectToLoginAction
{
    use QueueableAction;

<<<<<<< HEAD
<<<<<<< HEAD
=======
    /**
     * Execute the action.
     */
>>>>>>> f548be94 (.)
=======
    /**
     * Execute the action.
     */
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
    public function execute(string $message): RedirectResponse
    {
        // Assert::string($route_name = config('filament-socialite.login_page_route', 'filament.admin.auth.login'));
        // Route [filament.auth.login] not defined.
<<<<<<< HEAD
<<<<<<< HEAD
        $routeName = 'login';
        $translated = __('user::'.$message);
        if (is_array($translated)) {
            $translated = $translated['text'] ?? $message;
        }
        Assert::string($translated);
        Notification::make()
            ->title($translated)
=======
=======
>>>>>>> 87273113 (.)
        $route_name = 'login';
        Assert::string($message = __('user::' . $message));
        Notification::make()
            ->title($message)
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
        $routeName = 'login';
        $translated = __('user::'.$message);
        if (is_array($translated)) {
            $translated = $translated['text'] ?? $message;
        }
        Assert::string($translated);
        Notification::make()
            ->title($translated)
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            ->danger()
            ->persistent()
            ->send();

        // Redirect back to the login route with an error message attached
        return redirect()
<<<<<<< HEAD
<<<<<<< HEAD
            ->route($routeName)
            ->withErrors([
                'email' => [$translated],
=======
=======
>>>>>>> 87273113 (.)
            ->route($route_name)
            ->withErrors([
                'email' => [
                    __($message),
                ],
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            ->route($routeName)
            ->withErrors([
                'email' => [$translated],
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            ]);
    }
}
