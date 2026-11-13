<x-mobile::layout>
    <div class="bg-white shadow-sm">
        <div class="max-w-lg mx-auto px-4 py-6">
            <h1 class="text-2xl font-bold text-gray-900">{{ __('pub_theme::ui.mobile.home_title') }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ __('pub_theme::ui.mobile.home_subtitle') }}</p>
        </div>
    </div>

    <div class="max-w-lg mx-auto px-4 py-6">
        <div class="grid grid-cols-2 gap-4 mb-6">
            <a href="{{ route('container0.view', ['container0' => 'tickets', 'slug0' => 'create']) }}" class="bg-blue-600 text-white rounded-xl p-4 text-center shadow-sm">
                <div class="text-3xl mb-2">📸</div>
                <div class="font-semibold">{{ __('pub_theme::ui.mobile.new_report') }}</div>
                <div class="text-xs opacity-75">{{ __('pub_theme::ui.mobile.report_action') }}</div>
            </a>
            <a href="{{ route('container0.index', ['container0' => 'tickets']) }}" class="bg-white border border-gray-200 rounded-xl p-4 text-center shadow-sm">
                <div class="text-3xl mb-2">🗺️</div>
                <div class="font-semibold text-gray-900">{{ __('pub_theme::ui.mobile.map') }}</div>
                <div class="text-xs text-gray-500">{{ __('pub_theme::ui.mobile.explore') }}</div>
            </a>
        </div>

        <h2 class="font-semibold text-gray-900 mb-3">{{ __('pub_theme::ui.mobile.recent_reports') }}</h2>
        <div class="space-y-3">
            <template x-data="{ tickets: [] }" x-init="fetch('/api/tickets/geojson').then(r => r.json()).then(d => tickets = d.features)">
                <template x-for="ticket in tickets" :key="ticket.properties.id">
                    <a :href="ticket.properties.detail_url" class="block bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                        <div class="flex min-w-0 items-start justify-between">
                            <div class="min-w-0 flex-1 break-words">
                                <div class="font-medium text-gray-900 break-words" x-text="ticket.properties.title"></div>
                                <div class="mt-1 break-words text-sm text-gray-500" x-text="ticket.properties.address"></div>
                            </div>
                            <span class="ml-2 max-w-[45%] shrink-0 break-words px-2 py-1 text-xs font-medium rounded-full"
                                  :style="'background-color: ' + ticket.properties.status.color + '20; color: ' + ticket.properties.status.color"
                                  x-text="ticket.properties.status.label"></span>
                        </div>
                    </a>
                </template>
            </template>
        </div>
    </div>
</x-mobile::layout>
