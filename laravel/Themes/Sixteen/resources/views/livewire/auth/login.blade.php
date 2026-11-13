@section('title', __('pub_theme::header.guest.login.label'))

<div class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <x-filament::icon
            name="heroicon-o-user-circle"
            class="mx-auto h-12 w-12 text-primary-600"
        />
        
        <h1 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
            {{ __('pub_theme::auth_components.login.title.label') }}
        </h1>
        
        @if (Route::has('register'))
            <p class="mt-2 text-center text-sm text-gray-600">
                {{ __('pub_theme::auth_components.login.no_account.label') }}
                <x-filament::link href="{{ route('register') }}">
                    {{ __('pub_theme::header.guest.register.label') }}
                </x-filament::link>
            </p>
        @endif
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10">
            <form wire:submit="authenticate" class="login-form">
                {{ $this->form }}

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="rounded-md bg-danger-50 p-4 mt-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <x-filament::icon icon="heroicon-m-x-circle" class="h-5 w-5 text-danger-400"/>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-danger-800">
                                    {{ __('pub_theme::auth_components.login.invalid_credentials.message') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Actions -->
                <div class="mt-6 space-y-4">
                    <button
                        type="submit"
                        class="login-button"
                        wire:loading.attr="disabled"
                    >
                        <x-filament::loading-indicator
                            wire:loading
                            class="h-4 w-4 mr-2"
                        />
                        <span>{{ __('pub_theme::header.guest.login.label') }}</span>
                    </button>

                    @if (Route::has('password.request'))
                        <div class="text-center">
                            <x-filament::link
                                href="{{ route('password.request') }}"
                                class="text-sm"
                            >
                                {{ __('pub_theme::auth_components.login.forgot_password.label') }}
                            </x-filament::link>
                        </div>
                    @endif
                </div>
            </form>
        </div>
    </div>
</div>

