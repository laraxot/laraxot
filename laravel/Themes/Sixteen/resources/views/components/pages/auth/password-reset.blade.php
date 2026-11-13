{{--
  Callers: Modules/User pages/auth/password/reset.blade.php (route password.request).
  Guscio e resa: <x-auth.shell> come login (DCP-290). Testi da `user::password_recovery.*` in it, en, de, es.
--}}
<x-auth.shell
    :heading="__('user::password_recovery.title')"
    :intro="__('user::password_recovery.intro')"
    heading-id="auth-password-reset-heading"
>
    <div class="auth-login-widget">
        @livewire(\Modules\User\Filament\Widgets\Auth\ForgotPasswordWidget::class)
    </div>

    <p class="auth-login-links">
        <a href="{{ \Themes\Sixteen\Infrastructure\Url\FrontofficeUrl::fromStoredUrl('/auth/login') }}">{{ __('user::password_recovery.back_to_login') }}</a>
    </p>
</x-auth.shell>
