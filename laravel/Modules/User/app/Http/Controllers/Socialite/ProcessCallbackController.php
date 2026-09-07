<?php

declare(strict_types=1);

/**
 * @see DutchCodingCompany\FilamentSocialite.
 */

namespace Modules\User\Http\Controllers\Socialite;

<<<<<<< HEAD
<<<<<<< HEAD
=======
use Modules\Xot\Contracts\UserContract;
>>>>>>> f548be94 (.)
=======
use Modules\Xot\Contracts\UserContract;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
<<<<<<< HEAD
<<<<<<< HEAD
=======
use Modules\User\Actions\Socialite\IsProviderConfiguredAction;
>>>>>>> f548be94 (.)
=======
use Modules\User\Actions\Socialite\IsProviderConfiguredAction;
=======
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\User\Actions\Socialite\IsRegistrationEnabledAction;
use Modules\User\Actions\Socialite\IsUserAllowedAction;
use Modules\User\Actions\Socialite\LoginUserAction;
use Modules\User\Actions\Socialite\RedirectToLoginAction;
use Modules\User\Actions\Socialite\RegisterOauthUserAction;
use Modules\User\Actions\Socialite\RegisterSocialiteUserAction;
use Modules\User\Actions\Socialite\RetrieveOauthUserAction;
use Modules\User\Actions\Socialite\RetrieveSocialiteUserAction;
use Modules\User\Actions\Socialite\SetDefaultRolesBySocialiteUserAction;
use Modules\User\Actions\Socialite\ValidateProviderAction;
use Modules\User\Events\RegistrationNotEnabled;
use Modules\User\Events\UserNotAllowed;
<<<<<<< HEAD
<<<<<<< HEAD
use Modules\Xot\Contracts\UserContract;
=======
use Modules\User\Exceptions\ProviderNotConfigured;
>>>>>>> f548be94 (.)
=======
use Modules\User\Exceptions\ProviderNotConfigured;
=======
use Modules\Xot\Contracts\UserContract;
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
use Modules\Xot\Datas\XotData;

