{{--
/**
 * Enhanced Services Grid Component
 * 
 * Advanced grid layout with filtering, sorting, and pagination
 * Fully responsive and accessible
 * 
 * @param array $services Array of service objects
 * @param array $categories Available categories for filtering
 * @param string $view Layout view: 'grid', 'list', or 'cards'
 * @param int $columns Number of columns (1-4)
 * @param bool $showFilters Whether to show filter controls
 * @param bool $showSearch Whether to show search bar
 * @param bool $showPagination Whether to show pagination
 * @param int $perPage Items per page
 * @param string $class Additional CSS classes
 */
--}}

@props([
    'services' => [],
    'categories' => [],
    'view' => 'grid',
    'columns' => 3,
    'showFilters' => true,
    'showSearch' => true,
    'showPagination' => true,
    'perPage' => 12,
    'class' => ''
])

@php
    $gridId = 'services-grid-' . uniqid();
    $currentView = $view;
    $currentColumns = min(max($columns, 1), 4);
    $currentPage = 1;
    $totalPages = ceil(count($services) / $perPage);
    $filteredServices = $services;
@endphp

<div class="services-grid-enhanced {{ $class }}" id="{{ $gridId }}">
    {{-- Header with Controls --}}
    @if($showSearch || $showFilters)
    <div class="services-grid-header mb-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-end">
            {{-- Search --}}
            @if($showSearch)
            <div class="lg:col-span-2">
                <div class="relative">
                    <label for="search-{{ $gridId }}" class="sr-only">Cerca servizi</label>
                    <input 
                        type="text" 
                        id="search-{{ $gridId }}"
                        name="search"
                        placeholder="Cerca servizi..."
                        autocomplete="off"
                        aria-label="Cerca servizi per nome o descrizione"
                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-[#1E5A96] focus:border-transparent"
                    />
                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
            </div>
            @endif
            
            {{-- View Controls --}}
            <div class="flex gap-2">
                {{-- Category Filter --}}
                @if($showFilters && $categories)
                <label for="category-filter-{{ $gridId }}" class="sr-only">Filtra per categoria</label>
                <select id="category-filter-{{ $gridId }}" 
                        name="category"
                        autocomplete="off"
                        aria-label="Filtra servizi per categoria"
                        class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1E5A96] focus:border-transparent">
                    <option value="">Tutte le categorie</option>
                    @foreach($categories as $category)
                    <option value="{{ $category['id'] ?? $category }}">{{ $category['name'] ?? $category }}</option>
                    @endforeach
                </select>
                @endif
                
                {{-- Sort --}}
                <label for="sort-{{ $gridId }}" class="sr-only">Ordina servizi</label>
                <select id="sort-{{ $gridId }}" 
                        name="sort"
                        autocomplete="off"
                        aria-label="Ordina servizi"
                        class="px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-[#1E5A96] focus:border-transparent">
                    <option value="name">Nome (A-Z)</option>
                    <option value="name-desc">Nome (Z-A)</option>
                    <option value="category">Categoria</option>
                    <option value="popular">Più popolari</option>
                    <option value="recent">Recenti</option>
                </select>
                
                {{-- View Toggle --}}
                <div class="inline-flex rounded-md border border-gray-300" role="group">
                    <button 
                        type="button" 
                        onclick="changeView('{{ $gridId }}', 'grid')"
                        aria-label="Mostra servizi in vista griglia"
                        class="px-3 py-2 text-sm font-medium rounded-l-md {{ $currentView === 'grid' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }} border-r border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#1E5A96]"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                    </button>
                    <button 
                        type="button" 
                        onclick="changeView('{{ $gridId }}', 'list')"
                        aria-label="Mostra servizi in vista elenco"
                        class="px-3 py-2 text-sm font-medium -ml-px {{ $currentView === 'list' ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50' }} border-r border-gray-300 focus:outline-none focus:ring-2 focus:ring-[#1E5A96]"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        {{-- Active Filters --}}
        <div id="active-filters-{{ $gridId }}" class="mt-4 hidden">
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600">Filtri attivi:</span>
                <div class="flex flex-wrap gap-2">
                    <!-- Active filter chips will be added here -->
                </div>
                <button onclick="clearAllFilters('{{ $gridId }}')" 
                        aria-label="Rimuovi tutti i filtri attivi"
                        class="text-sm text-primary-600 hover:text-primary-700 focus:outline-none focus:ring-2 focus:ring-[#1E5A96] rounded-md px-1">
                    Rimuovi tutti
                </button>
            </div>
        </div>
    </div>
    @endif
    
    {{-- Results Summary --}}
    <div class="results-summary mb-6 flex justify-between items-center">
        <div class="results-count">
            <span class="text-sm text-gray-600">
                Mostrando <span id="showing-count-{{ $gridId }}">{{ count($filteredServices) }}</span> 
                di <span id="total-count-{{ $gridId }}">{{ count($services) }}</span> servizi
            </span>
        </div>
        
        @if($showPagination && $totalPages > 1)
        <div class="pagination-info">
            <span class="text-sm text-gray-600">
                Pagina <span id="current-page-{{ $gridId }}">{{ $currentPage }}</span> di <span>{{ $totalPages }}</span>
            </span>
        </div>
        @endif
    </div>
    
    {{-- Services Grid/List --}}
    <div id="services-container-{{ $gridId }}" class="services-container">
        <div id="services-grid-{{ $gridId }}" class="grid {{ $currentView === 'grid' ? 'grid-cols-1 md:grid-cols-2 lg:grid-cols-' . $currentColumns : '' }} gap-6">
            @foreach($services as $service)
            <div class="service-item" data-category="{{ $service['category'] ?? '' }}" data-status="{{ $service['status'] ?? 'active' }}">
                @if($currentView === 'grid')
                    <x-ui.service-card
                        title="{{ $service['title'] }}"
                        description="{{ $service['description'] }}"
                        icon="{{ $service['icon'] ?? 'heroicon-o-cog' }}"
                        url="{{ $service['url'] ?? '#' }}"
                        category="{{ $service['category'] }}"
                        status="{{ $service['status'] ?? 'active' }}"
                        featured="{{ $service['featured'] ?? false }}"
                    />
                @else
                    <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <span class="inline-block px-2 py-1 bg-primary-100 text-primary-700 text-xs font-medium rounded-full">
                                        {{ $service['category'] }}
                                    </span>
                                    @if($service['featured'] ?? false)
                                    <span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">
                                        In evidenza
                                    </span>
                                    @endif
                                </div>
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $service['title'] }}</h3>
                                <p class="text-gray-600 mb-4">{{ $service['description'] }}</p>
                                
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-4 text-sm text-gray-500">
                                        @if($service['processing_time'])
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                            </svg>
                                            {{ $service['processing_time'] }}
                                        </span>
                                        @endif
                                        
                                        @if($service['requires_auth'])
                                        <span class="flex items-center">
                                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                            </svg>
                                            Autenticazione richiesta
                                        </span>
                                        @endif
                                    </div>
                                    
                                    <a href="{{ $service['url'] ?? '#' }}" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium">
                                        Accedi
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            @endforeach
        </div>
        
        {{-- No Results --}}
        <div id="no-results-{{ $gridId }}" class="hidden text-center py-12">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Nessun servizio trovato</h3>
            <p class="text-gray-600 mb-4">Prova a modificare i filtri o la ricerca</p>
            <button onclick="clearAllFilters('{{ $gridId }}')" 
                    aria-label="Rimuovi tutti i filtri e mostra tutti i servizi"
                    class="text-primary-600 hover:text-primary-700 font-medium focus:outline-none focus:ring-2 focus:ring-[#1E5A96] rounded-md px-2 py-1">
                Rimuovi filtri
            </button>
        </div>
    </div>
    
    {{-- Pagination --}}
    @if($showPagination && $totalPages > 1)
    <div id="pagination-{{ $gridId }}" class="pagination mt-8 flex justify-center">
        <nav class="flex items-center gap-2" role="navigation" aria-label="Paginazione">
            {{-- Previous Button --}}
            <button 
                onclick="changePage('{{ $gridId }}', {{ $currentPage - 1 }})"
                aria-label="Vai alla pagina precedente"
                class="px-3 py-2 text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-[#1E5A96] {{ $currentPage === 1 ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300' }}"
                {{ $currentPage === 1 ? 'disabled aria-disabled="true"' : '' }}
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            
            {{-- Page Numbers --}}
            @for($i = 1; $i <= $totalPages; $i++)
                @if($i === 1 || $i === $totalPages || ($i >= $currentPage - 2 && $i <= $currentPage + 2))
                    <button 
                        onclick="changePage('{{ $gridId }}', {{ $i }})"
                        aria-label="Vai alla pagina {{ $i }}"
                        @if($i === $currentPage) aria-current="page" @endif
                        class="px-3 py-2 text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-[#1E5A96] {{ $i === $currentPage ? 'bg-primary-600 text-white' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300' }}"
                    >
                        {{ $i }}
                    </button>
                @elseif($i === $currentPage - 3 || $i === $currentPage + 3)
                    <span class="px-2 text-gray-500">...</span>
                @endif
            @endfor
            
            {{-- Next Button --}}
            <button 
                onclick="changePage('{{ $gridId }}', {{ $currentPage + 1 }})"
                aria-label="Vai alla pagina successiva"
                class="px-3 py-2 text-sm font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-[#1E5A96] {{ $currentPage === $totalPages ? 'bg-gray-100 text-gray-400 cursor-not-allowed' : 'bg-white text-gray-700 hover:bg-gray-50 border border-gray-300' }}"
                {{ $currentPage === $totalPages ? 'disabled aria-disabled="true"' : '' }}
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </nav>
    </div>
    @endif
