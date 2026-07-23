@props([
    'filters' => [],
    'sortOptions' => [],
    'defaultSort' => 'hot',
    'searchPlaceholder' => __('predict::predict_table.search.placeholder'),
])

<div x-data="{ 
    filtersOpen: false,
    currentSort: '{{ $defaultSort }}',
    searchQuery: ''
}" class="space-y-4">
    
    {{-- Top Bar - Always Visible --}}
    <div class="flex flex-col sm:flex-row gap-3 items-start sm:items-center justify-between">
        
        {{-- Search Bar --}}
        <div class="relative flex-1 w-full">
            <input
                type="search"
                x-model="searchQuery"
                @input="$dispatch('filter-search', { search: $event.target.value })"
                placeholder="{{ $searchPlaceholder }}"
                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 
                       bg-white dark:bg-gray-800 
                       text-gray-900 dark:text-gray-100 
                       rounded-lg 
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                       transition-all duration-200
                       placeholder-gray-400 dark:placeholder-gray-500"
            >
            <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" 
                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                      d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>

        {{-- Right Side Controls --}}
        <div class="flex gap-2 w-full sm:w-auto">
            
            {{-- Filter Toggle Button --}}
            <button
                @click="filtersOpen = !filtersOpen"
                class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 
                       px-4 py-2.5 
                       bg-gray-100 dark:bg-gray-800 
                       text-gray-700 dark:text-gray-300 
                       border border-gray-300 dark:border-gray-600
                       rounded-lg 
                       hover:bg-gray-200 dark:hover:bg-gray-700 
                       transition-all duration-200
                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                :aria-expanded="filtersOpen"
                aria-label="Toggle filters"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                </svg>
                <span class="text-sm font-medium" x-text="filtersOpen ? 'Nascondi Filtri' : 'Mostra Filtri'"></span>
                <svg class="w-4 h-4 transition-transform duration-200" 
                     :class="filtersOpen ? 'rotate-180' : ''"
                     fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </button>

            {{-- Sort Dropdown --}}
            <select
                x-model="currentSort"
                @change="$dispatch('filter-sort', { sort: $event.target.value })"
                class="flex-1 sm:flex-none px-4 py-2.5 
                       border border-gray-300 dark:border-gray-600 
                       bg-white dark:bg-gray-800 
                       text-gray-900 dark:text-gray-100 
                       rounded-lg 
                       focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                       transition-all duration-200
                       cursor-pointer"
                aria-label="Sort markets"
            >
                <option value="hot">Hot</option>
                <option value="new">Nuovi</option>
                <option value="volume">Volume</option>
                <option value="participants">Partecipanti</option>
                <option value="ending">Scadenza</option>
                <option value="probability">Probabilità</option>
            </select>
        </div>
    </div>

    {{-- Stats Bar --}}
    <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                  d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
        </svg>
        <span x-text="`${document.querySelectorAll('[data-market-card]').length} mercati disponibili`"></span>
    </div>

    {{-- Collapsible Filters Panel --}}
    <div
        x-show="filtersOpen"
        x-collapse
        x-cloak
        class="p-4 
               bg-gray-50 dark:bg-gray-800/50 
               border border-gray-200 dark:border-gray-700 
               rounded-lg"
    >
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            
            {{-- Category Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Categoria
                </label>
                <select
                    @change="$dispatch('filter-category', { category: $event.target.value })"
                    class="w-full px-3 py-2 
                           border border-gray-300 dark:border-gray-600 
                           bg-white dark:bg-gray-800 
                           text-gray-900 dark:text-gray-100 
                           rounded-lg 
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                           transition-all duration-200"
                >
                    <option value="">Tutte le Categorie</option>
                    @foreach($filters['categories'] ?? [] as $id => $title)
                        <option value="{{ $id }}">{{ $title }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Status Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Status
                </label>
                <select
                    @change="$dispatch('filter-status', { status: $event.target.value })"
                    class="w-full px-3 py-2 
                           border border-gray-300 dark:border-gray-600 
                           bg-white dark:bg-gray-800 
                           text-gray-900 dark:text-gray-100 
                           rounded-lg 
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                           transition-all duration-200"
                >
                    <option value="">Tutti gli Status</option>
                    <option value="active">Aperti</option>
                    <option value="open">In Corso</option>
                    <option value="published">Pubblicati</option>
                    <option value="closed">Chiusi</option>
                    <option value="resolved">Risolti</option>
                </select>
            </div>

            {{-- Outcome Type Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Tipo di Mercato
                </label>
                <select
                    @change="$dispatch('filter-outcome-type', { type: $event.target.value })"
                    class="w-full px-3 py-2 
                           border border-gray-300 dark:border-gray-600 
                           bg-white dark:bg-gray-800 
                           text-gray-900 dark:text-gray-100 
                           rounded-lg 
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                           transition-all duration-200"
                >
                    <option value="">Tutti i Tipi</option>
                    <option value="binary">Binario (2 opzioni)</option>
                    <option value="multi">Multiplo (3+ opzioni)</option>
                </select>
            </div>

            {{-- Expiration Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                    Scadenza
                </label>
                <select
                    @change="$dispatch('filter-expiration', { expiration: $event.target.value })"
                    class="w-full px-3 py-2 
                           border border-gray-300 dark:border-gray-600 
                           bg-white dark:bg-gray-800 
                           text-gray-900 dark:text-gray-100 
                           rounded-lg 
                           focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 
                           transition-all duration-200"
                >
                    <option value="">Tutte le Scadenze</option>
                    <option value="24h">Prossime 24 ore</option>
                    <option value="7d">Prossimi 7 giorni</option>
                    <option value="30d">Prossimi 30 giorni</option>
                    <option value="90d">Prossimi 90 giorni</option>
                </select>
            </div>
        </div>

        {{-- Filter Actions --}}
        <div class="flex flex-col sm:flex-row gap-3 mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
            <button
                @click="$dispatch('filter-apply')"
                class="flex-1 inline-flex items-center justify-center gap-2 
                       px-4 py-2.5 
                       bg-indigo-600 
                       text-white 
                       font-semibold 
                       rounded-lg 
                       hover:bg-indigo-700 
                       transition-all duration-200
                       focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M5 13l4 4L19 7"/>
                </svg>
                Applica Filtri
            </button>
            <button
                @click="
                    $dispatch('filter-reset');
                    filtersOpen = false;
                "
                class="flex-1 inline-flex items-center justify-center gap-2 
                       px-4 py-2.5 
                       bg-gray-200 dark:bg-gray-700 
                       text-gray-700 dark:text-gray-300 
                       font-semibold 
                       rounded-lg 
                       hover:bg-gray-300 dark:hover:bg-gray-600 
                       transition-all duration-200
                       focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                          d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Reset
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    // Listen for filter events and dispatch to Livewire
    document.addEventListener('livewire:init', () => {
        Livewire.on('filter-search', (event) => {
            Livewire.dispatch('setSearch', { search: event.search })
        });

        Livewire.on('filter-sort', (event) => {
            Livewire.dispatch('setSort', { sort: event.sort })
        });

        Livewire.on('filter-category', (event) => {
            Livewire.dispatch('setFilter', { field: 'category', value: event.category })
        });

        Livewire.on('filter-status', (event) => {
            Livewire.dispatch('setFilter', { field: 'status', value: event.status })
        });

        Livewire.on('filter-outcome-type', (event) => {
            Livewire.dispatch('setFilter', { field: 'outcome_type', value: event.type })
        });

        Livewire.on('filter-expiration', (event) => {
            Livewire.dispatch('setFilter', { field: 'expiration', value: event.expiration })
        });

        Livewire.on('filter-apply', () => {
            // Filters are applied automatically on change
        });

        Livewire.on('filter-reset', () => {
            Livewire.dispatch('resetFilters')
        });
    });
</script>
@endpush

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush
