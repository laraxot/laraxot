{{-- Bootstrap Italia semantic contract, implemented with Tailwind + Alpine + DaisyUI. --}}
<section
    x-data="{
        visible: true,
        cookieName: @js(config('cookie-consent.cookie_key', '__cookie_consent')),
        acceptedValue: @js(config('cookie-consent.cookie_value_both', 'true')),
        essentialsValue: @js(config('cookie-consent.cookie_value_none', 'false')),
        lifetimeDays: @js((int) config('cookie-consent.cookie_expiration_days', 365)),
        init() {
            this.visible = !document.cookie.split(';').some(cookie => cookie.trim().startsWith(this.cookieName + '='));
        },
        accept(value) {
            const expires = new Date(Date.now() + this.lifetimeDays * 864e5).toUTCString();
            document.cookie = this.cookieName + '=' + encodeURIComponent(value) + '; expires=' + expires + '; path=/; SameSite=Lax';
            this.visible = false;
            window.dispatchEvent(new CustomEvent('gdpr:cookie-consent-changed', {
                detail: { type: 'cookies', value: value }
            }));
        }
    }"
    x-init="init()"
    x-cloak
    x-show="visible"
    x-transition.opacity
    class="cookiebar fixed inset-x-0 bottom-0 z-50 border-t border-base-300 bg-base-100 text-base-content shadow-2xl"
    role="region"
    aria-label="{{ __('cookie-consent::texts.alert_title') }}"
    aria-live="polite"
>
    <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
        <p class="m-0 max-w-3xl text-sm leading-6">
            {{ __('cookie-consent::texts.alert_text') }}
        </p>
        <div class="flex shrink-0 flex-wrap items-center gap-2">
            <a href="{{ \Themes\Sixteen\Infrastructure\Url\FrontofficeUrl::fromStoredUrl('/cookie-policy') }}" class="btn btn-ghost btn-sm min-h-11">
                {{ __('cookie-consent::texts.alert_settings') }}
            </a>
            <button type="button" class="btn btn-ghost btn-sm min-h-11" x-on:click="accept(essentialsValue)">
                {{ __('cookie-consent::texts.alert_essentials_only') }}
            </button>
            <button type="button" class="btn btn-primary btn-sm min-h-11" x-on:click="accept(acceptedValue)">
                {{ __('cookie-consent::texts.alert_accept') }}
            </button>
        </div>
    </div>
</section>
