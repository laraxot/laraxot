{{--
/**
 * Albo Pretorio Component - Bootstrap Italia Compliant
 * 
 * Componente per la gestione dell'Albo Pretorio online
 * Conforme al D.Lgs. 33/2013 e linee guida AGID
 * 
 * @param array $publications Array di pubblicazioni nell'albo
 * @param string $title Titolo della sezione (default: 'Albo Pretorio')
 * @param bool $showFilters Mostra filtri di ricerca
 * @param array $categories Categorie disponibili per filtro
 * @param array $years Anni disponibili per filtro
 * @param int $itemsPerPage Numero di elementi per pagina (default: 10)
 * @param bool $showPagination Mostra paginazione
 */
--}}

@props([
    'publications' => [],
    'title' => 'Albo Pretorio',
    'showFilters' => true,
    'categories' => ['Tutti', 'Determine', 'Delibere', 'Avvisi', 'Bandi', 'Altro'],
    'years' => [2023, 2024, 2025],
    'itemsPerPage' => 10,
    'showPagination' => true
])

<div class="albo-pretorio bg-white rounded-lg shadow-md overflow-hidden"
     x-data="{
        publications: {{ json_encode($publications) }},
        filteredPublications: {{ json_encode($publications) }},
        searchTerm: '',
        selectedCategory: 'Tutti',
        selectedYear: 'Tutti',
        currentPage: 1,
        itemsPerPage: {{ $itemsPerPage }},
        
        get filteredItems() {
            return this.filteredPublications.filter(pub => {
                const matchesSearch = !this.searchTerm || 
                    pub.title.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                    (pub.description && pub.description.toLowerCase().includes(this.searchTerm.toLowerCase()));
                
                const matchesCategory = this.selectedCategory === 'Tutti' || 
                    pub.category === this.selectedCategory;
                
                const matchesYear = this.selectedYear === 'Tutti' || 
                    (pub.publish_date && pub.publish_date.includes(this.selectedYear));
                
                return matchesSearch && matchesCategory && matchesYear;
            });
        },
        
        get paginatedItems() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            return this.filteredItems.slice(start, start + this.itemsPerPage);
        },
        
        get totalPages() {
            return Math.ceil(this.filteredItems.length / this.itemsPerPage);
        },
        
        updateFilters() {
            this.currentPage = 1;
        }
     }">

    {{-- Header --}}
    <div class="bg-primary-800 text-white px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold">{{ $title }}</h2>
                <p class="text-primary-100 text-sm mt-1">
                    Pubblicazioni ufficiali del comune - Art. 32 D.Lgs. 33/2013
                </p>
            </div>
            
            <div class="flex items-center space-x-2">
                <span class="text-primary-200 text-sm">
                    <span x-text="filteredItems.length"></span> pubblicazioni
                </span>
            </div>
        </div>
    </div>

    {{-- Filtri --}}
    @if($showFilters)
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Ricerca --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Cerca</label>
                <input type="text" 
                       x-model="searchTerm" 
                       @input="updateFilters"
                       placeholder="Cerca per titolo o descrizione..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            {{-- Categoria --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                <select x-model="selectedCategory" @change="updateFilters"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @foreach($categories as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Anno --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Anno</label>
                <select x-model="selectedYear" @change="updateFilters"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="Tutti">Tutti</option>
                    @foreach($years as $year)
                    <option value="{{ $year }}">{{ $year }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Reset --}}
            <div class="flex items-end">
                <button @click="searchTerm = ''; selectedCategory = 'Tutti'; selectedYear = 'Tutti'; updateFilters()"
                        class="w-full px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Reset
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Lista pubblicazioni --}}
    <div class="divide-y divide-gray-200">
        <template x-if="filteredItems.length === 0">
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500">Nessuna pubblicazione trovata</p>
            </div>
        </template>

        <template x-for="(publication, index) in paginatedItems" :key="publication.id || index">
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        {{-- Badge categoria --}}
                        <span class="inline-block px-2 py-1 text-xs font-medium bg-blue-100 text-blue-800 rounded-full mb-2"
                              x-text="publication.category || 'Generale'">
                        </span>

                        {{-- Titolo --}}
                        <h3 class="text-lg font-semibold text-gray-900 mb-2"
                            x-text="publication.title">
                        </h3>

                        {{-- Descrizione --}}
                        <template x-if="publication.description">
                            <p class="text-gray-600 mb-3" x-text="publication.description"></p>
                        </template>

                        {{-- Metadati --}}
                        <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                            <template x-if="publication.publish_date">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span x-text="publication.publish_date"></span>
                                </div>
                            </template>

                            <template x-if="publication.number">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <span x-text="'N. ' + publication.number"></span>
                                </div>
                            </template>

                            <template x-if="publication.expiry_date">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span x-text="'Scadenza: ' + publication.expiry_date"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Azioni --}}
                    <div class="flex items-center space-x-2 ml-4">
                        <template x-if="publication.document_url">
                            <a :href="publication.document_url"
                               class="btn-italia bg-blue-600 hover:bg-blue-700 text-white px-4 py-2"
                               target="_blank"
                               rel="noopener noreferrer">
                                Visualizza
                            </a>
                        </template>

                        <template x-if="publication.download_url">
                            <a :href="publication.download_url"
                               class="btn-italia bg-gray-600 hover:bg-gray-700 text-white px-3 py-2"
                               download>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                            </a>
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- Paginazione --}}
    @if($showPagination)
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Mostrati <span x-text="paginatedItems.length"></span> di 
                <span x-text="filteredItems.length"></span> risultati
            </div>

            <div class="flex space-x-2" x-show="totalPages > 1">
                <button @click="currentPage = Math.max(1, currentPage - 1)"
                        :disabled="currentPage === 1"
                        :class="{ 'opacity-50 cursor-not-allowed': currentPage === 1 }"
                        class="px-3 py-1 border border-gray-300 rounded text-sm">
                    Precedente
                </button>

                <span class="px-3 py-1 text-sm text-gray-700">
                    Pagina <span x-text="currentPage"></span> di 
                    <span x-text="totalPages"></span>
                </span>

                <button @click="currentPage = Math.min(totalPages, currentPage + 1)"
                        :disabled="currentPage === totalPages"
                        :class="{ 'opacity-50 cursor-not-allowed': currentPage === totalPages }"
                        class="px-3 py-1 border border-gray-300 rounded text-sm">
                    Successiva
                </button>
            </div>
        </div>
    </div>
    @endif
