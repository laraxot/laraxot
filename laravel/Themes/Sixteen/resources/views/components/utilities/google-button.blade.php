{{-- Pulsante "Accedi con Google", lo stesso in login e registrazione (DCP-290, FO-5-26). --}}
{{-- SSoT URL: route oauth.redirect = {lang}/auth/login/{provider} (Modules/User/routes/socialite.php) --}}
{{-- Mai href senza lang: la pagina login e' /it/auth/login, quindi Google = /it/auth/login/google --}}
{{-- Compare solo con le tre chiavi GOOGLE_* vere: `Route::has('oauth.redirect')` e `config()->has('services.google')` sono sempre veri (DCP-271). --}}
{{-- La resa arriva con il componente (non dalla card che lo contiene) e solo una volta per pagina. --}}
@props(['size' => 'md', 'variant' => 'primary'])

@php
    $isGoogleConfigured = app(\Modules\User\Actions\Socialite\IsProviderConfiguredAction::class)->execute('google');

    $locale = trim((string) app()->getLocale(), '/');
    $supported = \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getSupportedLanguagesKeys();
    if (! in_array($locale, $supported, true)) {
        $locale = (string) config('app.locale', 'it');
    }

    $googleUrl = Route::has('oauth.redirect')
        ? route('oauth.redirect', ['provider' => 'google', 'lang' => $locale])
        : \Themes\Sixteen\Infrastructure\Url\FrontofficeUrl::fromStoredUrl('/auth/login/google');
@endphp

@if ($isGoogleConfigured && filled($googleUrl))
@once
<style data-google-button-style>
    .google-login-wrapper { width: 100%; }
    .google-login-wrapper a { display: flex !important; align-items: center; justify-content: center; gap: 0.5rem; width: 100%; box-sizing: border-box; height: 2.75rem; min-height: 2.75rem; padding: 0 1rem; border: 1px solid #5b6f82; border-radius: 0.25rem; background: #fff; color: #1a1a1a; font-weight: 600; text-decoration: none; }
    .google-login-wrapper a:hover { background: #f5f7f9; }
    .google-login-wrapper a:focus-visible { outline: 2px solid #007a52; outline-offset: 2px; }
    .google-login-wrapper svg { width: 1.25rem; height: 1.25rem; flex: 0 0 auto; }
</style>
@endonce
<div class="google-login-wrapper">
    <a href="{{ $googleUrl }}">
        <svg viewBox="0 0 24 24" aria-hidden="true" focusable="false">
            <path fill="#4285F4" d="M21.35 12.27c0-.74-.07-1.45-.2-2.13H12v4.03h5.24a4.48 4.48 0 0 1-1.94 2.94v2.45h3.15c1.85-1.7 2.9-4.2 2.9-7.29Z" />
            <path fill="#34A853" d="M12 21.75c2.65 0 4.88-.88 6.5-2.39l-3.15-2.45c-.87.58-1.98.92-3.35.92-2.57 0-4.75-1.74-5.53-4.08H3.22v2.53A9.82 9.82 0 0 0 12 21.75Z" />
            <path fill="#FBBC05" d="M6.47 13.75A5.9 5.9 0 0 1 6.16 12c0-.61.11-1.2.31-1.75V7.72H3.22A9.75 9.75 0 0 0 2.25 12c0 1.57.38 3.06.97 4.28l3.25-2.53Z" />
            <path fill="#EA4335" d="M12 6.17c1.44 0 2.73.5 3.75 1.48l2.81-2.81C16.87 3.27 14.65 2.25 12 2.25a9.82 9.82 0 0 0-8.78 5.47l3.25 2.53C7.25 7.91 9.43 6.17 12 6.17Z" />
        </svg>
        <span>{{ __('user::auth.login.google.button.text') }}</span>
    </a>
</div>
@endif
