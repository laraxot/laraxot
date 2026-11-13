{{--
    Chrome condiviso dell'area personale (DCP-110 + DCP-111): briciole, titolo, descrizione, CTA opzionale
    e navigazione a tab (`cmp-nav-tab`) verso le QUATTRO pagine reali.
    Religione: se una pagina dell'area mostra questi tab, TUTTE devono mostrarli con lo stesso markup e lo stesso
    placement (`col-12 p-0` sotto il titolo). Le "schede" del riferimento Design Comuni qui sono link veri
    (non pannelli): `<nav>` + `aria-current="page"`, niente `role="tab"`.
    Parametri: $active (practices|followed|notifications|settings), $title, $subtitle, $cta (label, url) opzionale.
--}}
@php
    $locale = app()->getLocale();
    $localize = static fn (string $path): string => (string) \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL($locale, $path);
    $icon = static fn (string $name): string => \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base().'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
@endphp
<div class="row justify-content-center">
    <div class="col-12 col-lg-10">
        <div class="cmp-breadcrumbs" role="navigation">
            <nav class="breadcrumb-container" aria-label="{{ __('pub_theme::components_common.a11y.breadcrumb_trail.label') }}"><ol class="breadcrumb p-0" data-element="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ $localize('/') }}">{{ __('fixcity::personal_area.breadcrumb.home') }}</a><span class="separator">/</span></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $title }}</li>
            </ol></nav>
        </div>
        <div class="cmp-heading pb-3 pb-lg-4">
            <h1 class="title-xxxlarge">{{ $title }}</h1>
            <p class="subtitle-small">{{ $subtitle }}</p>
            @isset($cta)
                <a class="btn btn-primary mt-2" href="{{ $cta['url'] }}"><svg class="icon icon-white icon-sm me-2" aria-hidden="true"><use href="{{ $icon('it-plus-circle') }}"></use></svg><span>{{ $cta['label'] }}</span></a>
            @endisset
        </div>
    </div>
    <div class="col-12 p-0">
        @include('pub_theme::components.blocks.personal-area.navigation', ['active' => $active])
    </div>
</div>
