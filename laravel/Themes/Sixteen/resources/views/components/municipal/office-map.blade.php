{{--
/**
 * Mappa Uffici e Contatti Component - Bootstrap Italia Compliant
 * 
 * Componente per la visualizzazione di mappa interattiva con uffici comunali
 * Integrazione con OpenStreetMap/Google Maps e gestione contatti
 * 
 * @param array $offices Array di uffici con coordinate e informazioni
 * @param string $mapProvider Provider mappa ('openstreetmap', 'googlemaps')
 * @param array $mapOptions Opzioni configurazione mappa
 * @param string $defaultView Vista predefinita ('map', 'list')
 * @param bool $showFilters Mostra filtri per categorie
 * @param array $categories Categorie uffici disponibili
 */
--}}

@props([
    'offices' => [],
    'mapProvider' => 'openstreetmap',
    'mapOptions' => [
        'zoom' => 13,
        'center' => [41.9028, 12.4964], // Roma centro
        'markerColor' => '#0066CC'
    ],
    'defaultView' => 'map',
    'showFilters' => true,
    'categories' => ['Tutti', 'Anagrafe', 'Tributi', 'Urbanistica', 'Lavori Pubblici', 'Polizia Locale', 'Sociale', 'Cultura', 'Sport', 'Altro']
])

<div class="office-map-container bg-white rounded-lg shadow-md overflow-hidden"
     x-data="{
        offices: {{ json_encode($offices) }},
        filteredOffices: {{ json_encode($offices) }},
        selectedCategory: 'Tutti',
        selectedOffice: null,
        currentView: '{{ $defaultView }}',
        map: null,
        markers: [],
        
        init() {
            this.filterOffices();
            
            if (this.currentView === 'map' && typeof L !== 'undefined') {
                this.$nextTick(() => {
                    this.initMap();
                });
            }
            
            // Gestione resize
            this.handleResize();
            window.addEventListener('resize', this.handleResize.bind(this));
        },
        
        handleResize() {
            if (this.map && window.innerWidth < 768) {
                this.map.invalidateSize();
            }
        },
        
        filterOffices() {
            this.filteredOffices = this.offices.filter(office => {
                return this.selectedCategory === 'Tutti' || 
                       office.category === this.selectedCategory;
            });
            
            if (this.currentView === 'map' && this.map) {
                this.updateMapMarkers();
            }
        },
        
        initMap() {
            const mapElement = this.$refs.map;
            if (!mapElement || typeof L === 'undefined') return;
            
            // Inizializza mappa
            this.map = L.map(mapElement).setView(
                {{ json_encode($mapOptions['center']) }},
                {{ $mapOptions['zoom'] }}
            );
            
            // Aggiungi layer OpenStreetMap
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
                maxZoom: 19
            }).addTo(this.map);
            
            this.updateMapMarkers();
        },
        
        updateMapMarkers() {
            // Rimuovi markers esistenti
            this.markers.forEach(marker => this.map.removeLayer(marker));
            this.markers = [];
            
            // Aggiungi nuovi markers
            this.filteredOffices.forEach(office => {
                if (office.latitude && office.longitude) {
                    const marker = L.marker([office.latitude, office.longitude])
                        .addTo(this.map)
                        .bindPopup(`
                            <div class="p-2">
                                <h4 class="font-semibold">${office.name}</h4>
                                ${office.address ? `<p class="text-sm">${office.address}</p>` : ''}
                                ${office.phone ? `<p class="text-sm">Tel: ${office.phone}</p>` : ''}
                                ${office.email ? `<p class="text-sm">Email: ${office.email}</p>` : ''}
                            </div>
                        `);
                    
                    this.markers.push(marker);
                }
            });
            
            // Aggiusta bounds se ci sono markers
            if (this.markers.length > 0) {
                const group = new L.featureGroup(this.markers);
                this.map.fitBounds(group.getBounds().pad(0.1));
            }
        },
        
        switchView(view) {
            this.currentView = view;
            
            if (view === 'map' && !this.map) {
                this.$nextTick(() => {
                    this.initMap();
                });
            }
        },
        
        selectOffice(office) {
            this.selectedOffice = office;
            
            if (this.currentView === 'map' && office.latitude && office.longitude) {
                this.map.setView([office.latitude, office.longitude], 16);
                
                // Trova e apri il marker corrispondente
                const officeMarker = this.markers.find(marker => 
                    marker.getLatLng().lat === office.latitude && 
                    marker.getLatLng().lng === office.longitude
                );
                
                if (officeMarker) {
                    officeMarker.openPopup();
                }
            }
        },
        
        getDirections(office) {
            if (!office.address) return;
            
            const encodedAddress = encodeURIComponent(office.address);
            window.open(`https://www.google.com/maps/dir/?api=1&destination=${encodedAddress}`, '_blank');
        },
        
        formatPhone(phone) {
            return phone.replace(/(\d{2})(\d{4})(\d{4})/, '$1 $2 $3');
        },
        
        getOfficeHours(hours) {
            if (!hours) return 'Orario non disponibile';
            if (typeof hours === 'string') return hours;
            return hours.join(', ');
        }
     }">

    {{-- Header --}}
    <div class="bg-primary-800 text-white px-6 py-4">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-xl font-bold">Uffici e Contatti</h2>
                <p class="text-primary-100 text-sm mt-1">
                    Trova gli uffici comunali e i relativi contatti
                </p>
            </div>
            
            <div class="flex items-center space-x-2">
                <span class="text-primary-200 text-sm">
                    <span x-text="filteredOffices.length"></span> uffici
                </span>
            </div>
        </div>
    </div>

    {{-- Filtri e controlli vista --}}
    <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            {{-- Filtro categoria --}}
            @if($showFilters)
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Categoria</label>
                <select x-model="selectedCategory" @change="filterOffices"
                        class="w-full md:w-auto px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @foreach($categories as $category)
                    <option value="{{ $category }}">{{ $category }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            {{-- Toggle vista --}}
            <div class="flex space-x-1">
                <button @click="switchView('list')"
                        :class="{ 
                            'bg-blue-600 text-white': currentView === 'list',
                            'bg-white text-gray-700 hover:bg-gray-100': currentView !== 'list'
                        }"
                        class="px-4 py-2 border border-gray-300 rounded-l-md text-sm font-medium transition-colors">
                    Lista
                </button>
                <button @click="switchView('map')"
                        :class="{ 
                            'bg-blue-600 text-white': currentView === 'map',
                            'bg-white text-gray-700 hover:bg-gray-100': currentView !== 'map'
                        }"
                        class="px-4 py-2 border border-gray-300 rounded-r-md text-sm font-medium transition-colors">
                    Mappa
                </button>
            </div>
        </div>
    </div>

    {{-- Contenuto principale --}}
    <div class="flex-1 min-h-[500px]">
        {{-- Vista Mappa --}}
        <template x-if="currentView === 'map'">
            <div class="h-full">
                <div x-ref="map" class="w-full h-[500px] rounded-b-lg"></div>
                
                {{-- Fallback per mappa non caricata --}}
                <div x-show="!map" class="p-8 text-center text-gray-500">
                    <svg class="w-16 h-16 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                    </svg>
                    <p>Caricamento mappa in corso...</p>
                </div>
            </div>
        </template>

        {{-- Vista Lista --}}
        <template x-if="currentView === 'list'">
            <div class="divide-y divide-gray-200 max-h-[500px] overflow-y-auto">
                <template x-if="filteredOffices.length === 0">
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <p class="text-gray-500">Nessun ufficio trovato</p>
                    </div>
                </template>

                <template x-for="office in filteredOffices" :key="office.id">
                    <div class="p-6 hover:bg-gray-50 transition-colors cursor-pointer"
                         @click="selectOffice(office)"
                         :class="{ 'bg-blue-50': selectedOffice && selectedOffice.id === office.id }">
                        
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                {{-- Nome e categoria --}}
                                <div class="flex items-center gap-3 mb-2">
                                    <h3 class="text-lg font-semibold text-gray-900" x-text="office.name"></h3>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                                          x-text="office.category || 'Generale'">
                                    </span>
                                </div>

                                {{-- Indirizzo --}}
                                <template x-if="office.address">
                                    <div class="flex items-center text-gray-600 mb-2">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                        </svg>
                                        <span x-text="office.address"></span>
                                    </div>
                                </template>

                                {{-- Contatti --}}
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                                    <template x-if="office.phone">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                            </svg>
                                            <a :href="'tel:' + office.phone" 
                                               class="hover:text-blue-600"
                                               x-text="formatPhone(office.phone)">
                                            </a>
                                        </div>
                                    </template>

                                    <template x-if="office.email">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                            <a :href="'mailto:' + office.email" 
                                               class="hover:text-blue-600"
                                               x-text="office.email">
                                            </a>
                                        </div>
                                    </template>

                                    <template x-if="office.hours">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                            </svg>
                                            <span x-text="getOfficeHours(office.hours)"></span>
                                        </div>
                                    </template>

                                    <template x-if="office.website">
                                        <div class="flex items-center">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                                            </svg>
                                            <a :href="office.website" 
                                               target="_blank"
                                               class="hover:text-blue-600"
                                               x-text="'Sito web'">
                                            </a>
                                        </div>
                                    </template>
                                </div>
                            </div>

                            {{-- Azioni --}}
                            <div class="flex flex-col space-y-2 ml-4">
                                <template x-if="office.address">
                                    <button @click.stop="getDirections(office)"
                                            class="btn-italia bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 text-sm">
                                        Indicazioni
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </template>
    </div>
