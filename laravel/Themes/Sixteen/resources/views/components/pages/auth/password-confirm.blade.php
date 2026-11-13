{{--
  Conferma password — contenuto della pagina `auth/password/confirm` (DCP-***).
  Callers: Themes/Sixteen pages/auth/password/confirm.blade.php e Modules/User
  pages/auth/password/confirm.blade.php (dentro il frammento Volt `auth.password.confirm`,
  nel frammento `data-dc`).
  Guscio: <x-auth.shell> come login/registrazione/password (DCP-290). Campo: x-auth.field
  revealable; submit: bottone nativo disegnato da 14-auth-login.css.
--}}
<x-auth.shell
    :heading="__('user::auth_pages.sections.password_confirm.title')"
    :intro="__('user::auth_pages.sections.password_confirm.description')"
    heading-id="auth-password-confirm-heading"
>
    <form wire:submit="confirm" class="auth-fields">
        <x-auth.field
            name="password"
            :label="__('user::login.fields.password.label')"
            type="password"
            autocomplete="current-password"
            :revealable="true"
        />
        <button type="submit">{{ __('user::auth_pages.password_confirm.submit.label') }}</button>
    </form>

    <p class="auth-login-links">
        <a href="{{ route('password.request') }}">{{ __('user::auth.login.forgot_password.text') }}</a>
    </p>
</x-auth.shell>
