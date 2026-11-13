{{--
  Callers: CMS auth.register → <x-page> → questo componente (Folio pages/auth/register).
  Guscio e resa: <x-auth.shell> + widget Livewire dominio (DCP-290): card, campi, pulsante Google.
  Widget Gdpr RegisterWidget = dominio (consensi + create user). Nessun controller.
--}}
@props(['data' => []])

@php
    $heading = trim((string) ($data['heading'] ?? '')) ?: __('fixcity::auth.register.title.text');
    $intro = trim((string) ($data['intro'] ?? '')) ?: __('fixcity::auth.register.intro.text');
    $localize = static fn (string $path): string => \Themes\Sixteen\Infrastructure\Url\FrontofficeUrl::fromStoredUrl($path);
@endphp

<x-auth.shell :heading="$heading" :intro="$intro" heading-id="auth-register-heading">
    <p class="auth-login-account">
        {{ __('user::registration.already_registered') }}
        <a href="{{ $localize('/auth/login') }}">{{ __('user::login.actions.login.label') }}</a>
    </p>

    <div class="auth-login-widget">
        @livewire(\Modules\Gdpr\Filament\Widgets\Auth\RegisterWidget::class)
    </div>

    @include('components.utilities.google-button')

    <p class="auth-login-links">
        <a href="{{ route('password.request') }}">{{ __('user::auth.login.forgot_password.text') }}</a>
    </p>
    <p class="auth-login-links">
        <a href="{{ $localize('/accessibilita') }}">{{ __('pub_theme::accessibility.page.title.text') }}</a>
    </p>
</x-auth.shell>
