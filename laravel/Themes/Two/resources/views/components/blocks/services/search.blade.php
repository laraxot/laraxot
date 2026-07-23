{{--
/**
 * Advanced Services Search Component
 * 
 * Intelligent search functionality with filters, suggestions, and real-time results
 * Fully accessible and mobile-optimized
 * 
 * @param string $placeholder Placeholder text for search input
 * @param array $categories Available service categories
 * @param array $filters Available filters
 * @param bool $showAdvanced Whether to show advanced filters by default
 * @param string $searchApi API endpoint for search requests
 * @param string $class Additional CSS classes
 */
--}}

@props([
    'placeholder' => 'Cerca servizi...',
    'categories' => [],
    'filters' => [],
    'showAdvanced' => false,
    'searchApi' => '/api/services/search',
    'class' => ''
])

@php
    $searchId = 'services-search-' . uniqid();
    $filtersOpen = $showAdvanced;
@endphp

<div class="services-search {{ $class }}" id="{{ $searchId }}">
    {{-- Search Input Section --}}
    <div class="search-input-section bg-white rounded-lg shadow-sm border border-gray-200 p-4">
        <div class="relative">
            {{-- Search Input --}}
            <div class="search-input-wrapper relative">
                <input 
                    type="text" 
                    id="search-input-{{ $searchId }}"
                    name="q"
                    placeholder="{{ $placeholder }}"
                    class="w-full pl-12 pr-12 py-4 text-lg border border-gray-300 rounded-lg focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all duration-200"
                    autocomplete="off"
                    role="searchbox"
                    aria-label="{{ $placeholder }}"
                    aria-describedby="search-help-{{ $searchId }}"
                />
                
                {{-- Search Icon --}}
                <div class="absolute left-4 top-1/2 transform -translate-y-1/2">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                
                {{-- Clear Button --}}
                <button 
                    type="button" 
                    id="clear-search-{{ $searchId }}"
                    class="absolute right-12 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 transition-colors hidden focus:outline-none focus:ring-2 focus:ring-[#1E5A96] rounded-md"
                    aria-label="Cancella testo di ricerca"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
                
                {{-- Search Button --}}
                <button 
                    type="submit"
                    aria-label="Esegui ricerca servizi"
                    class="absolute right-2 top-1/2 transform -translate-y-1/2 bg-primary-600 text-white px-4 py-2 rounded-md hover:bg-primary-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1E5A96]"
                >
                    Cerca
                </button>
            </div>
            
            {{-- Help Text --}}
            <div id="search-help-{{ $searchId }}" class="sr-only">
                Inserisci il nome del servizio o parole chiave per trovare quello che cerchi
            </div>
        </div>
        
        {{-- Quick Filters --}}
        @if($categories)
        <div class="quick-filters mt-4">
            <p class="text-sm font-medium text-gray-700 mb-2">Categorie popolari:</p>
            <div class="flex flex-wrap gap-2">
                @foreach($categories as $category)
                <button 
                    type="button"
                    class="category-chip px-3 py-1 text-sm bg-gray-100 text-gray-700 rounded-full hover:bg-primary-100 hover:text-primary-700 transition-colors focus:ring-2 focus:ring-primary-500"
                    data-category="{{ $category['id'] ?? $category }}"
                >
                    {{ $category['name'] ?? $category }}
                </button>
                @endforeach
            </div>
        </div>
        @endif
        
        {{-- Advanced Filters Toggle --}}
                <button 
                    type="button"
                    id="toggle-filters-{{ $searchId }}"
                    class="mt-4 text-primary-600 hover:text-primary-700 text-sm font-medium flex items-center gap-1 focus:outline-none focus:ring-2 focus:ring-[#1E5A96] rounded-md px-2 py-1"
                    aria-expanded="{{ $filtersOpen ? 'true' : 'false' }}"
                    aria-controls="advanced-filters-{{ $searchId }}"
                    aria-label="Mostra/Nascondi filtri avanzati di ricerca"
                >
                        <svg class="w-4 h-4 transform transition-transform {{ $filtersOpen ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
            Filtri avanzati
        </button>
    </div>
    
    {{-- Advanced Filters Section --}}
    <div 
        id="advanced-filters-{{ $searchId }}" 
        class="advanced-filters mt-4 bg-gray-50 rounded-lg p-4 border border-gray-200 {{ $filtersOpen ? '' : 'hidden' }}"
    >
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Filtri avanzati</h3>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            {{-- Category Filter --}}
            <div class="filter-group">
                <label for="category-filter-{{ $searchId }}" class="block text-sm font-medium text-gray-700 mb-2">
                    Categoria
                </label>
                <select 
                    id="category-filter-{{ $searchId }}"
                    name="category"
                    autocomplete="off"
                    aria-describedby="category-filter-help-{{ $searchId }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1E5A96] focus:border-transparent"
                >
                    <option value="">Tutte le categorie</option>
                    @foreach($categories as $category)
                    <option value="{{ $category['id'] ?? $category }}">
                        {{ $category['name'] ?? $category }}
                    </option>
                    @endforeach
                </select>
            </div>
            
            {{-- Status Filter --}}
            <div class="filter-group">
                <label for="status-filter-{{ $searchId }}" class="block text-sm font-medium text-gray-700 mb-2">
                    Stato del servizio
                </label>
                <select 
                    id="status-filter-{{ $searchId }}"
                    name="status"
                    autocomplete="off"
                    aria-describedby="status-filter-help-{{ $searchId }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1E5A96] focus:border-transparent"
                >
                    <option value="">Tutti gli stati</option>
                    <option value="active">Attivo</option>
                    <option value="maintenance">In manutenzione</option>
                    <option value="coming-soon">Prossimamente</option>
                </select>
            </div>
            
            {{-- Authentication Filter --}}
            <div class="filter-group">
                <fieldset>
                    <legend class="block text-sm font-medium text-gray-700 mb-2">
                        Autenticazione richiesta
                    </legend>
                    <div class="space-y-2" role="radiogroup" aria-labelledby="auth-legend-{{ $searchId }}">
                        <label class="flex items-center">
                            <input type="radio" 
                                   id="auth-all-{{ $searchId }}"
                                   name="auth" 
                                   value="all" 
                                   checked 
                                   class="mr-2 focus:outline-none focus:ring-2 focus:ring-[#1E5A96]">
                            <span class="text-sm">Tutti</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" 
                                   id="auth-required-{{ $searchId }}"
                                   name="auth" 
                                   value="required" 
                                   class="mr-2 focus:outline-none focus:ring-2 focus:ring-[#1E5A96]">
                            <span class="text-sm">Con autenticazione</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" 
                                   id="auth-optional-{{ $searchId }}"
                                   name="auth" 
                                   value="optional" 
                                   class="mr-2 focus:outline-none focus:ring-2 focus:ring-[#1E5A96]">
                            <span class="text-sm">Senza autenticazione</span>
                        </label>
                    </div>
                </fieldset>
            </div>
            
            {{-- Availability Filter --}}
            <div class="filter-group">
                <label for="availability-filter-{{ $searchId }}" class="block text-sm font-medium text-gray-700 mb-2">
                    Disponibilità
                </label>
                <select 
                    id="availability-filter-{{ $searchId }}"
                    name="availability"
                    autocomplete="off"
                    aria-describedby="availability-filter-help-{{ $searchId }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1E5A96] focus:border-transparent"
                >
                    <option value="">Tutte le disponibilità</option>
                    <option value="24-7">24/7</option>
                    <option value="business-hours">Orari lavorativi</option>
                    <option value="appointment">Su appuntamento</option>
                </select>
            </div>
            
            {{-- Target Audience Filter --}}
            <div class="filter-group">
                <label for="audience-filter-{{ $searchId }}" class="block text-sm font-medium text-gray-700 mb-2">
                    Destinatari
                </label>
                <select 
                    id="audience-filter-{{ $searchId }}"
                    name="audience"
                    autocomplete="off"
                    aria-describedby="audience-filter-help-{{ $searchId }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1E5A96] focus:border-transparent"
                >
                    <option value="">Tutti</option>
                    <option value="citizens">Cittadini</option>
                    <option value="business">Imprese</option>
                    <option value="associations">Associazioni</option>
                    <option value="professionals">Professionisti</option>
                </select>
            </div>
            
            {{-- Priority Filter --}}
            <div class="filter-group">
                <label for="priority-filter-{{ $searchId }}" class="block text-sm font-medium text-gray-700 mb-2">
                    Priorità
                </label>
                <select 
                    id="priority-filter-{{ $searchId }}"
                    name="priority"
                    autocomplete="off"
                    aria-describedby="priority-filter-help-{{ $searchId }}"
                    class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1E5A96] focus:border-transparent"
                >
                    <option value="">Tutte le priorità</option>
                    <option value="high">Alta</option>
                    <option value="medium">Media</option>
                    <option value="low">Bassa</option>
                </select>
            </div>
        </div>
        
        {{-- Filter Actions --}}
        <div class="flex justify-between items-center mt-6">
            <button 
                type="button"
                id="reset-filters-{{ $searchId }}"
                aria-label="Ripristina tutti i filtri ai valori predefiniti"
                class="text-gray-600 hover:text-gray-800 text-sm focus:outline-none focus:ring-2 focus:ring-[#1E5A96] rounded-md px-2 py-1"
            >
                Resetta filtri
            </button>
            
            <button 
                type="submit"
                aria-label="Applica i filtri selezionati"
                class="bg-primary-600 text-white px-6 py-2 rounded-md hover:bg-primary-700 transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#1E5A96]"
            >
                Applica filtri
            </button>
        </div>
    </div>
    
    {{-- Search Suggestions --}}
    <div id="search-suggestions-{{ $searchId }}" class="search-suggestions hidden mt-2 bg-white border border-gray-200 rounded-lg shadow-lg">
        <div class="max-h-60 overflow-y-auto">
            <!-- Suggestions will be populated by JavaScript -->
        </div>
    </div>
    
    {{-- Search Results --}}
    <div id="search-results-{{ $searchId }}" class="search-results mt-6 hidden">
        <div class="results-header flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900">
                Risultati della ricerca
            </h3>
            <div class="results-count text-sm text-gray-600">
                <!-- Result count will be updated by JavaScript -->
            </div>
        </div>
        
        <div class="results-grid grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Results will be populated by JavaScript -->
        </div>
        
        <div class="results-pagination mt-6 flex justify-center">
            <!-- Pagination will be added by JavaScript -->
        </div>
    </div>
