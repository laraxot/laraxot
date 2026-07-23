@guest
<div class="hidden lg:block">
    <div class="flex space-x-4">
        <a href="{{route('register')}}" class="grid px-4 py-2 text-sm font-semibold transition rounded-lg text-nowrap ring-2 ring-inset ring-blue-500 place-items-center hover:bg-gray-50 hover:no-underline">
            <span>{{ __('user::auth.sign-up') }}</span>
        </a>
        <a href="{{route('login')}}" class="grid px-4 py-2 text-sm font-semibold text-white transition bg-blue-500 rounded-lg text-nowrap place-items-center hover:bg-blue-600 hover:no-underline">
            <span>{{ __('user::auth.login-in') }}</span>
        </a>
    </div>
</div>
@endguest