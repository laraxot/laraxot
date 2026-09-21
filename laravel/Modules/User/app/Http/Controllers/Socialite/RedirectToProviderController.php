<?php

declare(strict_types=1);

/**
 * @see DutchCodingCompany\FilamentSocialite.
 */

namespace Modules\User\Http\Controllers\Socialite;

use Illuminate\Http\RedirectResponse;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Request;
use Laravel\Socialite\Facades\Socialite;
use Modules\User\Actions\Socialite\ValidateProviderAction;

class RedirectToProviderController extends Controller
{
    /**
     * Undocumented function.
     */
    public function __invoke(Request $_request, string $provider): RedirectResponse
    {
        // if (! app(IsProviderConfiguredAction::class)->execute($provider)) {
        //    throw ProviderNotConfigured::make($provider);
        // }
        app(ValidateProviderAction::class)->execute($provider);

        $redirect = Socialite::driver($provider)->redirect();

        if (! $redirect instanceof RedirectResponse) {
            throw new \RuntimeException(\sprintf('Expected %s from Socialite provider redirect(), got %s.', RedirectResponse::class, $redirect::class));
        }

        return $redirect;
    }
}
