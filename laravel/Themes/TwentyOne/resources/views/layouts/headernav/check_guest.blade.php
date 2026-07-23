<div class="flex items-center gap-4 order-4">
    <!-- Sign Up/Login -->
    @if(Auth::guest())
        <div class="flex gap-4">
            <x-filament::link :href="route('register')" class="border border-blue-1 font-semibold hover:border-blue-2 hover:bg-blue-2 hover:text-blue-3 text-blue-1 focus:ring-4 focus:outline-none focus:ring-blue rounded-lg text-sm px-10 h-12 hidden lg:flex items-center justify-center text-center">
                Sign Up
            </x-filament::link>
            <x-filament::link :href="route('login')" class="bg-blue-1 text-white hover:bg-blue-2 hover:text-blue-3 focus:ring-4 focus:outline-none focus:ring-blue font-semibold rounded-lg text-sm px-10 h-12 flex items-center justify-center text-center">
                Login
            </x-filament::link>
        </div>
    @else
        @include('pub_theme::layouts.headernav.check_guest.check')
    @endif

    <!-- LINKS DROPDOWN -->
    @include('pub_theme::layouts.headernav.links_dropdown')
</div>
