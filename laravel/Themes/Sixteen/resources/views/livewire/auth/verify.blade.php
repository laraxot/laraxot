@section('title', 'Verify your email address')

<div>
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <a href="{{ route('home') }}">
            <x-logo class="w-auto h-16 mx-auto text-indigo-600" />
        </a>

        <h2 class="mt-6 text-3xl font-extrabold text-center text-gray-900 leading-9">
            {{ __('pub_theme::auth_components.verify.title.label') }}
        </h2>

        <p class="mt-2 text-sm text-center text-gray-600 leading-5 max-w">
            Or
            <a href="{{ url('/'.app()->getLocale().'/auth/logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="font-medium text-indigo-600 hover:text-indigo-500 focus:outline-none focus:underline transition ease-in-out duration-150">
                {{ __('pub_theme::auth_components.verify.sign_out.label') }}
            </a>

            <form id="logout-form" action="{{ url('/'.app()->getLocale().'/auth/logout') }}" method="POST" style="display: none;">
                @csrf
            </form>
        </p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="px-4 py-8 bg-white shadow sm:rounded-lg sm:px-10">
            @if (session('resent'))
                <div class="flex items-center px-4 py-3 mb-6 text-sm text-white bg-green-500 rounded shadow" role="alert">
                    <svg class="w-4 h-4 mr-3 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>

                    <p>{{ __('pub_theme::auth_components.verify.link_sent.message') }}</p>
                </div>
            @endif

            <div class="text-sm text-gray-700">
                <p>{{ __('pub_theme::auth_components.verify.check_email.message') }}</p>

                <p class="mt-3">
<<<<<<< HEAD
                    {{ __('pub_theme::auth_components.verify.not_received.message') }} <a wire:click="resend" class="text-indigo-700 cursor-pointer hover:text-indigo-600 focus:outline-none focus:underline transition ease-in-out duration-150" wire:loading.attr="disabled">
                        <span class="inline-flex items-center gap-2">
                            <span wire:loading>
                                <svg class="w-5 h-5 text-indigo-600 animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                            </span>
                            <span wire:loading.remove>{{ __('pub_theme::auth_components.verify.resend.label') }}</span>
                        </span>
                    </a>.
=======
                    {{ __('pub_theme::auth_components.verify.not_received.message') }} <a wire:click="resend" class="text-indigo-700 cursor-pointer hover:text-indigo-600 focus:outline-none focus:underline transition ease-in-out duration-150">{{ __('pub_theme::auth_components.verify.resend.label') }}</a>.
>>>>>>> f08c6884 (.)
                </p>
            </div>
        </div>
    </div>
</div>
