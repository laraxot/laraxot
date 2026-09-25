{{--
    Elenco Segnalazioni - Homepage
    Replica Design Comuni con Tailwind CSS + Alpine.js
    Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazioni-elenco.html
--}}

@extends('sixteen::layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50" x-data="{ activeTab: 'map' }">
    {{-- Hero Block --}}
    <section class="bg-primary-500 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <h1 class="text-3xl md:text-4xl font-bold mb-4">Elenco segnalazioni</h1>
            <p class="text-lg text-primary-100">Negli ultimi 12 mesi sono state risolte 73 segnalazioni.</p>
        </div>
    </section>

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <nav class="flex text-sm text-gray-600">
                <a href="/" class="hover:text-primary-500">Home</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900">Elenco segnalazioni</span>
            </nav>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Header with CTA --}}
        <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Elenco segnalazioni</h2>
                <p class="text-gray-600">Negli ultimi 12 mesi sono state risolte 73 segnalazioni.</p>
            </div>
            <button class="mt-4 md:mt-0 bg-primary-500 text-white px-6 py-3 rounded-lg font-medium hover:bg-primary-600 transition">
                Fai una segnalazione
            </button>
        </div>

        {{-- Layout: Sidebar + Map --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Sidebar Filtri --}}
            <aside class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">CATEGORIA</h2>
                    
                    <div class="space-y-3">
                        {{-- Categoria 1 --}}
                        <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">Acqua, allagamenti, problemi idrici</span>
                                <span class="text-xs text-gray-500 block">(251)</span>
                            </div>
                        </label>

                        {{-- Categoria 2 --}}
                        <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">Ambiente, inquinamento, protezione ambientale</span>
                                <span class="text-xs text-gray-500 block">(114)</span>
                            </div>
                        </label>

                        {{-- Categoria 3 --}}
                        <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">Arredo urbano</span>
                                <span class="text-xs text-gray-500 block">(7)</span>
                            </div>
                        </label>

                        {{-- Categoria 4 --}}
                        <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">Dissestazione, divalizzazione, animali randagi</span>
                                <span class="text-xs text-gray-500 block">(208)</span>
                            </div>
                        </label>

                        {{-- Categoria 5 --}}
                        <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">Igiene urbana, rifiuti, pulizia e decoro</span>
                                <span class="text-xs text-gray-500 block">(321)</span>
                            </div>
                        </label>

                        {{-- Categoria 6 --}}
                        <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">Manutenzione immobili, edifici pubblici, scuole, barriere architettoniche, cimiteri</span>
                                <span class="text-xs text-gray-500 block">(360)</span>
                            </div>
                        </label>

                        {{-- Categoria 7 --}}
                        <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">Ordine pubblico, disturbo della quiete</span>
                                <span class="text-xs text-gray-500 block">(302)</span>
                            </div>
                        </label>

                        {{-- Categoria 8 --}}
                        <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">Parchi e verde pubblico</span>
                                <span class="text-xs text-gray-500 block">(302)</span>
                            </div>
                        </label>

                        {{-- Categoria 9 --}}
                        <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">Servizi del comune</span>
                                <span class="text-xs text-gray-500 block">(302)</span>
                            </div>
                        </label>

                        {{-- Categoria 10 --}}
                        <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">Sicurezza, degrado urbano e sociale</span>
                                <span class="text-xs text-gray-500 block">(302)</span>
                            </div>
                        </label>

                        {{-- Categoria 11 --}}
                        <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">Strade, marciapiedi, segnaletica e viabilità</span>
                                <span class="text-xs text-gray-500 block">(802)</span>
                            </div>
                        </label>
                    </div>

                    {{-- Risultati Button --}}
                    <button class="w-full mt-6 bg-primary-500 text-white py-2 px-4 rounded-lg hover:bg-primary-600 transition font-medium">
                        642 Risultati
                    </button>
                </div>
            </aside>

            {{-- Map + List Area --}}
            <div class="lg:col-span-3">
                {{-- Toggle Buttons --}}
                <div class="flex space-x-2 mb-4">
                    <button class="flex-1 bg-white border-2 border-primary-500 text-primary-500 py-2 px-4 rounded-lg font-medium hover:bg-primary-50 transition">
                        Mappa
                    </button>
                    <button class="flex-1 bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-lg font-medium hover:bg-gray-50 transition">
                        Elenco
                    </button>
                </div>

                {{-- Map Container --}}
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                    <div id="map" class="w-full h-[600px]"></div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
{{-- Load Leaflet and Marker Cluster assets --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.css" />
<link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.5.3/dist/MarkerCluster.Default.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet.markercluster@1.5.3/dist/leaflet.markercluster.js"></script>

<script>
    // Load the SAME JSON that powers the map and filters
    const ticketData = @json($ticketJson ?? []);

    // Initialise map once DOM is ready
    document.addEventListener('DOMContentLoaded', function () {
        // Default centre on Italy
        const map = L.map('map').setView([41.9, 12.4964], 6);

        // OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Marker cluster group
        const markerCluster = L.markerClusterGroup();

        // Build markers from the SAME JSON data used for filters
        ticketData.forEach(function (ticket) {
            if (!ticket.geometry || !ticket.geometry.coordinates) return;

            const coords = ticket.geometry.coordinates;
            const latlng = [coords[1], coords[0]]; // [lat, lng]

            const marker = L.marker(latlng);

            // Build popup content from ticket properties
            const props = ticket.properties || {};
            const popupContent = `
                <div class="ticket-popup">
                    <strong>${props.title || 'Segnalazione'}</strong><br>
                    ${props.address || ''}<br>
                    <a href="${props.detail_url || '#'}" class="text-blue-600 underline">Dettagli</a>
                </div>
            `;
            marker.bindPopup(popupContent);

            markerCluster.addLayer(marker);
        });

        // Add cluster group to map
        map.addLayer(markerCluster);

        // Optional: listen for filter changes and re-render markers
        // window.addEventListener('ticket-filters-changed', function(e) { ... })
    });
</script>
@endpush
@endsection