class ProcessCallbackController extends Controller
{
    /**
     * Undocumented function.
     */
    public function __invoke(Request $_request, string $provider): RedirectResponse
    {
        // See if provider exists
        // if (! app(IsProviderConfiguredAction::class)->execute($provider)) {
        //    throw ProviderNotConfigured::make($provider);
        // }
        app(ValidateProviderAction::class)->execute($provider);

        // Try to retrieve existing user
        $oauthUser = app(RetrieveOauthUserAction::class)->execute($provider);
<<<<<<< HEAD
<<<<<<< HEAD
        if (null === $oauthUser) {
=======
        if ($oauthUser === null) {
>>>>>>> f548be94 (.)
=======
        if ($oauthUser === null) {
=======
        if (null === $oauthUser) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return app(RedirectToLoginAction::class)->execute('auth.login-failed');
        }

        // Verify if user is allowed
<<<<<<< HEAD
<<<<<<< HEAD
        if (! app(IsUserAllowedAction::class)->execute($oauthUser)) {
=======
        if (!app(IsUserAllowedAction::class)->execute($oauthUser)) {
>>>>>>> f548be94 (.)
=======
        if (!app(IsUserAllowedAction::class)->execute($oauthUser)) {
=======
        if (! app(IsUserAllowedAction::class)->execute($oauthUser)) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            UserNotAllowed::dispatch($oauthUser);

            return app(RedirectToLoginAction::class)->execute('auth.user-not-allowed');
        }

        // Try to find a socialite user
        $socialiteUser = app(RetrieveSocialiteUserAction::class)->execute($provider, $oauthUser);
        if ($socialiteUser) {
            $socialiteUserObj = $socialiteUser->user;
<<<<<<< HEAD
<<<<<<< HEAD
            if (null === $socialiteUserObj || ! $socialiteUserObj->canAccessSocialite()) {
                return app(RedirectToLoginAction::class)->execute('auth.user-not-allowed');
            }
            // Associate default roles to the existing "real" user, if needed
            app(SetDefaultRolesBySocialiteUserAction::class)->execute($provider, $socialiteUserObj, $oauthUser);
=======
=======
>>>>>>> 87273113 (.)
            if ($socialiteUserObj === null || !$socialiteUserObj->canAccessSocialite()) {
                return app(RedirectToLoginAction::class)->execute('auth.user-not-allowed');
            }
            // Associate default roles to the existing "real" user, if needed
            app(SetDefaultRolesBySocialiteUserAction::class, [
                'provider' => $provider,
            ])->execute($socialiteUserObj, $oauthUser);
<<<<<<< HEAD
>>>>>>> f548be94 (.)
=======
=======
            if (null === $socialiteUserObj || ! $socialiteUserObj->canAccessSocialite()) {
                return app(RedirectToLoginAction::class)->execute('auth.user-not-allowed');
            }
            // Associate default roles to the existing "real" user, if needed
            app(SetDefaultRolesBySocialiteUserAction::class)->execute($provider, $socialiteUserObj, $oauthUser);
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)

            return app(LoginUserAction::class)->execute($socialiteUser);
        }

        // See if registration is allowed
<<<<<<< HEAD
<<<<<<< HEAD
        if (! app(IsRegistrationEnabledAction::class)->execute()) {
=======
        if (!app(IsRegistrationEnabledAction::class)->execute()) {
>>>>>>> f548be94 (.)
=======
        if (!app(IsRegistrationEnabledAction::class)->execute()) {
=======
        if (! app(IsRegistrationEnabledAction::class)->execute()) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            RegistrationNotEnabled::dispatch($provider, $oauthUser);

            return app(RedirectToLoginAction::class)->execute('auth.registration-not-enabled');
        }

        $user_class = XotData::make()->getUserClass();
        // See if a user already exists, but not for this socialite provider
        // $user = app()->call($this->socialite->getUserResolver(), ['provider' => $provider, 'oauthUser' => $oauthUser, 'socialite' => $this->socialite]);
        /** @var UserContract|null */
        $user = $user_class::query()->firstWhere(['email' => $oauthUser->getEmail()]);

        // Handle registration
<<<<<<< HEAD
<<<<<<< HEAD
        if (null !== $user) {
=======
        if ($user !== null) {
>>>>>>> f548be94 (.)
=======
        if ($user !== null) {
=======
        if (null !== $user) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            $socialiteUser = app(RegisterSocialiteUserAction::class)->execute($provider, $oauthUser, $user);
        } else {
            $socialiteUser = app(RegisterOauthUserAction::class)->execute($provider, $oauthUser);
        }

        $socialiteUserObj = $socialiteUser->user;
<<<<<<< HEAD
<<<<<<< HEAD
        if (null === $socialiteUserObj || ! $socialiteUserObj->canAccessSocialite()) {
=======
        if ($socialiteUserObj === null || !$socialiteUserObj->canAccessSocialite()) {
>>>>>>> f548be94 (.)
=======
        if ($socialiteUserObj === null || !$socialiteUserObj->canAccessSocialite()) {
=======
        if (null === $socialiteUserObj || ! $socialiteUserObj->canAccessSocialite()) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return app(RedirectToLoginAction::class)->execute('auth.user-not-allowed');
        }

        // Verifichiamo prima se l'utente può accedere al socialite
        /** @var UserContract|null $authUser */
        $authUser = Auth::user();
<<<<<<< HEAD
<<<<<<< HEAD
        if (null !== $authUser && method_exists($authUser, 'canAccessSocialite') && ! $authUser->canAccessSocialite()) {
=======
        if ($authUser !== null && method_exists($authUser, 'canAccessSocialite') && !$authUser->canAccessSocialite()) {
>>>>>>> f548be94 (.)
=======
        if ($authUser !== null && method_exists($authUser, 'canAccessSocialite') && !$authUser->canAccessSocialite()) {
=======
        if (null !== $authUser && method_exists($authUser, 'canAccessSocialite') && ! $authUser->canAccessSocialite()) {
>>>>>>> 2024e2e7 (.)
>>>>>>> 87273113 (.)
            return redirect()->route(
                optional(Auth::check()) ? 'filament.user.pages.dashboard' : 'filament.user.auth.login',
            );
        }

        return app(LoginUserAction::class)->execute($socialiteUser);
    }
}
