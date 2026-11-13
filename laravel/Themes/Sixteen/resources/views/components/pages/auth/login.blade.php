{{--
  Callers: CMS auth.login → <x-page> → questo componente (Folio pages/auth/login).
  Guscio e resa: <x-auth.shell> + widget Livewire dominio (DCP-290).
  Widget LoginWidget = dominio (credenziali). Nessun controller.
  Nota: il foglio CSS condiviso `.auth-login-*` dipinge qui dentro data-dc (14-auth-login.css).
  Nessun <style> privato: lo stile di questo componente è nel foglio condiviso.
 --}}
@props(['heading' => null, 'intro' => null, 'data' => []])

@php
    // Il titolo e il riquadro intro sono sovrascrivibili dal JSON CMS (`auth.login`),
    // ma hanno sempre un fallback localizzato coerente con il `<title>` della pagina.
    $loginHeading = is_string($heading) && trim($heading) !== ''
        ? trim($heading)
        : __('fixcity::auth.login.title.text');
    $loginIntro = is_string($intro) && trim($intro) !== ''
        ? trim($intro)
        : __('fixcity::auth.login.intro.text');
    $localize = static fn (string $path): string => \Themes\Sixteen\Infrastructure\Url\FrontofficeUrl::fromStoredUrl($path);
@endphp

<x-auth.shell :heading="$loginHeading" :intro="$loginIntro" heading-id="auth-login-heading">
    <p class="auth-login-account">
        {{ __('fixcity::auth.login.no_account.text') }}
        <a href="{{ $localize('/auth/register') }}">{{ __('fixcity::auth.login.create_account.text') }}</a>
    </p>

    <div class="auth-login-widget">
        @livewire(\Modules\User\Filament\Widgets\Auth\LoginWidget::class)
    </div>

    @include('components.utilities.google-button')

    <p class="auth-login-links">
        <a href="{{ route('password.request') }}">{{ __('fixcity::auth.login.forgot_password.text') }}</a>
    </p>
    <p class="auth-login-links">
        <a href="{{ $localize('/accessibilita') }}">{{ __('pub_theme::accessibility.page.title.text') }}</a>
    </p>
</x-auth.shell>