# 🗺️ Implementazione Mappa Interattiva - Piano Esecutivo

**Priorità**: 🔴 P0 - CRITICO  
**Effort**: 40 ore  
**Deadline**: Entro 3 giorni

---

## 🎯 Obiettivo

Implementare mappa interattiva OpenStreetMap con Leaflet per:
1. Selezione luogo segnalazione (create ticket)
2. Visualizzazione segnalazioni esistenti
3. Clustering per performance
4. Autocomplete indirizzi con Nominatim

---

## 📦 Stack Tecnologico

```json
{
  "leaflet": "^1.9.4",
  "leaflet.markercluster": "^1.5.3",
  "leaflet-geosearch": "^3.11.1"
}
```

---

## 🏗️ Architettura Componenti

### 1. Livewire Component: TicketMap

```
Modules/Fixcity/app/Livewire/TicketMap.php
Modules/Fixcity/resources/views/livewire/ticket-map.blade.php
```

**Props**:
- `mode`: 'create'|'view'|'list'
- `ticket`: Ticket model (per view mode)
- `filters`: array (categoria, status)

**Events**:
- `location-selected`: Emit coordinate selezionate
- `ticket-clicked`: Click su marker

### 2. JavaScript Module

```
Themes/Sixteen/resources/js/modules/leaflet-map.js
```

**Responsabilità**:
- Inizializzazione Leaflet
- Gestione markers
- Clustering
- Geocoding autocomplete
- Mobile touch support

---

## 📝 Implementazione Step-by-Step

### STEP 1: Installazione Dipendenze (1h)

```bash
cd Themes/Sixteen
npm install leaflet leaflet.markercluster leaflet-geosearch --save
```

### STEP 2: Livewire Component (4h)

File: `Modules/Fixcity/app/Livewire/TicketMap.php`

```php
<?php

namespace Modules\Fixcity\Livewire;

use Illuminate\View\View;
use Livewire\Attributes\On;
use Livewire\Component;
use Modules\Fixcity\Models\Ticket;

class TicketMap extends Component
{
    public string $mode = 'create'; // create|view|list
    public ?Ticket $ticket = null;
    public array $filters = [];
    
    // Selected coordinates
    public ?float $latitude = null;
    public ?float $longitude = null;
    public ?string $address = null;
    
    // Map center (default: Italy)
    public float $centerLat = 41.9028;
    public float $centerLng = 12.4964;
    public int $zoom = 6;
    
    public function mount(): void
    {
        if ($this->mode === 'view' && $this->ticket) {
            $this->latitude = $this->ticket->latitude;
            $this->longitude = $this->ticket->longitude;
            $this->address = $this->ticket->address;
            $this->centerLat = $this->latitude ?? $this->centerLat;
            $this->centerLng = $this->longitude ?? $this->centerLng;
            $this->zoom = 15;
        }
    }
    
    #[On('map-location-selected')]
    public function updateLocation(float $lat, float $lng, ?string $address = null): void
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
        $this->address = $address;
        
        $this->dispatch('location-updated', [
            'lat' => $lat,
            'lng' => $lng,
            'address' => $address,
        ]);
    }
    
    public function getTicketsProperty()
    {
        return Ticket::query()
            ->select(['id', 'name', 'latitude', 'longitude', 'status', 'priority'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->when($this->filters, function ($query) {
                // Apply filters
            })
            ->get();
    }
    
    public function render(): View
    {
        return view('fixcity::livewire.ticket-map');
    }
}
```

### STEP 3: Blade Template (3h)

File: `Modules/Fixcity/resources/views/livewire/ticket-map.blade.php`

```blade
<div
    class="ticket-map-container"
    wire:ignore
    x-data="ticketMap({
        mode: '{{ $mode }}',
        centerLat: {{ $centerLat }},
        centerLng: {{ $centerLng }},
        zoom: {{ $zoom }},
        @if($mode === 'create')
        selectedLat: @entangle('latitude'),
        selectedLng: @entangle('longitude'),
        selectedAddress: @entangle('address'),
        @endif
        @if($mode === 'list')
        tickets: {{ $this->tickets->toJson() }},
        @endif
        @if($mode === 'view' && $ticket)
        markerLat: {{ $latitude }},
        markerLng: {{ $longitude }},
        markerTitle: '{{ $ticket->name }}',
        @endif
    })"
    x-init="initMap()"
>
    <div id="map" class="leaflet-map" style="height: 500px; width: 100%;"></div>
    
    @if($mode === 'create')
    <div class="map-search-box mt-3">
        <div class="form-group">
            <label for="map-search">Cerca un indirizzo</label>
            <input 
                type="text" 
                id="map-search" 
                class="form-control"
                placeholder="Via, piazza, località..."
                autocomplete="off"
            >
            <div id="search-results" class="search-results-dropdown"></div>
        </div>
        
        @if($latitude && $longitude)
        <div class="alert alert-success mt-3">
            <strong>Posizione selezionata:</strong><br>
            {{ $address ?? "Lat: {$latitude}, Lng: {$longitude}" }}
        </div>
        @endif
    </div>
    @endif
</div>
```

