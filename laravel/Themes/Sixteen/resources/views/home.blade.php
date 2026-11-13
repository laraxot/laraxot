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
                        <h1 class="text-2xl font-bold">{{ __('pub_theme::header.center.brand.title.label') }}</h1>
                        <p class="text-sm text-primary-100">{{ __('pub_theme::home_components.home_page.tagline.label') }}</p>
                    </div>
                </div>
                <nav class="hidden md:flex space-x-6">
                    <a href="#" class="hover:text-primary-100 transition">{{ __('pub_theme::header.center.nav.amministrazione.label') }}</a>
                    <a href="#" class="hover:text-primary-100 transition">{{ __('pub_theme::header.center.nav.novita.label') }}</a>
                    <a href="#" class="hover:text-primary-100 transition">{{ __('pub_theme::header.center.nav.servizi.label') }}</a>
                    <a href="#" class="hover:text-primary-100 transition">{{ __('pub_theme::header.center.nav.vivere.label') }}1</a>
                </nav>
            </div>
        </div>
    </header>

    {{-- Breadcrumb --}}
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <nav class="flex text-sm text-gray-600">
                <a href="/" class="hover:text-primary-500">{{ __('pub_theme::common.navigation.home.label') }}</a>
                <span class="mx-2">/</span>
                <span class="text-gray-900">{{ __('pub_theme::home_components.home_page.list.title.label') }}</span>
            </nav>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        {{-- Title --}}
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ __('pub_theme::home_components.home_page.list.title.label') }}</h1>
            <form class="w-full max-w-2xl mx-auto mb-2">
    <input type="text" name="search" placeholder="{{ __('pub_theme::home_components.home_page.search.placeholder') }}" class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-primary-500">
    <button type="submit" class="w-full bg-primary-500 text-white py-1 rounded-md hover:bg-primary-600 transition font-medium">{{ __('pub_theme::header.center.search.label') }}</button>
</form>
<p class="text-gray-600">{{ __('pub_theme::home_components.home_page.results_found.label') }} <span id="search-results-count">0</span></p>
        </div>

        {{-- Layout: Sidebar + Map --}}
        <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
            {{-- Sidebar Filtri --}}
            <aside class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">{{ __('pub_theme::home_components.home_page.category.title') }}</h2>
                    
                    <div class="space-y-3">
                        {{-- Categoria 1 --}}
                        <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">{{ __('pub_theme::home_components.home_page.categories.water.label') }}</span>
                                <span class="text-xs text-gray-500 block">(251)</span>
                            </div>
                        </label>

                        {{-- Categoria 2 --}}
                        <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">{{ __('pub_theme::home_components.home_page.categories.environment.label') }}</span>
                                <span class="text-xs text-gray-500 block">(114)</span>
                            </div>
                        </label>

                        {{-- Categoria 3 --}}
                        <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">{{ __('pub_theme::home_components.home_page.categories.street_furniture.label') }}</span>
                                <span class="text-xs text-gray-500 block">(7)</span>
                            </div>
                        </label>

                        {{-- Categoria 4 --}}
                        <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">{{ __('pub_theme::home_components.home_page.categories.stray_animals.label') }}</span>
                                <span class="text-xs text-gray-500 block">(208)</span>
                            </div>
                        </label>

                        {{-- Categoria 5 --}}
                        <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">{{ __('pub_theme::home_components.home_page.categories.hygiene.label') }}</span>
                                <span class="text-xs text-gray-500 block">(321)</span>
                            </div>
                        </label>

                        {{-- Categoria 6 --}}
                        <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">{{ __('pub_theme::home_components.home_page.categories.buildings.label') }}</span>
                                <span class="text-xs text-gray-500 block">(360)</span>
                            </div>
                        </label>

                        {{-- Categoria 7 --}}
                        <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">{{ __('pub_theme::home_components.home_page.categories.public_order.label') }}</span>
                                <span class="text-xs text-gray-500 block">(302)</span>
                            </div>
                        </label>

                        {{-- Categoria 8 --}}
                        <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">{{ __('pub_theme::home_components.home_page.categories.parks.label') }}</span>
                                <span class="text-xs text-gray-500 block">(302)</span>
                            </div>
                        </label>

                        {{-- Categoria 9 --}}
                        <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">{{ __('pub_theme::home_components.home_page.categories.municipal_services.label') }}</span>
                                <span class="text-xs text-gray-500 block">(302)</span>
                            </div>
                        </label>

                        {{-- Categoria 10 --}}
                        <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">{{ __('pub_theme::home_components.home_page.categories.safety.label') }}</span>
                                <span class="text-xs text-gray-500 block">(302)</span>
                            </div>
                        </label>

                        {{-- Categoria 11 --}}
                        <label class="flex items-start space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition">
                            <input type="checkbox" class="mt-1 h-4 w-4 text-primary-500 border-gray-300 rounded focus:ring-primary-500">
                            <div class="flex-1">
                                <span class="text-sm font-medium text-gray-900">{{ __('pub_theme::home_components.home_page.categories.roads.label') }}</span>
                                <span class="text-xs text-gray-500 block">(802)</span>
                            </div>
                        </label>
                    </div>

                    {{-- Risultati Button --}}
                    <button class="w-full mt-6 bg-primary-500 text-white py-2 px-4 rounded-lg hover:bg-primary-600 transition font-medium">
                        642 {{ __('pub_theme::home_components.home_page.results.label') }}
                    </button>
                </div>
            </aside>

            {{-- Map + List Area --}}
            <div class="lg:col-span-3">
                {{-- Toggle Buttons --}}
                <div class="flex space-x-2 mb-4">
                    <button class="flex-1 bg-white border-2 border-primary-500 text-primary-500 py-2 px-4 rounded-lg font-medium hover:bg-primary-50 transition">
                        {{ __('pub_theme::home.list.map') }}
                    </button>
                    <button class="flex-1 bg-white border border-gray-300 text-gray-700 py-2 px-4 rounded-lg font-medium hover:bg-gray-50 transition">
                        {{ __('pub_theme::home.list.list') }}
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
<script>
    // Local Leaflet (asset locale, no CDN) — DesignComuni
    document.addEventListener('DOMContentLoaded', () => {
        if (window.L) {
            const map = L.map('map').setView([43.7696, 11.2558], 13);
            L.tileLayer('/assets/leaflet-tiles/{z}/{x}/{y}.png', { attribution: '© DesignComuni' }).addTo(map);
            const customIcon = L.divIcon({ html: '<svg width="24" height="24" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10" fill="#007a52" stroke="#fff" stroke-width="2"/></svg>', className: 'ticket-marker-visible', iconSize: [24,24], iconAnchor: [12,24] });
            L.marker([43.7696, 11.2558], {icon: customIcon}).addTo(map).bindPopup('<b>Segnalazione</b>');
        }
    });
</script>
@endpush
@endsection
