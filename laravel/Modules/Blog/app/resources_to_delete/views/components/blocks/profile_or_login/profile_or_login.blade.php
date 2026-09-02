<div>
    @if(Auth::check())
        <div
            {{-- x-show="loggedIn" --}}
            x-cloak
            class="bg-white rounded-2xl text-sm space-y-10 p-6"
            >
            <div class="flex items-center gap-4">
                {{-- <div
                    class="size-[75px] bg-neutral-2 flex items-center justify-center rounded-full text-2xl text-white"
                    >
                    U
                </div> --}}

                <img
                    src="{{ $_profile->getAvatarUrl() }}"
                    class="size-10 

                            {{-- lg:size-[7.5rem]  --}}
                            lg:size-20 
                        rounded-full shrink-0 object-cover"
                    alt="{{ $_profile->full_name }}"
                    />



                <p class="text-base font-semibold text-neutral-5">{{ $_profile->full_name }}</p>
            </div>
            <div class="space-y-6">
                <div class="flex justify-between items-center">
                    <p class="text-neutral-3 text-sm font-medium font-roboto">
                        Forecaster rank
                    </p>
                    <span class="text-sm text-neutral-4 font-semibold">#</span>
                </div>
                {{-- <div class="flex justify-between items-center">
                    <p class="text-neutral-3 text-sm font-medium font-roboto">
                        Lifetime profit
                    </p>
                    <p class="text-sm">
                        <span class="text-neutral-4 font-semibold">0.00</span>
                        <span class="text-neutral-3 font-medium">Ooms</span>
                    </p>
                </div> --}}
            </div>
            <div class="text-center">
                <a href="#" class="text-sm font-semibold text-blue-6"
                    >Go to my profile</a
                    >
            </div>
        </div>
    @else
        <div
            {{-- x-show="!loggedIn" --}}
            x-cloak
            class="bg-white rounded-2xl text-sm space-y-10 p-6"
            >
            <p class="text-base font-semibold">
                {{ (string) __('predict::auth.sign-up-or-login-to-participate') }}
            </p>
            <div class="flex gap-4">
                {{-- <a
                    type="button"
                    class="border border-blue-6 font-semibold hover:bg-blue-6 hover:text-white text-blue-6 focus:ring-4 focus:outline-none focus:ring-blue rounded-lg text-sm px-10 h-10 flex items-center justify-center text-center"
                    src="{{ url('it/register') }}"
                    >
                Sign Up
                </a> --}}

                <x-filament::link :href="route('register')" class="border border-blue-1 font-semibold hover:border-blue-2 hover:bg-blue-2 hover:text-blue-3 text-blue-1 focus:ring-4 focus:outline-none focus:ring-blue rounded-lg text-sm px-10 h-12 hidden lg:flex items-center justify-center text-center">
                    {{ (string) __('user::auth.sign-up') }}
                </x-filament::link>


                {{-- <a
                    type="button"
                    class="bg-blue-6 text-white hover:bg-[#061989] focus:ring-4 focus:outline-none focus:ring-blue font-semibold rounded-lg text-sm px-10 h-10 flex items-center justify-center text-center"
                    src="{{ url('it/login') }}"
                    >
                Login
                </a> --}}
                <x-filament::link :href="route('login')" class="bg-blue-1 text-white hover:bg-blue-2 hover:text-blue-3 focus:ring-4 focus:outline-none focus:ring-blue font-semibold rounded-lg text-sm px-10 h-12 flex items-center justify-center text-center">
                    {{ (string) __('user::auth.login-in') }}
                </x-filament::link>
            </div>
        </div>
    @endif
</div>