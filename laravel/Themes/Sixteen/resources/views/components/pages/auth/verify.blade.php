{{--
  Verifica email — contenuto della pagina `auth/verify` (DCP-***).
  Callers: Themes/Sixteen pages/auth/verify.blade.php e Modules/User pages/auth/verify.blade.php
  (dentro il frammento Volt `auth.verify`, nel frammento `data-dc`).
  Guscio: <x-auth.shell> come login/registrazione/password (DCP-290). Logica: `resend()` del componente Volt.
--}}
<x-auth.shell
    :heading="__('user::auth_pages.sections.verify.title')"
    :intro="__('user::auth_pages.verify.check_email.description')"
    heading-id="auth-verify-heading"
>
    @if (session('resent'))
        <div class="auth-status" role="status" aria-live="polite">
            {{ __('user::auth_pages.verify.resent.message') }}
        </div>
    @endif

    <p class="auth-login-links">
        {{ __('user::auth_pages.verify.not_received.message') }}
        <button
            type="button"
            class="auth-link-button"
            wire:click="resend"
            wire:loading.attr="disabled"
            wire:loading.attr="aria-busy"
            wire:loading.class="auth-link-button--loading"
            wire:target="resend"
        >
            <span class="auth-link-button__label">{{ __('user::auth_pages.verify.request_another.label') }}</span>
            <span class="auth-link-button__loading" role="status" aria-live="polite">
                <x-filament::loading-indicator class="auth-link-button__spinner" aria-hidden="true" />
                {{ __('user::auth_pages.verify.request_another.sending') }}
            </span>
        </button>.
    </p>

    <p class="auth-login-links">
        {{ __('user::auth_pages.common.or.label') }}
        <button type="button" class="auth-link-button" onclick="document.getElementById('logout-form').submit()">
            {{ __('user::auth_pages.verify.sign_out.label') }}
        </button>
    </p>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
</x-auth.shell>
