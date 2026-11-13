<x-mobile::layout>
    <div class="bg-white shadow-sm sticky top-0 z-10">
        <div class="max-w-lg mx-auto px-4 py-4">
            <a href="{{ route('container0.index', ['container0' => 'tickets']) }}" class="inline-flex min-h-11 items-center text-blue-600 text-sm">← {{ __('pub_theme::ui.mobile.back_reports') }}</a>
            <h1 class="text-xl font-bold text-gray-900 mt-2" x-data="{ title: '' }" x-init="fetch('/api/tickets/geojson').then(r => r.json()).then(d => title = d.features.find(f => f.properties.id === '{{ request()->segment(3) }}')?.properties.title || @js(__('pub_theme::ui.mobile.detail_report')))" x-text="title"></h1>
        </div>
    </div>

    <div class="max-w-lg mx-auto px-4 py-4 pt-16">
        <div class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm" x-data="{ ticket: null }" x-init="fetch('/api/tickets/geojson').then(r => r.json()).then(d => ticket = d.features.find(f => f.properties.id === '{{ request()->segment(3) }}'))">
            <template x-if="ticket">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <span class="px-2 py-1 text-xs font-medium rounded-full"
                              :style="'background-color: ' + ticket.properties.status.color + '20; color: ' + ticket.properties.status.color"
                              x-text="ticket.properties.status.label"></span>
                    </div>
                    <p class="text-gray-700" x-text="ticket.properties.address"></p>
                    <p class="text-sm text-gray-500 mt-2" x-text="ticket.properties.city"></p>
                </div>
            </template>
        </div>
    </div>
</x-mobile::layout>
