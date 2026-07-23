<x-layouts.app>
    <div>
        <!-- container -->
        <div class="max-w-[calc(100%-30px)] sm:max-w-[calc(100%-80px)] lg:max-w-[996px] mx-auto pb-12">
            <div class="flex flex-col items-center justify-center py-8 space-y-4">
                <div>
                    <img class="max-w-lg" src="https://error404.fun/img/full-preview/1x/3.png" alt="">
                </div>
                <div class="space-y-2 text-center">
                    <h2 class="text-2xl font-bold md:text-4xl lg:text-6xl">Whooops!</h2>
                    <p class="text-gray-400">{{ __('pub_theme::404.no_article') }}</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
