{{--
/**
 * Bandi e Avvisi Component - Bootstrap Italia Compliant
 * 
 * Componente per la gestione di bandi di gara, avvisi pubblici e opportunità
 * Conforme alle linee guida AGID per la trasparenza amministrativa
 * 
 * @param array $items Array di bandi/avvisi
 * @param string $title Titolo della sezione (default: 'Bandi e Avvisi')
 * @param array $types Tipologie disponibili per filtro
 * @param array $statuses Stati disponibili per filtro
 * @param bool $showFilters Mostra filtri di ricerca
 * @param bool $showCounters Mostra contatori per tipo/stato
 * @param int $itemsPerPage Numero di elementi per pagina
 */
--}}

@props([
    'items' => [],
    'title' => 'Bandi e Avvisi',
    'types' => ['Tutti', 'Bandi di Gara', 'Avvisi Pubblici', 'Manifestazioni di Interesse', 'Altro'],
    'statuses' => ['Tutti', 'Aperti', 'In corso', 'Scaduti', 'Annullati', 'Aggiudicati'],
    'showFilters' => true,
    'showCounters' => true,
    'itemsPerPage' => 8
])

<div class="tenders-notices bg-white rounded-lg shadow-md overflow-hidden"
     x-data="{
        items: {{ json_encode($items) }},
        filteredItems: {{ json_encode($items) }},
        searchTerm: '',
        selectedType: 'Tutti',
        selectedStatus: 'Tutti',
        currentPage: 1,
        itemsPerPage: {{ $itemsPerPage }},
        
        get filteredItems() {
            return this.items.filter(item => {
                const matchesSearch = !this.searchTerm || 
                    item.title.toLowerCase().includes(this.searchTerm.toLowerCase()) ||
                    (item.description && item.description.toLowerCase().includes(this.searchTerm.toLowerCase())) ||
                    (item.reference && item.reference.toLowerCase().includes(this.searchTerm.toLowerCase()));
                
                const matchesType = this.selectedType === 'Tutti' || 
                    item.type === this.selectedType;
                
                const matchesStatus = this.selectedStatus === 'Tutti' || 
                    item.status === this.selectedStatus;
                
                return matchesSearch && matchesType && matchesStatus;
            });
        },
        
        get paginatedItems() {
            const start = (this.currentPage - 1) * this.itemsPerPage;
            return this.filteredItems.slice(start, start + this.itemsPerPage);
        },
        
        get totalPages() {
            return Math.ceil(this.filteredItems.length / this.itemsPerPage);
        },
        
        get typeCounts() {
            const counts = {};
            this.types.forEach(type => {
                counts[type] = this.items.filter(item => item.type === type).length;
            });
            return counts;
        },
        
        get statusCounts() {
            const counts = {};
            this.statuses.forEach(status => {
                counts[status] = this.items.filter(item => item.status === status).length;
            });
            return counts;
        },
        
        updateFilters() {
            this.currentPage = 1;
        },
        
        getStatusColor(status) {
            const colors = {
                'Aperti': 'bg-green-100 text-green-800',
                'In corso': 'bg-blue-100 text-blue-800',
                'Scaduti': 'bg-red-100 text-red-800',
                'Annullati': 'bg-gray-100 text-gray-800',
                'Aggiudicati': 'bg-purple-100 text-purple-800'
            };
            return colors[status] || 'bg-gray-100 text-gray-800';
        },
        
        getTypeIcon(type) {
            const icons = {
                'Bandi di Gara': '📋',
                'Avvisi Pubblici': '📢',
                'Manifestazioni di Interesse': '🤝',
                'Altro': '📄'
            };
            return icons[type] || '📄';
        },
        
        formatDate(dateString) {
            if (!dateString) return '';
            return new Date(dateString).toLocaleDateString('it-IT');
        },
        
        isExpired(expiryDate) {
            if (!expiryDate) return false;
            return new Date(expiryDate) < new Date();
        }
     }">

    {{-- Header --}}
    <div class="bg-primary-800 text-white px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold">{{ $title }}</h2>
                <p class="text-primary-100 text-sm mt-1">
                    Opportunità e procedure amministrative del comune
                </p>
            </div>
            
            <div class="flex items-center space-x-4">
                @if($showCounters)
                <div class="text-right">
                    <div class="text-primary-200 text-sm">
                        <span x-text="filteredItems.length"></span> risultati
                    </div>
                    <div class="text-primary-300 text-xs" x-show="filteredItems.length !== items.length">
                        su <span x-text="items.length"></span> totali
                    </div>
                </div>
                @endif
                
                <button @click="$dispatch('open-modal', { id: 'tenders-help' })"
                        class="p-1 text-primary-200 hover:text-white transition-colors"
                        title="Guida all'uso">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </button>
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
                       placeholder="Cerca per titolo, descrizione o riferimento..."
                       class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
            </div>

            {{-- Tipologia --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipologia</label>
                <select x-model="selectedType" @change="updateFilters"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @foreach($types as $type)
                    <option value="{{ $type }}">
                        {{ $type }} @if($showCounters && $type !== 'Tutti') (<span x-text="typeCounts['{{ $type }}']"></span>)@endif
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Stato --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Stato</label>
                <select x-model="selectedStatus" @change="updateFilters"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @foreach($statuses as $status)
                    <option value="{{ $status }}">
                        {{ $status }} @if($showCounters && $status !== 'Tutti') (<span x-text="statusCounts['{{ $status }}']"></span>)@endif
                    </option>
                    @endforeach
                </select>
            </div>

            {{-- Reset --}}
            <div class="flex items-end">
                <button @click="searchTerm = ''; selectedType = 'Tutti'; selectedStatus = 'Tutti'; updateFilters()"
                        class="w-full px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-gray-500">
                    Reset
                </button>
            </div>
        </div>
    </div>
    @endif

    {{-- Lista bandi/avvisi --}}
    <div class="divide-y divide-gray-200">
        <template x-if="filteredItems.length === 0">
            <div class="text-center py-12">
                <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="text-gray-500">Nessun bando o avviso trovato</p>
                <p class="text-gray-400 text-sm mt-2">Prova a modificare i filtri di ricerca</p>
            </div>
        </template>

        <template x-for="(item, index) in paginatedItems" :key="item.id || index">
            <div class="p-6 hover:bg-gray-50 transition-colors">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        {{-- Header con tipo e stato --}}
                        <div class="flex items-center gap-3 mb-3">
                            {{-- Icona tipo --}}
                            <span class="text-lg" x-text="getTypeIcon(item.type)"></span>
                            
                            {{-- Tipo --}}
                            <span class="text-sm font-medium text-gray-600" 
                                  x-text="item.type || 'Bando'">
                            </span>
                            
                            {{-- Riferimento --}}
                            <template x-if="item.reference">
                                <span class="text-sm text-gray-500" x-text="'Rif. ' + item.reference"></span>
                            </template>
                            
                            {{-- Stato --}}
                            <template x-if="item.status">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                      :class="getStatusColor(item.status)"
                                      x-text="item.status">
                                </span>
                            </template>
                        </div>

                        {{-- Titolo --}}
                        <h3 class="text-lg font-semibold text-gray-900 mb-2"
                            x-text="item.title">
                        </h3>

                        {{-- Descrizione --}}
                        <template x-if="item.description">
                            <p class="text-gray-600 mb-4 line-clamp-2" x-text="item.description"></p>
                        </template>

                        {{-- Metadati --}}
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm text-gray-600">
                            {{-- Date importanti --}}
                            <template x-if="item.publish_date">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <span x-text="'Pubblicato: ' + formatDate(item.publish_date)"></span>
                                </div>
                            </template>

                            <template x-if="item.expiry_date">
                                <div class="flex items-center" :class="{ 'text-red-600 font-medium': isExpired(item.expiry_date) }">
                                    <svg class="w-4 h-4 mr-2" :class="{ 'text-red-500': isExpired(item.expiry_date), 'text-gray-400': !isExpired(item.expiry_date) }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span x-text="'Scadenza: ' + formatDate(item.expiry_date)"></span>
                                </div>
                            </template>

                            <template x-if="item.amount">
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span x-text="'Importo: ' + item.amount"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Azioni --}}
                    <div class="flex flex-col space-y-2 ml-4 min-w-[120px]">
                        <template x-if="item.document_url">
                            <a :href="item.document_url"
                               class="btn-italia bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 text-center"
                               target="_blank"
                               rel="noopener noreferrer">
                                Documento
                            </a>
                        </template>

                        <template x-if="item.download_url">
                            <a :href="item.download_url"
                               class="btn-italia bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 text-center"
                               download>
                                Download
                            </a>
                        </template>

                        <template x-if="item.info_url">
                            <a :href="item.info_url"
                               class="btn-italia bg-green-600 hover:bg-green-700 text-white px-4 py-2 text-center"
                               target="_blank">
                                Info
                            </a>
                        </template>
                    </div>
                </div>
            </div>
        </template>
    </div>

    {{-- Paginazione --}}
    <div class="bg-gray-50 px-6 py-4 border-t border-gray-200" x-show="filteredItems.length > 0">
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
</div>