</div>

{{-- Include Leaflet CSS e JS --}}
@pushOnce('styles')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
      crossorigin="" />
<style>
.office-map-container {
    min-height: 600px;
}

.leaflet-container {
    height: 500px;
    width: 100%;
    border-radius: 0 0 0.5rem 0.5rem;
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

@media (max-width: 768px) {
    .office-map-container {
        margin: 0 -1rem;
        border-radius: 0;
        border: none;
    }
    
    .leaflet-container {
        border-radius: 0;
        height: 400px;
    }
}
</style>
@endPushOnce

@pushOnce('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""></script>
@endPushOnce

{{--
Usage Examples:

1. Mappa base:
<x-pub_theme::municipal.office-map 
    :offices="$offices" />

2. Con uffici personalizzati:
<x-pub_theme::municipal.office-map 
    :offices="[
        {
            'id': 1,
            'name': 'Ufficio Anagrafe',
            'category': 'Anagrafe',
            'address': 'Piazza del Comune, 1',
            'phone': '0123456789',
            'email': 'anagrafe@comune.example.it',
            'hours': 'Lun-Ven: 8:30-12:30',
            'latitude': 41.9028,
            'longitude': 12.4964
        }
    ]"
    :map-options="['zoom' => 14, 'center' => [41.9028, 12.4964]]" />

3. Vista predefinita lista:
<x-pub_theme::municipal.office-map 
    default-view="list"
    :offices="$offices" />
--}}