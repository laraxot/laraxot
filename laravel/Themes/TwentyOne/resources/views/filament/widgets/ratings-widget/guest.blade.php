<div class="flex flex-col items-center p-4 space-y-4">
    <p class="text-sm">{{ __('predict::auth.sign-up-or-login-to-participate') }}</p>
    <div class="flex space-x-4">
        {{-- <button role="button" class="grid border rounded-full size-10 hover:bg-gray-50 place-items-center">
            <svg class="facebook-login__icon" xmlns="http://www.w3.org/2000/svg" width="20px" height="19px" viewBox="0 0 8 14" > <g fill="none" fill-rule="evenodd" stroke="none" stroke-width="1" > <g fill="#1877f2" fill-rule="nonzero" transform="translate(-637 -911)" > <g transform="translate(484 905)"> <g transform="translate(144)"> <path d="M16.5 6v2.8h-1.4c-.483 0-.7.567-.7 1.05v1.75h2.1v2.8h-2.1V20h-2.8v-5.6H9.5v-2.8h2.1V8.8A2.8 2.8 0 0114.4 6h2.1z" ></path> </g> </g> </g> </g> </svg>
        </button> --}}

          {{--  --}}
        <a href="{{ route('socialite.oauth.redirect', ['provider' => 'google']) }}" class="grid border rounded-full size-10 hover:bg-gray-50 place-items-center" role="button">
            @svg('ui-google')
        </a>

        {{-- <button role="button" class="grid border rounded-full size-10 hover:bg-gray-50 place-items-center">
            <x-heroicon-o-envelope class="text-gray-800 size-5"/>
        </button> --}}
    </div>
    <div class="flex items-center justify-center w-full space-x-3">
        <div class="w-full h-px bg-gray-200"></div>
        <div class="text-xs !text-gray-400">{{ __('predict::bet.or') }}</div>
        <div class="w-full h-px bg-gray-200"></div>
    </div>
    <div>
        <a href="{{route('login')}}"
            type="button"
            class="grid px-4 py-2 text-sm font-semibold !text-white transition bg-blue-500 rounded-lg text-nowrap place-items-center hover:bg-blue-600 hover:no-underline"
            >
        Login
        </a>
    </div>
</div>
