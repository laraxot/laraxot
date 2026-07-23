@props([
    'title' => 'Dove Siamo',
    'address' => '',
    'coordinates' => [],
])

@php
    $displayAddress = $address ?: 'Via Vanzo 86/A, 31021 Mogliano Veneto TV';

    // Link navigazione Google Maps (gratuito, non è una chiamata API)
    if (!empty($coordinates['lat']) && !empty($coordinates['lng'])) {
        $mapsUrl = 'https://www.google.com/maps?q='.$coordinates['lat'].','.$coordinates['lng'];
    } else {
        $mapsUrl = 'https://www.google.com/maps/search/?api=1&query='.urlencode($displayAddress);
    }

    // PNG locale (screenshot manuale da Google Maps UI) - niente richieste esterne a runtime.
    $imagePath = public_path('modules/techplanner/images/map-via-vanzo.png');
    $imageUrl = asset('modules/techplanner/images/map-via-vanzo.png');

    $hasLocalImage = file_exists($imagePath) && filesize($imagePath) > 1000;
@endphp

<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">

        <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 mb-2">{{ $title }}</h2>
            @if(!empty($address))
                <p class="text-lg text-gray-600">{{ $address }}</p>
            @endif
        </div>

        <div class="max-w-4xl mx-auto">
            {{-- Mappa statica (PNG locale) + link Google Maps --}}
            <a
                href="{{ $mapsUrl }}"
                target="_blank"
                rel="noopener noreferrer"
                class="block bg-white rounded-xl shadow-lg overflow-hidden group focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2"
            >
                <span class="sr-only">Apri {{ $displayAddress }} su Google Maps</span>
                @if($hasLocalImage)
                    <img
                        src="{{ $imageUrl }}"
                        alt="Mappa: {{ $displayAddress }}"
                        class="w-full h-auto transition-opacity duration-200 group-hover:opacity-90"
                        loading="lazy"
                    />
                @else
                    <div class="w-full h-[450px] flex items-center justify-center bg-gray-50">
                        <div class="text-center px-6">
                            <p class="text-gray-900 font-semibold">{{ $displayAddress }}</p>
                            <p class="text-gray-600 text-sm mt-2">Apri la posizione su Google Maps</p>
                        </div>
                    </div>
                @endif
            </a>

            {{-- Pulsante apertura Google Maps navigazione --}}
            <div class="mt-6 text-center">
                <a
                    href="{{ $mapsUrl }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors shadow-md focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-600 focus-visible:ring-offset-2"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Ottieni Indicazioni su Google Maps
                </a>
            </div>
        </div>
    </div>
</section>