{{-- Stili specifici --}}
@pushOnce('styles')
<style>
.tenders-notices {
    min-height: 500px;
}

.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
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
    white-space: nowrap;
}

.btn-italia:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

@media (max-width: 768px) {
    .tenders-notices {
        margin: 0 -1rem;
        border-radius: 0;
        border: none;
    }
    
    .tenders-notices > div {
        padding: 1rem;
    }
    
    .btn-italia {
        font-size: 0.875rem;
        padding: 0.5rem 0.75rem;
    }
}
</style>
@endPushOnce

{{--
Usage Examples:

1. Bandi base:
<x-pub_theme::municipal.tenders-notices 
    :items="$tenders" />

2. Con dati personalizzati:
<x-pub_theme::municipal.tenders-notices 
    title="Opportunità e Bandi"
    :items="[
        {
            'id': 1,
            'type': 'Bandi di Gara',
            'status': 'Aperti',
            'title': 'Affidamento servizi di manutenzione',
            'reference': 'BG-2023-001',
            'description': 'Servizi di manutenzione ordinaria degli edifici comunali',
            'publish_date': '2023-01-15',
            'expiry_date': '2023-02-15',
            'amount': '€ 50.000,00',
            'document_url': '/bandi/documento/1'
        }
    ]"
    :types="['Tutti', 'Bandi di Gara', 'Avvisi', 'MI']"
    :statuses="['Tutti', 'Aperti', 'Scaduti', 'Aggiudicati']" />

3. Senza filtri e contatori:
<x-pub_theme::municipal.tenders-notices 
    :show-filters="false"
    :show-counters="false"
    :items="$recentTenders" />
--}}