{{--
    Hero homepage civic-first, data-driven.
    La mappa è un blocco successivo: la hero deve rispondere subito a “cosa posso fare?”.
    Il CMS fornisce il copy e gli URL; questo componente definisce solo la presentazione.
--}}
@props([
    'eyebrow' => '',
    'heading' => '',
    'intro' => '',
    'trust' => '',
    'primary_label' => '',
    'primary_url' => '#',
    'secondary_label' => '',
    'secondary_url' => '#',
    'signal_eyebrow' => '',
    'signal_heading' => '',
    'signal_body' => '',
])

@php
    $eyebrow = $eyebrow !== '' ? $eyebrow : (string) __('pub_theme::home.hero.eyebrow');
    $heading = $heading !== '' ? $heading : (string) __('pub_theme::home.hero.title');
    $intro = $intro !== '' ? $intro : (string) __('pub_theme::home.hero.subtitle');
    $primary_label = $primary_label !== '' ? $primary_label : (string) __('pub_theme::home.hero.cta_create');
    $secondary_label = $secondary_label !== '' ? $secondary_label : (string) __('pub_theme::home.map.cta_list');
    $signal_eyebrow = $signal_eyebrow !== '' ? $signal_eyebrow : (string) __('pub_theme::home.hero.signal_eyebrow');
    $signal_heading = $signal_heading !== '' ? $signal_heading : (string) __('pub_theme::home.hero.signal_heading');
    $signal_body = $signal_body !== '' ? $signal_body : (string) __('pub_theme::home.hero.signal_body');
@endphp

<section class="fc-home-hero" aria-labelledby="fc-home-hero-heading" data-section="home-hero">
    <div class="fc-home-hero__inner container">
        <div class="fc-home-hero__copy">
            <p class="fc-eyebrow">{{ $eyebrow }}</p>
            <h1 id="fc-home-hero-heading" class="font-serif">{{ $heading }}</h1>
            <p class="fc-home-hero__intro">{{ $intro }}</p>
            @if (filled($trust))
                <p class="fc-home-hero__trust">{{ $trust }}</p>
            @endif
            <div class="fc-home-hero__actions">
                <a class="fc-button fc-button--primary" href="{{ $primary_url }}">{{ $primary_label }}</a>
                @if (filled($secondary_label))
                    <a class="fc-button fc-button--quiet" href="{{ $secondary_url }}">{{ $secondary_label }}</a>
                @endif
            </div>
        </div>

        <aside class="fc-home-hero__signal" aria-label="{{ $signal_eyebrow }}">
            <span class="fc-home-hero__signal-mark" aria-hidden="true"></span>
            <p class="fc-eyebrow">{{ $signal_eyebrow }}</p>
            <p class="fc-home-hero__signal-value">{{ $signal_heading }}</p>
            <p class="fc-home-hero__signal-body">{{ $signal_body }}</p>
            <div class="fc-home-hero__signal-line" aria-hidden="true"></div>
        </aside>
    </div>
</section>
