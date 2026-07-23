import { LitElement, html, css } from 'lit';
import { map } from 'lit/directives/map.js';

export class MyMap extends LitElement {
    static properties = {
        latitude: { type: Number },
        longitude: { type: Number },
        zoom: { type: Number },
        height: { type: String },
        mapId: { type: String },
        shellId: { type: String },
        btnId: { type: String },
        layerControlId: { type: String },
        defaultLat: { type: Number },
        defaultLng: { type: Number },
        defaultZoom: { type: Number },
        sprite: { type: String },
        initialLat: { type: Number },
        initialLng: { type: Number }
    };

    static styles = css`
        :host {
            display: block;
            width: 100%;
        }

        .latitude-longitude-map-shell {
            position: relative;
            min-height: var(--height, 340px);
            border-radius: 0.375rem;
            overflow: hidden;
        }

        .latitude-longitude-map-canvas {
            width: 100%;
            height: var(--height, 340px);
            margin: 0;
            border-radius: 0;
            border: 0;
        }

        #map-layer-ctrl {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-bottom: 12px;
        }

        #map-layer-ctrl button {
            flex: 1;
            min-width: 80px;
            padding: 8px 12px;
            font-size: 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background-color: white;
            color: #374151;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        #map-layer-ctrl button:hover {
            background-color: #f3f4f6;
        }

        #map-layer-ctrl button.active {
            background-color: #0066cc;
            color: white;
            border-color: #0066cc;
        }

        #btn-geo-locate {
            flex: 1;
            min-width: 120px;
            padding: 8px 12px;
            font-size: 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            background-color: white;
            color: #374151;
            cursor: pointer;
            transition: all 0.2s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        #btn-geo-locate:hover:not(:disabled) {
            background-color: #f3f4f6;
        }

        #btn-geo-locate:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .map-fullscreen-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 1000;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 32px;
            height: 32px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .map-fullscreen-btn:hover {
            background-color: #f0f0f0;
        }

        .map-fullscreen-btn:focus-visible {
            outline: 2px solid #0066cc;
            outline-offset: 2px;
        }

        .latitude-longitude-map-shell.is-browser-fullscreen {
            width: 100vw;
            height: 100vh;
            max-width: none;
            max-height: none;
            border-radius: 0;
            border: none;
        }

        .latitude-longitude-map-shell.is-browser-fullscreen .latitude-longitude-map-canvas {
            height: 100vh;
            min-height: 100vh;
        }

        .latitude-longitude-map-shell.is-browser-fullscreen .map-fullscreen-btn {
            top: 10px;
            right: 10px;
            z-index: 10000;
        }

        @media (max-width: 768px) {
            .latitude-longitude-map-canvas {
                height: 300px;
            }

            .latitude-longitude-map-shell {
                min-height: 300px;
            }
        }

        @media (max-width: 480px) {
            .latitude-longitude-map-canvas {
                height: 250px;
            }

            .latitude-longitude-map-shell {
                min-height: 250px;
            }

            #map-layer-ctrl {
                flex-direction: column;
                gap: 4px;
            }

            #map-layer-ctrl button,
            #btn-geo-locate {
                width: 100%;
            }
        }
    `;

    constructor() {
        super();
        this.map = null;
        this.marker = null;
        this.mapLayers = {};
    }

    updated(changedProperties) {
        if (changedProperties.has('latitude') || changedProperties.has('longitude')) {
            this.updateMarkerPosition();
        }
    }

    firstUpdated() {
        this.initializeMap();
    }

    initializeMap() {
        // Import Leaflet dynamically
        import('leaflet').then(L => {
            // Initialize map
            this.map = L.map(this.mapId).setView(
                [this.initialLat || this.defaultLat, this.initialLng || this.defaultLng],
                this.defaultZoom
            );

            // Add tile layers
            this.mapLayers.osm = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(this.map);

            this.mapLayers.satellite = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: '© Esri'
            });

            // Create marker
            this.marker = L.marker([this.initialLat || this.defaultLat, this.initialLng || this.defaultLng], {
                draggable: true
            }).addTo(this.map);

            // Update coordinates on marker drag
            this.marker.on('drag', (e) => {
                const position = e.target.getLatLng();
                this.dispatchEvent(new CustomEvent('coordinates-changed', {
                    detail: {
                        latitude: position.lat,
                        longitude: position.lng
                    }
                }));
            });

            // Create controls
            this.createControls();
        });
    }

    createControls() {
        // Layer control
        const layerControl = document.getElementById(this.layerControlId);
        if (layerControl) {
            layerControl.innerHTML = `
                <button class="${this.mapLayers.osm ? 'active' : ''}" data-layer="osm">Stradale</button>
                <button class="${this.mapLayers.satellite ? 'active' : ''}" data-layer="satellite">Satellite</button>
            `;

            layerControl.addEventListener('click', (e) => {
                if (e.target.tagName === 'BUTTON') {
                    const layer = e.target.dataset.layer;
                    Object.entries(this.mapLayers).forEach(([key, value]) => {
                        if (key === layer) {
                            this.map.addLayer(value);
                            e.target.classList.add('active');
                        } else {
                            this.map.removeLayer(value);
                            layerControl.querySelector(`[data-layer="${key}"]`).classList.remove('active');
                        }
                    });
                }
            });
        }

        // Geolocation button
        const geoBtn = document.getElementById(this.btnId);
        if (geoBtn) {
            geoBtn.addEventListener('click', () => {
                if (navigator.geolocation) {
                    geoBtn.disabled = true;
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            const lat = position.coords.latitude;
                            const lng = position.coords.longitude;
                            this.map.setView([lat, lng], 15);
                            this.marker.setLatLng([lat, lng]);
                            geoBtn.disabled = false;
                            this.dispatchEvent(new CustomEvent('coordinates-changed', {
                                detail: { latitude: lat, longitude: lng }
                            }));
                        },
                        () => {
                            geoBtn.disabled = false;
                        }
                    );
                }
            });
        }

        // Fullscreen button
        const fullscreenBtn = document.getElementById(this.btnFullscreenId);
        if (fullscreenBtn) {
            fullscreenBtn.addEventListener('click', () => {
                const shell = document.getElementById(this.shellId);
                shell.classList.toggle('is-browser-fullscreen');
                if (shell.classList.contains('is-browser-fullscreen')) {
                    if (this.map) {
                        this.map.invalidateSize();
                    }
                }
            });
        }
    }

    updateMarkerPosition() {
        if (this.marker && this.latitude !== undefined && this.longitude !== undefined) {
            this.marker.setLatLng([this.latitude, this.longitude]);
            if (this.map) {
                this.map.panTo([this.latitude, this.longitude]);
            }
        }
    }

    render() {
        return html`
            <div class="latitude-longitude-map-shell" id="${this.shellId}">
                <div id="${this.layerControlId}" class="map-layer-ctrl"></div>
                <div id="${this.mapId}" class="latitude-longitude-map-canvas"></div>
                <button id="${this.btnId}" class="geo-locate-btn">Usa la mia posizione</button>
                <button id="${this.btnFullscreenId}" class="map-fullscreen-btn">
                    <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M8 3H5a2 2 0 00-2 2v3m18 0V5a2 2 0 00-2-2h-3m0 18h3a2 2 0 002-2v-3M3 16v3a2 2 0 002 2h3"></path>
                    </svg>
                </button>
            </div>
        `;
    }
}

customElements.define('my-map', MyMap);