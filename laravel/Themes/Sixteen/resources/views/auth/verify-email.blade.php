<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('pub_theme::auth_pages.verify_email.before_continuing_could_you_verify_your.message') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ __('pub_theme::auth_pages.verify_email.a_new_verification_link_has_been.message') }}
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf

                <div>
                    <x-button type="submit">
                        {{ __('pub_theme::auth_pages.verify_email.resend_verification_email.label') }}
                    </x-button>
                    <x-button type="submit">
                        {{ __('pub_theme::auth_pages.verify_email.resend_verification_email.label') }}
                    </x-button>
                </div>
            </form>

            <div>
                <a
                    href="{{ route('profile.show') }}"
                    class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                >
                    {{ __('pub_theme::auth_pages.verify_email.edit_profile.label') }}</a>

                <form method="POST" action="{{ url('/'.app()->getLocale().'/auth/logout') }}" class="inline">
                    @csrf
                    <button type="submit" class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 ms-2">
                        {{ __('pub_theme::auth_pages.verify_email.log_out.label') }}
                    </button>
                </form>
            </div>
        </div>
    </x-authentication-card>
    </x-authentication-card>
</x-guest-layout>
