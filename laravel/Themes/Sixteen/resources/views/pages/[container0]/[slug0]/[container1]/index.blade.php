<?php

declare(strict_types=1);

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use function Laravel\Folio\middleware;
use function Laravel\Folio\name;
use function Laravel\Folio\render;
use Livewire\Attributes\Locked;
use Livewire\Volt\Component;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Modules\Cms\Http\Middleware\PageSlugMiddleware;

name('container1.index');
middleware(PageSlugMiddleware::class);

/**
 * DCP-90: questa pagina generica (montata prima dei moduli) oscura Modules/Fixcity/.../tickets/track/[code].blade.php,
 * che porta `/tickets/track/{code}` (link della ricevuta) a `/tickets/track?code={code}`. Stesso redirect, qui.
 */
render(function (View $view, string $container0, string $slug0, string $container1): View|RedirectResponse {
    if ($container0 !== 'tickets' || $slug0 !== 'track') {
        return $view;
    }

    $path = '/tickets/track?code='.rawurlencode($container1);
    $localized = LaravelLocalization::getLocalizedURL(LaravelLocalization::getCurrentLocale(), $path);

    return redirect()->to($localized !== false ? $localized : $path);
});

new class extends Component {
    #[Locked]
    public string $container0 = '';
    #[Locked]
    public string $slug0 = '';
    #[Locked]
    public string $container1 = '';

    #[Locked]
    public string $pageSlug = '';

    /** @var array<string, mixed> */
    #[Locked]
    public array $data = [];

    public function mount(string $container0, string $slug0 = '', string $container1 = ''): void
    {
        $this->container0 = $container0;
        $this->slug0 = $slug0;
        $this->container1 = $container1;
        $this->pageSlug = $container0.'.view';
        $this->data = [
            'container0' => $container0,
            'slug0' => $slug0,
            'container1' => $container1,
        ];
    }
};
?>

@php
    $specialPage = $container0 === 'auth' && $slug0 === 'password' && $container1 === 'reset'
        ? 'auth-password-reset'
        : ($container0 === 'auth' && $slug0 === 'password' && $container1 === 'confirm'
            ? 'auth-password-confirm'
            : null);
    $pageTitle = $specialPage === 'auth-password-reset' || $specialPage === 'auth-password-confirm'
        ? __('Reimposta la password')
        : config('app.name');
@endphp
<x-layouts.app :title="$pageTitle">
    @if ($container0 === 'auth' && $slug0 === 'password' && $container1 === 'reset')
        @include('pub_theme::components.pages.auth-password-reset')
    @else
        @volt('container1.index')
            <div class="page-content content" data-slug="{{ $pageSlug }}" data-side="content">
                <x-page side="content" :slug="$pageSlug" :data="$data" />
            </div>
        @endvolt
    @endif
</x-layouts.app>
