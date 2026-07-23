<x-filament-panels::page>
    @php
        $stats = $this->getHeaderStats();
    @endphp

    <div class="space-y-6">
        <div class="rounded-xl border border-gray-200 bg-white p-6 shadow-sm dark:border-white/10 dark:bg-white/5">
            <h2 class="text-lg font-semibold text-gray-950 dark:text-white">
                {{ __('inventory.overview_page.title') }}
            </h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
                {{ __('inventory.overview_page.description') }}
            </p>
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ __('inventory.overview_page.stats.products') }}</p>
                <p class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">{{ $stats['products'] }}</p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ __('inventory.overview_page.stats.warehouses') }}</p>
                <p class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">{{ $stats['warehouses'] }}</p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ __('inventory.overview_page.stats.locations') }}</p>
                <p class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">{{ $stats['locations'] }}</p>
            </div>

            <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
                <p class="text-sm text-gray-600 dark:text-gray-300">{{ __('inventory.overview_page.stats.total_stock') }}</p>
                <p class="mt-2 text-2xl font-semibold text-gray-950 dark:text-white">{{ $stats['total_stock'] }}</p>
            </div>
        </div>

        <div class="rounded-xl border border-gray-200 bg-white shadow-sm dark:border-white/10 dark:bg-white/5">
            {{ $this->table }}
        </div>

        <div class="rounded-xl border border-gray-200 bg-gray-50 p-4 shadow-sm dark:border-white/10 dark:bg-white/5">
            <h3 class="font-semibold text-gray-950 dark:text-white">{{ __('inventory.overview_page.notes_title') }}</h3>
            <ul class="mt-3 space-y-2 text-sm text-gray-600 dark:text-gray-300">
                <li>{{ __('inventory.overview_page.notes.aggregate') }}</li>
                <li>{{ __('inventory.overview_page.notes.locations') }}</li>
                <li>{{ __('inventory.overview_page.notes.units') }}</li>
            </ul>
        </div>
    </div>
</x-filament-panels::page>
