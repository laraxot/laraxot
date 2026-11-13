<?php

declare(strict_types=1);

/**
 * GET conferma logout per link header JSON. Logout effettivo: POST route('logout').
 * Link FO solo via route() Folio — mai localizzare path a mano nel Blade.
 */

use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use function Laravel\Folio\render;

name('auth.logout');
middleware(['web']);

render(function (View $view): View|RedirectResponse {
    if (! Auth::check()) {
        return redirect()->to(route('home'));
    }

    return $view;
});

?>

<x-layouts.app :title="__('pub_theme::auth.logout.title')">
    <main id="main-container" class="mx-auto max-w-lg px-4 py-16 sm:px-6">
        <h1 class="text-2xl font-bold tracking-tight text-slate-950">
            {{ __('pub_theme::auth.logout.title') }}
        </h1>
        <p class="mt-3 text-slate-700">
            {{ __('pub_theme::auth.logout.confirm_message') }}
        </p>
        <form method="POST" action="{{ route('logout') }}" class="mt-8 flex flex-wrap gap-3">
            @csrf
            <button type="submit" class="inline-flex min-h-11 items-center rounded-lg bg-sky-900 px-5 py-3 font-semibold text-white hover:bg-sky-950 focus:outline-none focus-visible:ring-2 focus-visible:ring-sky-800">
                {{ __('pub_theme::auth.logout.confirm_button') }}
            </button>
            {{-- Caller: form logout in questa pagina Folio auth/logout; SSoT: FrontofficeRoutes::personalAreaImpostazioni. User: «procedi» riusabile. --}}
            <a href="{{ \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::personalAreaImpostazioni() }}" class="inline-flex min-h-11 items-center rounded-lg border border-slate-300 px-5 py-3 font-semibold text-slate-800 hover:bg-slate-50">
                {{ __('pub_theme::auth.logout.cancel_button') }}
            </a>
        </form>
    </main>
</x-layouts.app>
