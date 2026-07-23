<div class="fixed inset-x-0 bottom-0 z-50 flex justify-around gap-2 p-2 px-2 transition-transform duration-500 bg-white border-t border-gray-200 md:hidden" x-data :class="$store.scrollingup && 'translate-y-full'">
    <a class="flex flex-col items-center w-full p-2 space-y-1 text-gray-500 rounded hover:text-blue-500 hover:bg-gray-50" href="{{ route('categories.index', ['lang'=>$lang]) }}">
        <x-heroicon-o-globe-europe-africa class="size-6"/>
        <span class="hidden text-xs sm:block">{{ __('pub_theme::headernav.markets') }}</span>
    </a>
    <a class="flex flex-col items-center w-full p-2 space-y-1 text-gray-500 rounded hover:text-blue-500 hover:bg-gray-50" href="{{ route('page_slug.view', ['lang'=>$lang, 'slug' => 'leaderboard' ]) }}">
        <x-heroicon-o-trophy class="size-6" />
        <span class="hidden text-xs sm:block">{{ __('pub_theme::headernav.leaderboard') }}</span>
    </a>
    <a class="flex flex-col items-center w-full p-2 space-y-1 text-gray-500 rounded hover:text-blue-500 hover:bg-gray-50" href="{{ route('search', ['lang'=>$lang]) }}">
        <x-heroicon-o-magnifying-glass class="size-6" />
        <span class="hidden text-xs sm:block">{{ __('pub_theme::headernav.search') }}</span>
    </a>
    @auth
        <a class="flex flex-col items-center w-full p-2 space-y-1 text-gray-500 rounded hover:text-blue-500 hover:bg-gray-50" href="{{ '/'.$lang.'/pages/setting' }}">
            <x-heroicon-o-user class="size-6" />
            <span class="hidden text-xs sm:block">{{ __('pub_theme::headernav.account') }}</span>
        </a>
    @endauth
</div>
