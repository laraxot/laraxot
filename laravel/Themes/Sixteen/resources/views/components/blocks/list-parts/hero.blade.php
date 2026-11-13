@props(['data' => []])
{{--
    Hero Design Comuni delle pagine lista/servizi: titolo (`title`) e sommario (`hero.text`); `hero.row_shadow` aggiunge l'ombra
    della riga come nelle liste con risorse in evidenza.
--}}
@php
    $d = static fn (string $key, mixed $default = '') => data_get($data, $key, $default);
@endphp
<div class="container">
    <div @class(['row justify-content-center', 'row-shadow' => (bool) $d('hero.row_shadow', false)])>
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
