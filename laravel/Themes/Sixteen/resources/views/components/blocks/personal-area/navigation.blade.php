{{-- Shared front-office navigation for every personal-area page. --}}
@props(['active' => ''])

@php
    $routes = [
        'practices' => ['url' => \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::personalAreaPratiche(), 'icon' => 'it-files'],
        'followed' => ['url' => \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::personalAreaSeguite(), 'icon' => 'it-star-outline'],
        'notifications' => ['url' => \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::personalAreaNotifiche(), 'icon' => 'it-mail'],
        'settings' => ['url' => \Themes\Sixteen\Infrastructure\Url\FrontofficeRoutes::personalAreaImpostazioni(), 'icon' => 'it-settings'],
    ];
    $icon = static fn (string $name): string => \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base().'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
@endphp

<nav class="cmp-nav-tab mb-4 mb-lg-5 mt-lg-4" aria-label="{{ __('fixcity::personal_area.nav.aria_label') }}">
    <ul class="nav nav-tabs nav-tabs-icon-text w-100 flex-wrap">
        @foreach ($routes as $key => $route)
            <li class="nav-item">
                <a
                    @class(['nav-link justify-content-start text-tab', 'active' => $key === $active])
                    href="{{ $route['url'] }}"
                    @if ($key === $active) aria-current="page" @endif
                >
                    <svg class="icon me-1 mr-lg-10" aria-hidden="true"><use href="{{ $icon($route['icon']) }}"></use></svg>
                    {{ __('fixcity::personal_area.nav.'.$key) }}
                </a>
            </li>
        @endforeach
    </ul>
</nav>
