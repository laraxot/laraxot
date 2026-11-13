@props(['data' => []])
{{--
    Richiesta assistenza, passo di conferma Design Comuni (riferimento sito/assistenza-02-conferma.html): breadcrumb, hero con
    icona di successo, messaggio ed email del riepilogo, poi i partial condivisi rating e contatti. Testi, icona ed email arrivano
    dal JSON (tests.assistenza-02-conferma.json). Stili: resources/css/design-comuni/ (scope body:has(page-content tests.*)).
--}}
@php
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $icon = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
@endphp
<div class="container" id="main-container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="cmp-breadcrumbs" role="navigation">
                <nav class="breadcrumb-container" aria-label="{{ __('pub_theme::blocks_common.shared.breadcrumb.label') }}"><ol class="breadcrumb p-0" data-element="breadcrumb">
                    @foreach ($d('breadcrumb.items', []) as $crumb)
                        <li class="breadcrumb-item">
                            @if (filled($crumb['url'] ?? null) && ($crumb['url'] ?? '') !== '#')
                                <a href="{{ $crumb['url'] }}">{{ $crumb['label'] ?? '' }}</a>
                            @else
                                <span>{{ $crumb['label'] ?? '' }}</span>
                            @endif
                            <span class="separator">/</span>
                        </li>
                    @endforeach
                    <li class="breadcrumb-item active" aria-current="page">{{ $d('breadcrumb.current') }}</li>
                </ol></nav>
            </div>
        </div>
    </div>
</div>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="cmp-hero">
                <section class="it-hero-wrapper bg-white align-items-start">
                    <div class="it-hero-text-wrapper pt-0 ps-0 pb-40 pb-lg-60">
                        <div class="categoryicon-top d-flex">
                            <svg class="icon icon-success mr-10 icon-md" aria-hidden="true"><use href="{{ $icon($d('hero.icon')) }}"></use></svg>
                            <h1 class="text-black" data-element="page-name">{{ $d('title') }}</h1>
                        </div>
                        <div class="hero-text"><p>{{ $d('hero.message') }}<br><br> {{ $d('hero.email_label') }}<br>
                            <strong>{{ $d('hero.email') }}</strong></p></div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@include('pub_theme::components.blocks.partials.dc-rating', ['data' => $data])
@include('pub_theme::components.blocks.partials.dc-contacts', ['data' => $data])
