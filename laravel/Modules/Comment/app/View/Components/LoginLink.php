<?php

declare(strict_types=1);

namespace Modules\Comment\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

/**
 * «Accedi per commentare» (CMT-022): link al login che riporta l'ospite sulla pagina che sta leggendo.
 *
 * Usato dai blocchi blog, eventi e ticket del tema al posto di tre `route('login')` nudi: con il link nudo, dopo l'accesso si finiva
 * sulla dashboard. Il ritorno e' `?redirect=<uri corrente>`, letto e validato da `Modules\User\Actions\Auth\StoreIntendedUrlAction`.
 * Va usato in un normale render di pagina: in una chiamata Livewire l'URI corrente e' quello di `/livewire/update`.
 */
class LoginLink extends Component
{
    public string $href;

    public function __construct(?string $redirect = null)
    {
        $this->href = route('login', ['redirect' => $redirect ?? request()->getRequestUri()]);
    }

    public function render(): View
    {
        /** @phpstan-var view-string */
        $view = 'comment::components.login-link';

        return view($view);
    }
}