### STEP 4: JavaScript Module (8h)

File: `Themes/Sixteen/resources/js/modules/leaflet-map.js`

```javascript
import L from 'leaflet';
import 'leaflet.markercluster';
import { GeoSearchControl, OpenStreetMapProvider } from 'leaflet-geosearch';

export function ticketMap(config) {
    return {
        map: null,
        markers: L.markerClusterGroup(),
        selectedMarker: null,
        
        initMap() {
            // Initialize Leaflet map
            this.map = L.map('map').setView(
                [config.centerLat, config.centerLng],
                config.zoom
            );
            
            // Add OSM tiles
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors',
                maxZoom: 19
            }).addTo(this.map);
            
            // Mode-specific initialization
            if (config.mode === 'create') {
                this.initCreateMode();
            } else if (config.mode === 'list') {
                this.initListMode();
            } else if (config.mode === 'view') {
                this.initViewMode();
            }
        },
        
        initCreateMode() {
            // Click to select location
            this.map.on('click', (e) => {
                this.selectLocation(e.latlng.lat, e.latlng.lng);
            });
            
            // Init geocoding search
            const provider = new OpenStreetMapProvider({
                params: {
                    'accept-language': 'it',
                    countrycodes: 'it',
                },
            });
            
            const searchControl = new GeoSearchControl({
                provider: provider,
                style: 'bar',
                showMarker: false,
                retainZoomLevel: false,
                animateZoom: true,
                searchLabel: 'Cerca indirizzo...',
            });
            
            this.map.addControl(searchControl);
            
            // Listen to search results
            this.map.on('geosearch/showlocation', (result) => {
                this.selectLocation(
                    result.location.y,
                    result.location.x,
                    result.location.label
                );
            });
        },
        
        selectLocation(lat, lng, address = null) {
            // Remove previous marker
            if (this.selectedMarker) {
                this.map.removeLayer(this.selectedMarker);
            }
            
            // Add new marker
            this.selectedMarker = L.marker([lat, lng], {
                icon: this.getCustomIcon('selected')
            }).addTo(this.map);
            
            // Reverse geocode if no address
            if (!address) {
                this.reverseGeocode(lat, lng);
            } else {
                // Emit to Livewire
                Livewire.dispatch('map-location-selected', {
                    lat, lng, address
                });
            }
            
            this.map.setView([lat, lng], 15);
        },
        
        async reverseGeocode(lat, lng) {
            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/reverse?` +
                    `lat=${lat}&lon=${lng}&format=json&accept-language=it`
                );
                const data = await response.json();
                
                Livewire.dispatch('map-location-selected', {
                    lat, 
                    lng, 
                    address: data.display_name
                });
            } catch (error) {
                console.error('Geocoding failed:', error);
                Livewire.dispatch('map-location-selected', { lat, lng });
            }
        },
        
        initListMode() {
            // Add all ticket markers with clustering
            config.tickets.forEach(ticket => {
                const marker = L.marker([ticket.latitude, ticket.longitude], {
                    icon: this.getCustomIcon(ticket.status, ticket.priority)
                });
                
                marker.bindPopup(this.createPopupContent(ticket));
                marker.on('click', () => {
                    Livewire.dispatch('ticket-clicked', { ticketId: ticket.id });
                });
                
                this.markers.addLayer(marker);
            });
            
            this.map.addLayer(this.markers);
            
            // Fit bounds to show all markers
            if (config.tickets.length > 0) {
                this.map.fitBounds(this.markers.getBounds());
            }
        },
        
        initViewMode() {
            // Single marker for ticket view
            L.marker([config.markerLat, config.markerLng], {
                icon: this.getCustomIcon('view')
            })
            .addTo(this.map)
            .bindPopup(config.markerTitle)
            .openPopup();
        },
        
        getCustomIcon(status, priority = null) {
            const colors = {
                'selected': '#0066cc',
                'open': '#d32f2f',
                'in_progress': '#f57c00',
                'resolved': '#388e3c',
                'closed': '#757575',
                'view': '#0066cc'
            };
            
            const color = colors[status] || '#0066cc';
            
            return L.divIcon({
                className: 'custom-marker',
                html: `<div style="
                    background-color: ${color};
                    width: 30px;
                    height: 30px;
                    border-radius: 50%;
                    border: 3px solid white;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.3);
                "></div>`,
                iconSize: [30, 30],
                iconAnchor: [15, 15]
            });
        },
        
        createPopupContent(ticket) {
            return `
                <div class="ticket-popup">
                    <h6>${ticket.name}</h6>
                    <span class="badge bg-${this.getStatusColor(ticket.status)}">
                        ${ticket.status}
                    </span>
                    <a href="/tickets/${ticket.id}" class="btn btn-sm btn-primary mt-2">
                        Vedi dettagli
                    </a>
                </div>
            `;
        },
        
        getStatusColor(status) {
            const colors = {
                'open': 'danger',
                'in_progress': 'warning',
                'resolved': 'success',
                'closed': 'secondary'
            };
            return colors[status] || 'primary';
        }
    };
}

