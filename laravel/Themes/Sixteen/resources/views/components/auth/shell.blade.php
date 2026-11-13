{{--
  Guscio delle pagine di accesso (DCP-290): breadcrumb, card, un solo h1, introduzione, contenuto nello slot.
  Callers: components/pages/auth-{login,register,password-reset,password-token}.blade.php.
  Le classi `.auth-login-*` restano quelle di 14-auth-login.css e 20-dc-components.css (non rinominare).
  Lo stile e lo script del focus arrivano da <x-auth.assets> (una volta per pagina).
--}}
@props(['heading', 'intro' => null, 'headingId' => 'auth-heading'])

@php
    $homeUrl = \Themes\Sixteen\Infrastructure\Url\FrontofficeUrl::fromStoredUrl('/');
@endphp

<x-auth.assets />

<section class="auth-login-shell" aria-labelledby="{{ $headingId }}">
    <div class="auth-login-shell__inner">
        <nav aria-label="{{ __('fixcity::global.breadcrumb') }}">
            <ol class="auth-login-shell__crumb">
                <li><a href="{{ $homeUrl }}">{{ __('pub_theme::navigation.home') }}</a></li>
                <li aria-hidden="true">/</li>
                <li aria-current="page">{{ $heading }}</li>
            </ol>
        </nav>

        <div class="auth-login-card">
            <h1 id="{{ $headingId }}">{{ $heading }}</h1>
            @if (filled($intro))
                <p class="auth-login-lede">{{ $intro }}</p>
            @endif

            {{ $slot }}
        </div>
    </div>
</section>
