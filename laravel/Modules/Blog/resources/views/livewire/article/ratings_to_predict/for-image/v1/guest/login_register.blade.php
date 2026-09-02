<div class="flex flex-col items-center p-4 space-y-4">
    <p class="text-sm">{{ (string) __('predict::auth.sign-up-or-login-to-participate') }}</p>
    <div class="flex space-x-4">
        {{-- <button role="button" class="grid border rounded-full size-10 hover:bg-gray-50 place-items-center">
            <svg class="facebook-login__icon" xmlns="http://www.w3.org/2000/svg" width="20px" height="19px" viewBox="0 0 8 14" > <g fill="none" fill-rule="evenodd" stroke="none" stroke-width="1" > <g fill="#1877f2" fill-rule="nonzero" transform="translate(-637 -911)" > <g transform="translate(484 905)"> <g transform="translate(144)"> <path d="M16.5 6v2.8h-1.4c-.483 0-.7.567-.7 1.05v1.75h2.1v2.8h-2.1V20h-2.8v-5.6H9.5v-2.8h2.1V8.8A2.8 2.8 0 0114.4 6h2.1z" ></path> </g> </g> </g> </g> </svg>
        </button> --}}
         
        <a href="{{ route('socialite.', ['provider' => 'google']) }}" class="grid border rounded-full size-10 hover:bg-gray-50 place-items-center" role="button">
            {{-- <svg viewBox="0 0 533.5 544.3" xmlns="http://www.w3.org/2000/svg" class="google-button__icon" width="20px" height="20px" > <path d="M533.5 278.4c0-18.5-1.5-37.1-4.7-55.3H272.1v104.8h147c-6.1 33.8-25.7 63.7-54.4 82.7v68h87.7c51.5-47.4 81.1-117.4 81.1-200.2z" fill="#4285f4" ></path> <path d="M272.1 544.3c73.4 0 135.3-24.1 180.4-65.7l-87.7-68c-24.4 16.6-55.9 26-92.6 26-71 0-131.2-47.9-152.8-112.3H28.9v70.1c46.2 91.9 140.3 149.9 243.2 149.9z" fill="#34a853" ></path> <path d="M119.3 324.3c-11.4-33.8-11.4-70.4 0-104.2V150H28.9c-38.6 76.9-38.6 167.5 0 244.4l90.4-70.1z" fill="#fbbc04" ></path> <path d="M272.1 107.7c38.8-.6 76.3 14 104.4 40.8l77.7-77.7C405 24.6 339.7-.8 272.1 0 169.2 0 75.1 58 28.9 150l90.4 70.1c21.5-64.5 81.8-112.4 152.8-112.4z" fill="#ea4335" ></path> </svg> --}}
            @svg('ui-google')
        </a>
       
        {{-- <button role="button" class="grid border rounded-full size-10 hover:bg-gray-50 place-items-center">
            <x-heroicon-o-envelope class="text-gray-800 size-5"/>
        </button> --}}
    </div>
    <div class="flex items-center justify-center w-full space-x-3">
        <div class="w-full h-px bg-gray-200"></div>
        <div class="text-xs !text-gray-400">{{ (string) __('predict::bet.or') }}</div>
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