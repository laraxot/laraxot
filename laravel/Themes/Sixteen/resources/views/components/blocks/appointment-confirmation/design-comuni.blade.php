@props(['data' => []])
{{--
    Conferma della prenotazione Design Comuni (pagina di riferimento appuntamento-06-conferma): breadcrumb, intestazione con icona e
    riepilogo, indice, sezioni per `type` (checklist, address, calendar-links), valutazione e contatti. Tutto il testo arriva dal JSON
    (tests.appuntamento-06-conferma.json). Stili: resources/css/design-comuni/. Riusa i partial del blocco fratello appointment-step.
--}}
@php
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $icon = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $step = 'pub_theme::components.blocks.appointment-step.partials';
@endphp
@include("$step.breadcrumb")
<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="cmp-hero">
                <section class="it-hero-wrapper bg-white align-items-start">
                    <div class="it-hero-text-wrapper pt-0 ps-0 pb-40">
                        <div class="categoryicon-top d-flex">
                            <svg class="icon icon-success mr-10 icon-md" aria-hidden="true"><use href="{{ $icon($d('hero.icon', 'it-check-circle')) }}"></use></svg>
                            <h1 class="text-black" data-element="page-name">{{ $d('title') }}</h1>
                        </div>
                        <div class="hero-text">
                            <p>{{ $d('hero.intro') }}<strong> {{ $d('hero.when') }}</strong><br><br>{{ $d('hero.email_intro') }}<br>
                                <strong>{{ $d('hero.email') }}</strong></p>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
    <hr class="d-none d-lg-block mb-40">
</div>
<div class="container">
    <div class="row mb-4 mb-lg-80">
        @include("$step.side-nav", ['col' => 'd-none d-sm-none d-lg-block col-lg-3'])
        <div class="col-12 col-lg-8 offset-lg-1">
            <div class="it-page-sections-container">
                @foreach ($d('sections', []) as $section)
                    <section class="it-page-section" id="{{ $section['id'] ?? '' }}">
                        @switch($section['type'] ?? '')
                            @case('checklist')
                                <div class="cmp-card mb-50">
                                    <div class="card has-bkg-primary-grey p-3">
                                        <div class="card-header border-0 p-0 mb-lg-20">
                                            <div class="d-flex"><h2 class="title-xxlarge mb-0">{{ $section['title'] ?? '' }}</h2></div>
                                        </div>
                                        <div class="card-body p-0">
                                            <div class="cmp-ul-list">
                                                <ul class="list-wrapper titillium">
                                                    @foreach ($section['items'] ?? [] as $item)
                                                        <li><span> {{ $item }} </span></li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @break
                            @case('address')
                                <div class="address-wrapper mb-50">
                                    <h2 class="title-xxlarge mb-10">{{ $section['title'] ?? '' }}</h2>
                                    <div class="cmp-card-img row mx-auto">
                                        <div class="col-11 col-lg-6 px-0">
                                            <div class="card card-teaser card-teaser-info rounded shadow-sm">
                                                <div class="card-body align-items-center card-body d-flex justify-content-between">
                                                    <div class="left">
                                                        <span class="title-small"><a href="{{ $section['url'] ?? '#' }}" class="text-decoration-none">{{ $section['name'] ?? '' }}</a></span>
                                                        <p class="subtitle-small mb-0">@foreach ($section['lines'] ?? [] as $line){{ $line }}@unless ($loop->last)<br>@endunless @endforeach</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @break
                            @case('calendar-links')
                                <div class="cmp-icon-list">
                                    <h2 class="title-xxlarge mt-40 mb-2 mb-lg-4">{{ $section['title'] ?? '' }}</h2>
                                    <div class="link-list-wrapper">
                                        <ul class="link-list mt-3">
                                            @foreach ($section['items'] ?? [] as $link)
                                                <li class="">
                                                    <a class="list-item icon-left t-primary title-small-semi-bold" href="{{ $link['url'] ?? '#' }}">
                                                        <span class="list-item-title-icon-wrapper">
                                                            <svg class="icon icon-sm align-self-start icon-primary icon-color" aria-hidden="true"><use href="{{ $icon('it-calendar') }}"></use></svg>
                                                            <span class="list-item-title title-small-semi-bold fw-normal">{{ $link['label'] ?? '' }}</span>
                                                        </span>
                                                    </a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                @break
                        @endswitch
                    </section>
                @endforeach
            </div>
        </div>
    </div>
</div>
@include('pub_theme::components.blocks.appointment-confirmation.partials.rating')
@include("$step.contacts-col")