</div>

{{-- Stili specifici --}}
@pushOnce('styles')
<style>
.albo-pretorio {
    min-height: 400px;
}

.btn-italia {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.375rem;
    font-weight: 500;
    text-decoration: none;
    transition: all 0.2s;
    min-height: 2.25rem;
}

.btn-italia:hover {
    transform: translateY(-1px);
}

@media (max-width: 768px) {
    .albo-pretorio {
        margin: 0 -1rem;
        border-radius: 0;
        border: none;
    }
    
    .albo-pretorio > div {
        padding: 1rem;
    }
}
</style>
@endPushOnce

{{--
Usage Examples:

1. Albo base:
<x-pub_theme::municipal.albo-pretorio 
    :publications="$publications" />

2. Con dati personalizzati:
<x-pub_theme::municipal.albo-pretorio 
    title="Albo Pretorio del Comune"
    :publications="[
        {
            'id': 1,
            'title': 'Determina per affidamento servizi',
            'category': 'Determine',
            'number': '123/2023',
            'publish_date': '15/01/2023',
            'expiry_date': '30/01/2023',
            'document_url': '/albo/documento/1'
        }
    ]"
    :categories="['Tutti', 'Determine', 'Delibere', 'Bandi']"
    :years="[2022, 2023, 2024]" />

3. Senza filtri:
<x-pub_theme::municipal.albo-pretorio 
    :show-filters="false"
    :show-pagination="false"
    :publications="$recentPublications" />
--}}