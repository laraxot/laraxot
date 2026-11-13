<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div x-data="{ recovery: false }">
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400" x-show="! recovery">
                {{ __('pub_theme::auth_pages.two_factor_challenge.please_confirm_access_to_your_account_2.message') }}
            </div>

            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400" x-cloak x-show="recovery">
                {{ __('pub_theme::auth_pages.two_factor_challenge.please_confirm_access_to_your_account.message') }}
            </div>

            <x-validation-errors class="mb-4" />
            <x-validation-errors class="mb-4" />

            <form method="POST" action="{{ route('two-factor.login') }}">
                @csrf

                <div class="mt-4" x-show="! recovery">
                    <x-label for="code" value="{{ __('pub_theme::auth_pages.two_factor_challenge.code.label') }}" />
                    <x-input id="code" class="block mt-1 w-full" type="text" inputmode="numeric" name="code" autofocus x-ref="code" autocomplete="one-time-code" />
                </div>

                <div class="mt-4" x-cloak x-show="recovery">
                    <x-label for="recovery_code" value="{{ __('pub_theme::auth_pages.two_factor_challenge.recovery_code.label') }}" />
                    <x-input id="recovery_code" class="block mt-1 w-full" type="text" name="recovery_code" x-ref="recovery_code" autocomplete="one-time-code" />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <button type="button" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 underline cursor-pointer"
                                    x-show="! recovery"
                                    x-on:click="
                                        recovery = true;
                                        $nextTick(() => { $refs.recovery_code.focus() })
                                    ">
                        {{ __('pub_theme::auth_pages.two_factor_challenge.use_a_recovery_code.label') }}
                    </button>

                    <button type="button" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 underline cursor-pointer"
                                    x-cloak
                                    x-show="recovery"
                                    x-on:click="
                                        recovery = false;
                                        $nextTick(() => { $refs.code.focus() })
                                    ">
                        {{ __('pub_theme::auth_pages.two_factor_challenge.use_an_authentication_code.label') }}
                    </button>

                    <x-button class="ms-4">
                        {{ __('pub_theme::auth_pages.two_factor_challenge.log_in.label') }}
                    </x-button>
                </div>
            </form>
        </div>
    </x-authentication-card>
</x-guest-layout>
