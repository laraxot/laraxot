{{--
  ForgotPasswordWidget — vestito Sixteen come il login (DCP-290); logica in Modules\User\Filament\Widgets\Auth\ForgotPasswordWidget.
  Il nome del file e' quello che risolve GetViewByClassAction (`pub_theme::filament.widgets.auth.forgot-password`): con altro nome
  il widget ricade sulla vista del modulo, senza tema (era il caso di `forgot-password-widget`, mai raggiunto).
--}}
<div class="space-y-6">
    {{-- Esito neutro: stesso testo per un indirizzo registrato e per uno sconosciuto (nessuna enumerazione). La regione c'e' sempre,
         vuota, cosi' il testo che vi compare viene annunciato. --}}
    <div role="status" aria-live="polite">
        @if ($sent)
            <p class="auth-status">{{ __('user::password_recovery.sent') }}</p>
        @endif
    </div>

    <x-auth.error-summary />

    <form wire:submit="sendResetLink" class="space-y-6" aria-labelledby="auth-password-reset-heading" novalidate>
        <div class="fo-filament-form-shell">
            {{ $this->form }}
        </div>

        <x-filament::button
            type="submit"
            color="primary"
            class="w-full"
            wire:loading.attr="disabled"
            wire:target="sendResetLink"
        >
            <span wire:loading.remove wire:target="sendResetLink">{{ __('user::password_recovery.submit') }}</span>
            <span wire:loading wire:target="sendResetLink" role="status" aria-live="polite">{{ __('user::password_recovery.sending') }}</span>
        </x-filament::button>
    </form>
</div>
