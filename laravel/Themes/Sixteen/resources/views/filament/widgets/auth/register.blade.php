{{--
  Callers: Themes/Sixteen auth-register.blade.php → @livewire(Gdpr RegisterWidget).
  Vestito FO: campi <x-auth.field>/<x-auth.check> e riepilogo <x-auth.error-summary>, gli stessi componenti e la stessa resa
  del login (stile in components/auth/assets.blade.php, DCP-290). Logica create-user/consensi nel widget modulo.
--}}
<div>
    <x-auth.error-summary />

    <form
        wire:submit="submit"
        class="w-full min-w-0"
        aria-labelledby="auth-register-heading"
        novalidate
    >
        <fieldset>
            <legend>{{ __('gdpr::register.sections.user_info') }}</legend>
            <div class="auth-fields">
                <x-auth.field name="first_name" :label="__('gdpr::register.fields.first_name.label')" autocomplete="given-name" />
                <x-auth.field name="last_name" :label="__('gdpr::register.fields.last_name.label')" autocomplete="family-name" />
                <x-auth.field name="email" type="email" :label="__('gdpr::register.fields.email.label')" autocomplete="email" :hint="__('gdpr::register.fields.email.helper_text')" />
                <x-auth.field name="password" type="password" :label="__('gdpr::register.fields.password.label')" autocomplete="new-password" :hint="__('gdpr::register.fields.password.helper_text')" revealable />
                <x-auth.field name="password_confirmation" type="password" :label="__('gdpr::register.fields.password_confirmation.label')" autocomplete="new-password" revealable />
            </div>
        </fieldset>

        <fieldset class="auth-consents">
            <legend>{{ __('gdpr::register.consents.title') }}</legend>
            <x-auth.check name="privacy_accepted" required>
                {{ __('gdpr::register.consents.privacy_policy_label') }}
                <a href="{{ \Themes\Sixteen\Infrastructure\Url\FrontofficeUrl::fromStoredUrl('/privacy') }}" target="_blank" rel="noopener">
                    {{ __('gdpr::register.consents.privacy_policy_link') }}
                    <span class="sr-only"> ({{ __('gdpr::register.consents.opens_new_tab') }})</span>
                </a>
            </x-auth.check>
            <x-auth.check name="terms_accepted" required>
                {{ __('gdpr::register.consents.terms_label') }}
                <a href="{{ \Themes\Sixteen\Infrastructure\Url\FrontofficeUrl::fromStoredUrl('/terms') }}" target="_blank" rel="noopener">
                    {{ __('gdpr::register.consents.terms_link') }}
                    <span class="sr-only"> ({{ __('gdpr::register.consents.opens_new_tab') }})</span>
                </a>
            </x-auth.check>
            <x-auth.check name="marketing_consent">{{ __('gdpr::register.consents.marketing_label') }}</x-auth.check>
        </fieldset>

        <x-filament::button
            type="submit"
            color="primary"
            wire:loading.attr="disabled"
            wire:loading.class="opacity-70"
            wire:target="submit"
            class="w-full min-h-[44px]"
            aria-describedby="auth-register-submit-status"
        >
            <span wire:loading.remove wire:target="submit">
                {{ __('user::registration.actions.register.label') }}
            </span>
            <span wire:loading wire:target="submit" class="inline-flex items-center gap-2" role="status" aria-live="polite">
                <svg class="h-5 w-5 animate-spin" width="20" height="20" viewBox="0 0 24 24" fill="none" aria-hidden="true">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                {{ __('fixcity::auth.register.loading.text') }}
            </span>
        </x-filament::button>
        <span id="auth-register-submit-status" class="sr-only visually-hidden" wire:loading wire:target="submit" role="status" aria-live="polite">
            {{ __('fixcity::auth.register.loading.text') }}
        </span>
    </form>
</div>
