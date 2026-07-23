@props([
    'title'       => 'Dove Siamo',
    'address'     => 'Via Vanzo 86/A, 31021 Mogliano Veneto TV',
    'coordinates' => [],
    'image'       => '/modules/techplanner/images/map-via-vanzo.png',
])

@php
    $lat = $coordinates['lat'] ?? 45.5648;
    $lng = $coordinates['lng'] ?? 12.2347;

    // Link to open Google Maps with destination (no API key needed)
    $gmapsUrl = 'https://www.google.com/maps/search/?api=1&query=' . urlencode($address);
@endphp

<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">

        {{-- Titolo sezione --}}
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
            <p class="text-lg text-gray-600">{{ $address }}</p>
        </div>

        {{-- Mappa statica cliccabile --}}
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                <a
                    href="{{ $gmapsUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="block relative group cursor-pointer"
                    aria-label="Apri {{ $address }} in Google Maps"
                >
                    {{-- Immagine mappa OSM (statica, nessuna API) --}}
                    <img
                        src="{{ $image }}"
                        alt="Mappa: {{ $address }}"
                        class="w-full h-auto"
                        loading="lazy"
                    />

                    {{-- Hover overlay --}}
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/10 transition-all duration-200"></div>

                    {{-- Badge "Apri in Google Maps" --}}
                    <div class="absolute bottom-4 right-4 bg-white/95 backdrop-blur-sm px-4 py-2 rounded-lg shadow-lg flex items-center gap-2 group-hover:shadow-xl transition-shadow">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-blue-600 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        <span class="text-sm font-semibold text-gray-700">Apri in Google Maps</span>
                    </div>
                </a>
            </div>

            {{-- Pulsante indicazioni --}}
            <div class="mt-6 text-center">
                <a
                    href="{{ $gmapsUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-semibold text-white transition-colors"
                    style="background: #1E5A96;"
                    onmouseover="this.style.background='#164575'"
                    onmouseout="this.style.background='#1E5A96'"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Ottieni Indicazioni
                </a>
            </div>

            {{-- Attribuzione OpenStreetMap (obbligatoria per ODbL) --}}
            <p class="text-center text-xs text-gray-400 mt-3">
                Mappa © <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener" class="underline hover:text-gray-600">OpenStreetMap</a> contributors
            </p>
        </div>
    </div>
</section>