</div>

{{-- JavaScript for Grid Functionality --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const gridId = '{{ $gridId }}';
    const searchInput = document.getElementById('search-' + gridId);
    const categoryFilter = document.getElementById('category-filter-' + gridId);
    const sortSelect = document.getElementById('sort-' + gridId);
    const servicesContainer = document.getElementById('services-container-' + gridId);
    const servicesGrid = document.getElementById('services-grid-' + gridId);
    const noResults = document.getElementById('no-results-' + gridId);
    const showingCount = document.getElementById('showing-count-' + gridId);
    const totalCount = document.getElementById('total-count-' + gridId);
    const currentPageSpan = document.getElementById('current-page-' + gridId);
    
    let services = [];
    let filteredServices = [];
    let currentPage = 1;
    let currentSort = 'name';
    let currentCategory = '';
    let currentSearch = '';
    
    // Initialize services data
    @php
    $servicesJson = json_encode($services);
    ?>
    services = {!! $servicesJson !!};
    filteredServices = [...services];
    
    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            currentSearch = this.value.toLowerCase();
            applyFilters();
        });
    }
    
    // Category filter
    if (categoryFilter) {
        categoryFilter.addEventListener('change', function() {
            currentCategory = this.value;
            applyFilters();
        });
    }
    
    // Sort functionality
    if (sortSelect) {
        sortSelect.addEventListener('change', function() {
            currentSort = this.value;
            applyFilters();
        });
    }
    
    // Apply filters and sorting
    function applyFilters() {
        filteredServices = services.filter(service => {
            // Search filter
            if (currentSearch) {
                const searchMatch = 
                    (service.title || '').toLowerCase().includes(currentSearch) ||
                    (service.description || '').toLowerCase().includes(currentSearch) ||
                    (service.category || '').toLowerCase().includes(currentSearch);
                if (!searchMatch) return false;
            }
            
            // Category filter
            if (currentCategory) {
                if ((service.category || '').toLowerCase() !== currentCategory.toLowerCase()) {
                    return false;
                }
            }
            
            return true;
        });
        
        // Apply sorting
        sortServices();
        
        // Reset to first page
        currentPage = 1;
        
        // Render results
        renderServices();
    }
    
    // Sort services
    function sortServices() {
        filteredServices.sort((a, b) => {
            switch (currentSort) {
                case 'name':
                    return (a.title || '').localeCompare(b.title || '');
                case 'name-desc':
                    return (b.title || '').localeCompare(a.title || '');
                case 'category':
                    return (a.category || '').localeCompare(b.category || '');
                case 'popular':
                    return (b.popularity || 0) - (a.popularity || 0);
                case 'recent':
                    return new Date(b.created_at || 0) - new Date(a.created_at || 0);
                default:
                    return 0;
            }
        });
    }
    
    // Render services
    function renderServices() {
        const perPage = {{ $perPage }};
        const startIndex = (currentPage - 1) * perPage;
        const endIndex = startIndex + perPage;
        const servicesToShow = filteredServices.slice(startIndex, endIndex);
        
        if (servicesToShow.length === 0) {
            servicesGrid.style.display = 'none';
            noResults.style.display = 'block';
        } else {
            servicesGrid.style.display = '';
            noResults.style.display = 'none';
            
            // Re-render service items
            const container = servicesGrid;
            const existingItems = container.querySelectorAll('.service-item');
            
            existingItems.forEach(item => item.remove());
            
            servicesToShow.forEach(service => {
                const serviceElement = createServiceElement(service);
                container.appendChild(serviceElement);
            });
        }
        
        // Update counts
        if (showingCount) showingCount.textContent = filteredServices.length;
        if (totalCount) totalCount.textContent = services.length;
        if (currentPageSpan) currentPageSpan.textContent = currentPage;
        
        // Update pagination
        updatePagination();
    }
    
    // Create service element
    function createServiceElement(service) {
        const div = document.createElement('div');
        div.className = 'service-item';
        div.dataset.category = service.category || '';
        div.dataset.status = service.status || 'active';
        
        // This would normally use the service-card component
        div.innerHTML = `
            <div class="bg-white border border-gray-200 rounded-lg p-6 hover:shadow-md transition-shadow">
                <div class="flex items-start gap-4">
                    <div class="w-12 h-12 bg-primary-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="inline-block px-2 py-1 bg-primary-100 text-primary-700 text-xs font-medium rounded-full">
                                ${service.category || 'Servizio'}
                            </span>
                            ${service.featured ? '<span class="inline-block px-2 py-1 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-full">In evidenza</span>' : ''}
                        </div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-2">${service.title}</h3>
                        <p class="text-gray-600 mb-4">${service.description}</p>
                        <a href="${service.url || '#'}" class="inline-flex items-center text-primary-600 hover:text-primary-700 font-medium">
                            Accedi al servizio
                            <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        `;
        
        return div;
    }
    
    // Update pagination
    function updatePagination() {
        const totalPages = Math.ceil(filteredServices.length / {{ $perPage }});
        const pagination = document.getElementById('pagination-' + gridId);
        
        if (pagination && totalPages > 1) {
            pagination.style.display = 'flex';
            // Rebuild pagination buttons
            const nav = pagination.querySelector('nav');
            // Implementation would rebuild pagination buttons
        } else if (pagination) {
            pagination.style.display = 'none';
        }
    }
    
    // Initial render
    renderServices();
});

