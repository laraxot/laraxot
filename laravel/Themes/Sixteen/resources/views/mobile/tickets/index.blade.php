<x-mobile::layout>
    <div class="bg-white shadow-sm sticky top-0 z-10">
        <div class="max-w-lg mx-auto px-4 py-4">
            <h1 class="text-xl font-bold text-gray-900">{{ __('pub_theme::ui.mobile.reports') }}</h1>
        </div>
    </div>

    <div class="max-w-lg mx-auto px-4 py-4 pt-16">
        <div class="space-y-3">
            <template x-data="{ tickets: [] }" x-init="fetch('/api/tickets/geojson').then(r => r.json()).then(d => tickets = d.features)">
                <template x-for="ticket in tickets" :key="ticket.properties.id">
                    <a :href="@js(route('container0.view', ['container0' => 'tickets', 'slug0' => '__ticket__'])) .replace('__ticket__', ticket.properties.id)" class="block bg-white border border-gray-200 rounded-xl p-4 shadow-sm">
                        <div class="flex min-w-0 items-start justify-between">
                            <div class="min-w-0 flex-1 break-words">
                                <div class="font-medium text-gray-900 break-words" x-text="ticket.properties.title"></div>
                                <div class="mt-1 break-words text-sm text-gray-500" x-text="ticket.properties.address"></div>
                                <div class="mt-1 break-words text-xs text-gray-400" x-text="ticket.properties.city"></div>
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
