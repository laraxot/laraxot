<x-layouts.main>
    <x-slot name="title">
        {{ __('pub_theme::auth.actions.login.label') }} - {{ config('app.name', 'Laravel') }}
    </x-slot>

    <x-slot name="description">
        {{ __('pub_theme::auth.login.page_description.description') }}
    </x-slot>

    <!-- Skip to main content for accessibility -->
    <a href="#login-widget" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-indigo-600 text-white px-4 py-2 rounded-md z-50">
        {{ __('pub_theme::auth.login.skip_link.label') }}
    </a>

    <!-- Login Container -->
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <!-- Logo and Header -->
            <div class="text-center">
                <div class="mx-auto h-12 w-12 bg-indigo-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                    </svg>
                </div>

                <h1 class="text-3xl font-extrabold text-gray-900 mb-2">
                    {{ __('pub_theme::auth.login.heading.title') }}
                </h1>

                <p class="text-sm text-gray-600">
                    {{ __('pub_theme::auth.login.subheading.description') }}
                </p>
            </div>

            <!-- Login Widget Container -->
            <div id="login-widget" class="bg-white rounded-lg shadow-lg border border-gray-200 p-8">
                @livewire(\Modules\User\Filament\Widgets\Auth\LoginWidget::class)
            </div>

            <!-- Registration Link -->
            <div class="text-center">
                <p class="text-sm text-gray-600">
                    {{ __('pub_theme::auth.login.no_account.message') }}
                    <a
                        href="{{ route('register') }}"
                        class="font-medium text-blue-600 hover:text-blue-500 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 rounded"
                    >
                        {{ __('pub_theme::auth.actions.register_now.label') }}
                    </a>
                </p>
            </div>

            <!-- Footer Links -->
            <div class="text-center space-y-2">
                <p class="text-xs text-gray-500">
                    {{ __('pub_theme::auth.consent.prefix.message') }}
                    <a href="{{-- route('terms') --}}" class="text-blue-600 hover:text-blue-500">{{ __('pub_theme::auth.consent.terms.label') }}</a>
                    {{ __('pub_theme::auth.consent.and.message') }}
                    <a href="{{-- route('privacy') --}}" class="text-blue-600 hover:text-blue-500">{{ __('pub_theme::auth.consent.privacy.label') }}</a>
                </p>
            </div>
        </div>
    </div>
</x-layouts.main>
