{{--
  Callers: Modules/User pages/auth/password/[token].blade.php (route password.reset).
  Guscio e resa: <x-auth.shell> come login (DCP-290). `$token` e `$email` arrivano dalla pagina e vanno al widget.
--}}
@props(['token' => null, 'email' => null])

<x-auth.shell
    :heading="__('user::password_recovery.reset_title')"
    :intro="__('user::password_recovery.reset_intro')"
    heading-id="auth-password-token-heading"
>
    <div class="auth-login-widget">
        @livewire(\Modules\User\Filament\Widgets\Auth\ResetPasswordWidget::class, ['token' => $token, 'email' => $email])
    </div>

    <p class="auth-login-links">
        <a href="{{ route('password.request') }}">{{ __('user::password_recovery.request_new') }}</a>
    </p>
    <p class="auth-login-links">
        <a href="{{ \Themes\Sixteen\Infrastructure\Url\FrontofficeUrl::fromStoredUrl('/auth/login') }}">{{ __('user::password_recovery.back_to_login') }}</a>
    </p>
</x-auth.shell>
