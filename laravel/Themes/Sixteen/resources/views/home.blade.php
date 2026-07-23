{{-- 
    Elenco Segnalazioni - Homepage
    Replica Design Comuni con Tailwind CSS
--}}

@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-50">
    {{-- Header Verde PA --}}
    <header class="bg-primary-500 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-white rounded-lg flex items-center justify-center">
                        <span class="text-primary-500 font-bold text-xl">FC</span>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold">Il mio Comune</h1>
                        <p class="text-sm text-primary-100">Segnalazioni e servizi</p>
                    </div>
                </div>
                <nav class="hidden md:flex space-x-6">
                    <a href="#" class="hover:text-primary-100 transition">Amministrazione</a>
                    <a href="#" class="hover:text-primary-100 transition">Novità</a>
                    <a href="#" class="hover:text-primary-100 transition">Servizi</a>
                    <a href="#" class="hover:text-primary-100 transition">Vivere il Comune1</a>
                </nav>
            </div>
        </div>
    </header>

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
        {{-- Title --}}
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Elenco segnalazioni</h1>
            <form class="w-full max-w-2xl mx-auto mb-2">
    <input type="text" name="search" placeholder="Cerca segnalazioni..." class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-primary-500">
    <button type="submit" class="w-full bg-primary-500 text-white py-1 rounded-md hover:bg-primary-600 transition font-medium">Cerca</button>
</form>
<p class="text-gray-600">Segnalazioni trovate: <span id="search-results-count">0</span></p>
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
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
<script>
    // Initialize map
    const map = L.map('map').setView([43.7696, 11.2558], 13); // Firenze

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Add sample marker
    const marker = L.marker([43.7696, 11.2558]).addTo(map);
    marker.bindPopup('<b>Segnalazione</b><br>Esempio segnalazione').openPopup();
</script>
@endpush
@endsection