// Global functions for inline event handlers
function changeView(gridId, view) {
    // This would update the view mode
    console.log('Changing view to:', view);
}

function changePage(gridId, page) {
    // This would handle pagination
    console.log('Changing to page:', page);
}

function clearAllFilters(gridId) {
    const searchInput = document.getElementById('search-' + gridId);
    const categoryFilter = document.getElementById('category-filter-' + gridId);
    
    if (searchInput) searchInput.value = '';
    if (categoryFilter) categoryFilter.value = '';
    
    // Trigger filter reset
    const event = new Event('input');
    if (searchInput) searchInput.dispatchEvent(event);
}
</script>

<style>
.services-grid-enhanced .grid {
    transition: all 0.3s ease;
}

.services-grid-enhanced .service-item {
    opacity: 0;
    transform: translateY(20px);
    animation: fadeInUp 0.5s ease forwards;
}

.services-grid-enhanced .service-item:nth-child(1) { animation-delay: 0.1s; }
.services-grid-enhanced .service-item:nth-child(2) { animation-delay: 0.2s; }
.services-grid-enhanced .service-item:nth-child(3) { animation-delay: 0.3s; }
.services-grid-enhanced .service-item:nth-child(4) { animation-delay: 0.4s; }
.services-grid-enhanced .service-item:nth-child(5) { animation-delay: 0.5s; }
.services-grid-enhanced .service-item:nth-child(6) { animation-delay: 0.6s; }

@keyframes fadeInUp {
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* Mobile optimizations */
@media (max-width: 768px) {
    .services-grid-enhanced .lg\:grid-cols-3 {
        grid-template-columns: 1fr;
    }
    
    .services-grid-enhanced .md\:grid-cols-2 {
        grid-template-columns: 1fr;
    }
    
    .services-grid-header .grid-cols-3 {
        grid-template-columns: 1fr;
        gap: 1rem;
    }
    
    .services-grid-header .flex {
        flex-direction: column;
        gap: 1rem;
    }
}

/* Print styles */
@media print {
    .services-grid-header,
    .pagination,
    .results-summary {
        display: none !important;
    }
    
    .services-grid-enhanced .service-item {
        break-inside: avoid;
        margin-bottom: 1rem;
    }
}
</style>