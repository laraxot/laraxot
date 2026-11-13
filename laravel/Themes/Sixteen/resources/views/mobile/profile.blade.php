<x-mobile::layout>
    <div class="bg-white shadow-sm sticky top-0 z-10">
        <div class="max-w-lg mx-auto px-4 py-4">
            <a href="{{ route('home') }}" class="inline-flex min-h-11 items-center text-blue-600 text-sm">← {{ __('pub_theme::ui.mobile.home') }}</a>
            <h1 class="text-xl font-bold text-gray-900 mt-2">{{ __('pub_theme::ui.mobile.profile') }}</h1>
        </div>
    </div>

    <div class="max-w-lg mx-auto px-4 py-4 pt-16">
        <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm text-center">
            <div class="w-20 h-20 bg-gray-200 rounded-full mx-auto mb-4 flex items-center justify-center">
                <span class="text-3xl">👤</span>
            </div>
            <h2 class="font-semibold text-gray-900">{{ __('pub_theme::ui.mobile.citizen') }}</h2>
            <p class="text-sm text-gray-500 mt-1">demo@fixcity.it</p>
        </div>

        <div class="mt-6 space-y-3">
            <a href="{{ route('container0.view', ['container0' => 'area-personale', 'slug0' => 'pratiche']) }}" class="block min-h-11 bg-white border border-gray-200 rounded-xl p-4 shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-blue-600">
                <div class="flex items-center justify-between">
                    <span class="text-gray-700">{{ __('pub_theme::ui.mobile.my_reports') }}</span>
                    <span class="text-gray-400">→</span>
                </div>
            </a>
            <a href="{{ route('container0.view', ['container0' => 'area-personale', 'slug0' => 'notifiche']) }}" class="block min-h-11 bg-white border border-gray-200 rounded-xl p-4 shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-blue-600">
                <div class="flex items-center justify-between">
                    <span class="text-gray-700">{{ __('pub_theme::ui.mobile.notifications') }}</span>
                    <span class="text-gray-400">→</span>
                </div>
            </a>
            <a href="{{ route('container0.view', ['container0' => 'area-personale', 'slug0' => 'impostazioni']) }}" class="block min-h-11 bg-white border border-gray-200 rounded-xl p-4 shadow-sm focus-visible:outline focus-visible:outline-2 focus-visible:outline-blue-600">
                <div class="flex items-center justify-between">
                    <span class="text-gray-700">{{ __('pub_theme::ui.mobile.settings') }}</span>
                    <span class="text-gray-400">→</span>
                </div>
            </a>
        </div>
    </div>
</x-mobile::layout>
