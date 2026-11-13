{{-- LoginWidget — vestito Sixteen; logica in Modules\User\Filament\Widgets\Auth\LoginWidget --}}
<div class="space-y-6">
    {{-- Esito neutro o positivo (es. "Password cambiata") e riepilogo degli errori: password errata (data.email), campi vuoti, ritorno
         da Google rifiutato (email). Una sola regione role="status" sempre presente, un solo avviso role="alert" (DCP-290).
         Il vestito sta in components/auth/assets.blade.php: le utility Tailwind sono spente dentro data-dc. --}}
    <div role="status" aria-live="polite">
        @if (session('status'))
            <p class="auth-status">{{ session('status') }}</p>
        @endif
    </div>

    <x-auth.error-summary />

    <form
        wire:submit="login"
        class="space-y-6"
        aria-labelledby="auth-login-heading"
        novalidate
    >
        {{-- Niente riquadro annidato (le utility border/p-4 erano dipinte anche dentro data-dc): i campi hanno la colonna e l'aspetto
             della registrazione (DCP-290). L'errore sta nel riepilogo e sul campo, non sul colore del contenitore. --}}
        <div class="fo-filament-form-shell">
            {{ $this->form }}
        </div>

        <x-filament::button
            type="submit"
            color="primary"
            class="w-full"
            wire:loading.attr="disabled"
            wire:loading.attr="aria-busy"
            wire:target="login"
            aria-describedby="login-submit-status"
        >
            <span wire:loading wire:target="login" class="inline-flex items-center gap-2" role="status" aria-live="polite">
                <x-filament::icon icon="heroicon-o-arrow-path" class="h-5 w-5 animate-spin motion-reduce:animate-none" aria-hidden="true" />
                {{ __('user::auth.login.logging_in.text') }}
            </span>
            <span wire:loading.remove wire:target="login">
                {{ __('user::login.actions.login.label') }}
            </span>
        </x-filament::button>
        <span id="login-submit-status" class="sr-only visually-hidden" wire:loading wire:target="login" role="status" aria-live="polite">
            {{ __('user::auth.login.logging_in.text') }}
        </span>
    </form>
</div>