// Auto-initialize on Alpine
document.addEventListener('alpine:init', () => {
    Alpine.data('ticketMap', ticketMap);
});
```

### STEP 5: CSS Styles (2h)

File: `Themes/Sixteen/resources/css/components/leaflet-map.css`

```css
/* Leaflet Map Container */
.ticket-map-container {
    position: relative;
    width: 100%;
}

.leaflet-map {
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    z-index: 1;
}

/* Custom Marker Popup */
.ticket-popup {
    min-width: 200px;
    text-align: center;
}

.ticket-popup h6 {
    margin-bottom: 8px;
    font-weight: 600;
}

/* Search Box */
.map-search-box {
    position: relative;
}

.search-results-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-radius: 4px;
    max-height: 300px;
    overflow-y: auto;
    z-index: 1000;
    display: none;
}

.search-results-dropdown.show {
    display: block;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .leaflet-map {
        height: 400px !important;
    }
    
    .leaflet-control-zoom {
        transform: scale(1.2);
    }
}

/* Dark mode support */
.dark .leaflet-tile {
    filter: brightness(0.7) contrast(1.2);
}
```

### STEP 6: Integration in Create Ticket Form (3h)

File: `Modules/Fixcity/resources/views/pages/tickets/create.blade.php`

```blade
<div class="col-12 col-lg-8">
    <div class="card">
        <div class="card-header">
            <h5>Dove si trova il problema?</h5>
        </div>
        <div class="card-body">
            <livewire:ticket-map mode="create" />
            
            {{-- Hidden inputs for form submission --}}
            <input type="hidden" name="latitude" wire:model="latitude">
            <input type="hidden" name="longitude" wire:model="longitude">
            <input type="hidden" name="address" wire:model="address">
        </div>
    </div>
</div>
```

---

## ✅ Testing Checklist

### Funzionalità
- [ ] Mappa si carica correttamente
- [ ] Click su mappa seleziona posizione
- [ ] Autocomplete indirizzi funziona
- [ ] Marker viene piazzato correttamente
- [ ] Reverse geocoding popola address
- [ ] Clustering funziona con 100+ markers
- [ ] Popup ticket mostra info corrette

### Performance
- [ ] Inizializzazione < 2s
- [ ] Nessun memory leak
- [ ] Smooth su mobile
- [ ] Tiles caricano progressivamente

### Accessibilità
- [ ] Navigabile da tastiera
- [ ] ARIA labels presenti
- [ ] Screen reader compatible
- [ ] Focus indicators visibili

### Browser Compatibility
- [ ] Chrome/Edge (latest)
- [ ] Firefox (latest)
- [ ] Safari (latest)
- [ ] Mobile Safari
- [ ] Mobile Chrome

---

## 📊 Metriche Successo

- **Load time**: < 2 secondi
- **FPS**: 60fps smooth panning/zooming
- **Memory**: < 50MB con 500 markers
- **Lighthouse Score**: > 90

---

## 🚀 Go Live Checklist

- [ ] Documentazione completa
- [ ] Test Pest scritti
- [ ] Performance validata
- [ ] Accessibilità WCAG AA
- [ ] Mobile responsive verified
- [ ] Browser testing passed
- [ ] Security audit (XSS, injection)
- [ ] Backup plan se Nominatim down

---

**Inizio implementazione**: ORA  
**Completion target**: 72 ore
