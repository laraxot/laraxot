{{--
  ResetPasswordWidget — vestito Sixteen come il login (DCP-290); logica in Modules\User\Filament\Widgets\Auth\ResetPasswordWidget.
  Il nome del file e' quello che risolve GetViewByClassAction (`pub_theme::filament.widgets.auth.reset-password`).
--}}
<div class="space-y-6">
    <x-auth.error-summary />

    <form wire:submit="resetPassword" class="space-y-6" aria-labelledby="auth-password-token-heading" novalidate>
        <div class="fo-filament-form-shell">
            {{ $this->form }}
        </div>

        <x-filament::button
            type="submit"
            color="primary"
            class="w-full"
            wire:loading.attr="disabled"
            wire:target="resetPassword"
        >
            <span wire:loading.remove wire:target="resetPassword">{{ __('user::password_recovery.reset_submit') }}</span>
            <span wire:loading wire:target="resetPassword" role="status" aria-live="polite">{{ __('user::password_recovery.saving') }}</span>
        </x-filament::button>
    </form>
</div>
