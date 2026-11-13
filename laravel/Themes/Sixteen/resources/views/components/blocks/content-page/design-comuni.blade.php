@props(['data' => []])
{{--
    Pagina di contenuto Design Comuni a sezioni (riferimenti sito/amministrazione, argomenti, documenti-dati, domande-frequenti):
    breadcrumb, hero di testo, `sections[]` e i partial condivisi rating e contatti. Testi, link e immagini arrivano dal JSON
    (tests.<slug>.json); `sections[].type` sceglie la vista in `sections/` (nuovo tipo = nuovo file), `sections[].style` il contenitore:
    `grey` = fascia bg-grey-card, `plain` = container py-5, `bare` = container. Il markup segue quello del riferimento.
    Variabili ereditate dai partial: $d, $assets, $icon, $cx. Stili: resources/css/design-comuni/ (scope body:has(page-content tests.*)).
--}}
@php
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
    $assets = \Themes\Sixteen\Infrastructure\Assets\DesignComuniAssets::base();
    $icon = static fn (string $name): string => $assets.'/bootstrap-italia/dist/svg/sprites.svg#'.$name;
    $cx = static fn (?string ...$parts): string => implode(' ', array_filter($parts, 'filled'));
@endphp
<div class="container" id="main-container">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            @include('pub_theme::components.blocks.content-page.partials.breadcrumb', ['breadcrumb' => $d('breadcrumb', [])])
        </div>
    </div>
</div>
<div class="container">
    <div class="{{ $cx('row justify-content-center', $d('hero.shadow', false) ? 'row-shadow' : null) }}">
        <div class="col-12 col-lg-10">
            <div class="cmp-hero">
                <section class="it-hero-wrapper bg-white align-items-start">
                    <div class="it-hero-text-wrapper pt-0 ps-0 pb-4 pb-lg-60">
                        <h1 class="text-black" data-element="page-name">{{ $d('title') }}</h1>
                        <div class="hero-text"><p>{{ $d('hero.text') }}</p></div>
                    </div>
                </section>
            </div>
        </div>
    </div>
</div>
@foreach ($d('sections', []) as $section)
    @php($style = $section['style'] ?? 'plain')
    @if ($style === 'grey')<div class="bg-grey-card py-5">@endif
    <div class="{{ $cx('container', $style === 'plain' ? 'py-5' : null) }}"@if (filled($section['id'] ?? null)) id="{{ $section['id'] }}"@endif>
        @include('pub_theme::components.blocks.content-page.sections.'.($section['type'] ?? 'cards-simple'), ['section' => $section])
    </div>
    @if ($style === 'grey')</div>@endif
@endforeach
@include('pub_theme::components.blocks.partials.dc-rating', ['data' => $data])
@include('pub_theme::components.blocks.partials.dc-contacts', ['data' => $data])