</div>

{{-- JavaScript for Search Functionality --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchId = '{{ $searchId }}';
    const searchInput = document.getElementById('search-input-' + searchId);
    const clearButton = document.getElementById('clear-search-' + searchId);
    const toggleFilters = document.getElementById('toggle-filters-' + searchId);
    const advancedFilters = document.getElementById('advanced-filters-' + searchId);
    const resetFilters = document.getElementById('reset-filters-' + searchId);
    const searchSuggestions = document.getElementById('search-suggestions-' + searchId);
    const searchResults = document.getElementById('search-results-' + searchId);
    
    let searchTimeout;
    let currentSearch = '';
    let currentFilters = {};
    
    // Search input handling
    searchInput.addEventListener('input', function(e) {
        const query = e.target.value.trim();
        
        // Show/hide clear button
        if (query) {
            clearButton.classList.remove('hidden');
        } else {
            clearButton.classList.add('hidden');
        }
        
        // Debounced search
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            if (query.length >= 2) {
                performSearch(query);
            } else {
                hideSuggestions();
                hideResults();
            }
        }, 300);
    });
    
    // Clear search
    clearButton.addEventListener('click', function() {
        searchInput.value = '';
        clearButton.classList.add('hidden');
        hideSuggestions();
        hideResults();
        searchInput.focus();
    });
    
    // Toggle advanced filters
    toggleFilters.addEventListener('click', function() {
        const isHidden = advancedFilters.classList.contains('hidden');
        if (isHidden) {
            advancedFilters.classList.remove('hidden');
            toggleFilters.setAttribute('aria-expanded', 'true');
            toggleFilters.querySelector('svg').classList.add('rotate-180');
        } else {
            advancedFilters.classList.add('hidden');
            toggleFilters.setAttribute('aria-expanded', 'false');
            toggleFilters.querySelector('svg').classList.remove('rotate-180');
        }
    });
    
    // Reset filters
    resetFilters.addEventListener('click', function() {
        const form = searchInput.closest('form');
        if (form) {
            form.reset();
        }
        currentFilters = {};
        hideResults();
    });
    
    // Category chip clicks
    document.querySelectorAll('.category-chip').forEach(chip => {
        chip.addEventListener('click', function() {
            const category = this.dataset.category;
            searchInput.value = this.textContent.trim();
            performSearch(searchInput.value, { category: category });
        });
    });
    
    // Perform search function
    function performSearch(query, filters = {}) {
        currentSearch = query;
        currentFilters = { ...currentFilters, ...filters };
        
        // Show loading state
        showSuggestions([
            { type: 'loading', text: 'Ricerca in corso...' }
        ]);
        
        // Simulate API call (replace with actual implementation)
        setTimeout(() => {
            const results = mockSearchResults(query, currentFilters);
            if (results.length > 0) {
                showSuggestions(results.slice(0, 3));
                showResults(results);
            } else {
                showSuggestions([
                    { type: 'no-results', text: 'Nessun risultato trovato' }
                ]);
                hideResults();
            }
        }, 500);
    }
    
    // Mock search results (replace with actual API call)
    function mockSearchResults(query, filters) {
        const allServices = [
            { id: 1, title: 'Certificati Anagrafici', category: 'Anagrafe', description: 'Richiedi certificati online' },
            { id: 2, title: 'Pagamento IMU', category: 'Tributi', description: 'Paga l\'IMU online' },
            { id: 3, title: 'SUAP Online', category: 'Urbanistica', description: 'Sportello Unico Attività Produttive' },
            { id: 4, title: 'Asili Nido', category: 'Servizi Sociali', description: 'Iscrizione asili nido' },
            { id: 5, title: 'Raccolta Differenziata', category: 'Ambiente', description: 'Calendario raccolta rifiuti' }
        ];
        
        return allServices.filter(service => 
            service.title.toLowerCase().includes(query.toLowerCase()) ||
            service.description.toLowerCase().includes(query.toLowerCase())
        );
    }
    
    function showSuggestions(suggestions) {
        searchSuggestions.classList.remove('hidden');
        const container = searchSuggestions.querySelector('div');
        container.innerHTML = suggestions.map(suggestion => {
            if (suggestion.type === 'loading') {
                return `
                    <div class="p-3 text-center text-gray-600">
                        <div class="inline-block animate-spin rounded-full h-4 w-4 border-b-2 border-primary-600"></div>
                        <span class="ml-2">${suggestion.text}</span>
                    </div>
                `;
            } else if (suggestion.type === 'no-results') {
                return `
                    <div class="p-3 text-center text-gray-600">
                        ${suggestion.text}
                    </div>
                `;
            } else {
                return `
                    <div class="suggestion-item p-3 hover:bg-gray-50 cursor-pointer border-b last:border-b-0" data-service-id="${suggestion.id}">
                        <div class="font-medium text-gray-900">${suggestion.title}</div>
                        <div class="text-sm text-gray-600">${suggestion.description}</div>
                        <div class="text-xs text-gray-500 mt-1">${suggestion.category}</div>
                    </div>
                `;
            }
        }).join('');
        
        // Add click handlers to suggestions
        container.querySelectorAll('.suggestion-item').forEach(item => {
            item.addEventListener('click', function() {
                const serviceId = this.dataset.serviceId;
                // Navigate to service detail page
                window.location.href = `/servizi/${serviceId}`;
            });
        });
    }
    
    function hideSuggestions() {
        searchSuggestions.classList.add('hidden');
    }
    
    function showResults(results) {
        searchResults.classList.remove('hidden');
        const resultsCount = searchResults.querySelector('.results-count');
        const resultsGrid = searchResults.querySelector('.results-grid');
        
        resultsCount.textContent = `${results.length} risultati trovati`;
        
        resultsGrid.innerHTML = results.map(result => `
            <div class="service-card bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                <div class="service-header mb-3">
                    <span class="inline-block px-2 py-1 text-xs bg-primary-100 text-primary-700 rounded-full">
                        ${result.category}
                    </span>
                </div>
                <h4 class="font-semibold text-gray-900 mb-2">${result.title}</h4>
                <p class="text-sm text-gray-600 mb-3">${result.description}</p>
                <a href="/servizi/${result.id}" class="inline-flex items-center text-primary-600 hover:text-primary-700 text-sm font-medium">
                    Accedi al servizio
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>
        `).join('');
    }
    
    function hideResults() {
        searchResults.classList.add('hidden');
    }
    
    // Hide suggestions when clicking outside
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.services-search')) {
            hideSuggestions();
        }
    });
    
    // Keyboard navigation
    searchInput.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideSuggestions();
            hideResults();
        }
    });
});
</script>

{{-- CSS for Search Component --}}
<style>
.services-search {
    max-width: 100%;
}

.search-input-wrapper input:focus {
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.category-chip:focus {
    outline: 2px solid #3b82f6;
    outline-offset: 2px;
}

.suggestion-item:hover {
    background-color: #f9fafb;
}

.suggestion-item:focus {
    outline: 2px solid #3b82f6;
    outline-offset: -2px;
}

/* Mobile optimizations */
@media (max-width: 768px) {
    .services-search .grid-cols-2 {
        grid-template-columns: 1fr;
    }
    
    .services-search .grid-cols-3 {
        grid-template-columns: 1fr;
    }
    
    .search-input-wrapper input {
        font-size: 16px; /* Prevents zoom on iOS */
    }
    
    .advanced-filters .grid-cols-3 {
        grid-template-columns: 1fr;
    }
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .services-search input,
    .services-search select,
    .services-search button {
        border-width: 2px;
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    .services-search * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
</style>