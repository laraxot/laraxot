@php
    $placeholder = $placeholder ?? 'Cerca articoli...';
    $showSuggestions = $showSuggestions ?? true;
    $debounceMs = $debounceMs ?? 300;
    $maxSuggestions = $maxSuggestions ?? 5;
@endphp

<div class="relative" x-data="blogSearch()" x-init="init()">
    {{-- Search Input --}}
    <div class="relative">
        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        
        <input 
            type="text" 
            x-model="query"
            @input.debounce.{{ $debounceMs }}ms="search()"
            @focus="showResults = true"
            @blur="setTimeout(() => showResults = false, 200)"
            placeholder="{{ $placeholder }}"
            class="w-full pl-12 pr-12 py-4 border border-gray-300 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent shadow-sm transition-all"
            :class="{ 'rounded-b-none border-b-0': showResults && results.length > 0 }"
        >
        
        <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
            {{-- Loading Spinner --}}
            <div x-show="loading" class="text-blue-500">
                <svg class="animate-spin w-5 h-5" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
            
            {{-- Clear Button --}}
            <button 
                x-show="query.length > 0"
                @click="clearSearch()"
                class="ml-2 text-gray-400 hover:text-gray-600 transition-colors"
            >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
    
    {{-- Search Results Dropdown --}}
    <div 
        x-show="showResults && (results.length > 0 || query.length > 0)"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform scale-95"
        x-transition:enter-end="opacity-1 transform scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-1 transform scale-100"
        x-transition:leave-end="opacity-0 transform scale-95"
        class="absolute z-50 w-full mt-0 bg-white rounded-b-xl shadow-xl border border-gray-200 border-t-0 overflow-hidden"
        style="display: none;"
    >
        {{-- Search Suggestions --}}
        @if($showSuggestions)
            <div x-show="results.length > 0" class="max-h-96 overflow-y-auto">
                <template x-for="result in results.slice(0, {{ $maxSuggestions }})" :key="result.url">
                    <a 
                        :href="result.url"
                        class="flex items-start p-4 hover:bg-gray-50 transition-colors border-b border-gray-100 last:border-b-0"
                        @click="showResults = false"
                    >
                        <div class="flex-shrink-0 w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="text-sm font-medium text-gray-900 truncate" x-html="highlightMatch(result.title)"></h4>
                            <p class="text-sm text-gray-500 mt-1 line-clamp-2" x-text="result.excerpt"></p>
                            <div class="flex items-center mt-2 text-xs text-gray-400">
                                <span x-text="result.category"></span>
                                <span class="mx-2">•</span>
                                <span x-text="result.date"></span>
                                <span class="mx-2">•</span>
                                <span x-text="result.reading_time"></span>
                            </div>
                        </div>
                    </a>
                </template>
            </div>
        @endif
        
        {{-- No Results --}}
        <div x-show="query.length > 0 && results.length === 0" class="p-8 text-center">
            <svg class="w-12 h-12 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="text-gray-500">Nessun articolo trovato per "<span x-text="query"></span>"</p>
            <p class="text-sm text-gray-400 mt-2">Prova con altri termini di ricerca</p>
        </div>
        
        {{-- View All Results --}}
        <div x-show="results.length > 0" class="p-3 bg-gray-50 border-t border-gray-200">
            <a 
                href="#" 
                class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center justify-center"
                @click="showResults = false"
            >
                Vedi tutti i risultati per "<span x-text="query"></span>"
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>
    </div>
    
    {{-- Advanced Search Toggle --}}
    <div class="mt-3 flex items-center justify-between">
        <button 
            @click="showAdvanced = !showAdvanced"
            class="text-sm text-blue-600 hover:text-blue-700 font-medium flex items-center"
        >
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
            </svg>
            Ricerca avanzata
        </button>
        
        {{-- Search Stats --}}
        <div x-show="query.length > 0" class="text-xs text-gray-500">
            <span x-show="!loading" x-text="`${results.length} risultati`"></span>
            <span x-show="loading">Ricerca in corso...</span>
        </div>
    </div>
    
    {{-- Advanced Search Panel --}}
    <div 
        x-show="showAdvanced"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-1 transform translate-y-0"
        class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200"
        style="display: none;"
    >
        <h4 class="font-medium text-gray-900 mb-3">Filtri di ricerca avanzata</h4>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            {{-- Category Filter --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                <select x-model="advancedFilters.category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Tutte le categorie</option>
                    <option value="radioprotezione">Radioprotezione</option>
                    <option value="normativa">Normativa</option>
                    <option value="elettromedicali">Elettromedicali</option>
                    <option value="guide-pratiche">Guide Pratiche</option>
                </select>
            </div>
            
            {{-- Date Range --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Periodo</label>
                <select x-model="advancedFilters.dateRange" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Qualsiasi periodo</option>
                    <option value="today">Oggi</option>
                    <option value="week">Ultima settimana</option>
                    <option value="month">Ultimo mese</option>
                    <option value="year">Ultimo anno</option>
                </select>
            </div>
            
            {{-- Sort By --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Ordina per</label>
                <select x-model="advancedFilters.sortBy" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="relevance">Rilevanza</option>
                    <option value="date">Data (più recente)</option>
                    <option value="date_asc">Data (più vecchio)</option>
                    <option value="title">Titolo (A-Z)</option>
                </select>
            </div>
        </div>
        
        <div class="mt-4 flex justify-end space-x-3">
            <button 
                @click="resetAdvancedFilters()"
                class="px-4 py-2 text-gray-600 hover:text-gray-700 font-medium"
            >
                Resetta filtri
            </button>
            <button 
                @click="applyAdvancedFilters()"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-medium"
            >
                Applica filtri
            </button>
        </div>
    </div>
</div>

<script>
function blogSearch() {
    return {
        query: '',
        results: [],
        loading: false,
        showResults: false,
        showAdvanced: false,
        advancedFilters: {
            category: '',
            dateRange: '',
            sortBy: 'relevance'
        },
        
        init() {
            // Sample data for demonstration
            this.sampleArticles = [
                {
                    title: 'Nuove Linee Guida per il Controllo Radioprotezione 2026',
                    excerpt: 'Scopri le ultime direttive europee e come impattano sulla gestione della radioprotezione negli studi dentistici e veterinari.',
                    category: 'Radioprotezione',
                    date: '15 Febbraio 2026',
                    reading_time: '5 min lettura',
                    url: '/blog/nuove-linee-guida-radioprotezione-2026'
                },
                {
                    title: 'D.Lgs 101/2020: Cosa Cambia per la Tua Attività',
                    excerpt: 'Analisi dettagliata delle modifiche introdotte dal Decreto Legislativo 101/2020 e come adeguare la tua struttura.',
                    category: 'Normativa',
                    date: '10 Febbraio 2026',
                    reading_time: '8 min lettura',
                    url: '/blog/dlgs-101-2020-cambia-attivita'
                },
                {
                    title: 'Manutenzione Elettromedicali: IEC 62353 Spiegato Semplice',
                    excerpt: 'Comprendi la normativa IEC 62353 per la sicurezza elettrica dei dispositivi medici. Frequenza dei controlli.',
                    category: 'Elettromedicali',
                    date: '1 Febbraio 2026',
                    reading_time: '6 min lettura',
                    url: '/blog/manutenzione-elettromedicali-iec-62353'
                }
            ];
        },
        
        async search() {
            if (this.query.length < 2) {
                this.results = [];
                return;
            }
            
            this.loading = true;
            
            // Simulate API call
            await new Promise(resolve => setTimeout(resolve, 300));
            
            // Filter sample data
            this.results = this.sampleArticles.filter(article => 
                article.title.toLowerCase().includes(this.query.toLowerCase()) ||
                article.excerpt.toLowerCase().includes(this.query.toLowerCase())
            );
            
            this.loading = false;
        },
        
        clearSearch() {
            this.query = '';
            this.results = [];
            this.showResults = false;
        },
        
        highlightMatch(text) {
            if (!this.query) return text;
            const regex = new RegExp(`(${this.query})`, 'gi');
            return text.replace(regex, '<mark class="bg-yellow-200">$1</mark>');
        },
        
        resetAdvancedFilters() {
            this.advancedFilters = {
                category: '',
                dateRange: '',
                sortBy: 'relevance'
            };
        },
        
        applyAdvancedFilters() {
            this.showAdvanced = false;
            this.search(); // Re-run search with advanced filters
        }
    }
}
</script>