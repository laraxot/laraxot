<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('pub_theme::auth_pages.confirm_password.this_is_a_secure_area_of.message') }}
        </div>

        <x-validation-errors class="mb-4" />
        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('password.confirm') }}">
            @csrf

            <div>
                <x-label for="password" value="{{ __('pub_theme::auth_pages.shared.password.label') }}" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" autofocus />
            </div>

            <div class="flex justify-end mt-4">
                <x-button class="ms-4">
                    {{ __('pub_theme::auth_pages.shared.confirm.label') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
