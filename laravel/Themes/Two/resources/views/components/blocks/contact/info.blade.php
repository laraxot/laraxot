@props([
    'title' => 'Come Contattarci',
    'subtitle' => 'Scegli il modo più comodo per raggiungerci',
    'info' => [],
    'social' => [],
    'coordinates' => [],
    'address' => '',
])

@php
    // Valori di default per la mappa
    $defaultAddress = 'Via Vanzo 86/A, 31021 Mogliano Veneto TV';
    $defaultCoordinates = ['lat' => 45.5633, 'lng' => 12.2506];
    
    // Usa i valori passati o i default
    $mapAddress = $address ?: $defaultAddress;
    $mapCoordinates = !empty($coordinates) ? $coordinates : $defaultCoordinates;
@endphp

<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        {{-- Section Title --}}
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
            <p class="text-lg text-gray-600">{{ $subtitle }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            {{-- Contact Information --}}
            <div class="space-y-6">
                {{-- Phone --}}
                @if(!empty($info['phone']))
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Telefono</h3>
                            <a href="tel:{{ str_replace(' ', '', $info['phone']) }}" class="text-blue-600 hover:text-blue-700">
                                {{ $info['phone'] }}
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Mobile Phone --}}
                @if(!empty($info['phone_mobile']))
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Cellulare / WhatsApp</h3>
                            <a href="tel:{{ str_replace(' ', '', $info['phone_mobile']) }}" class="text-blue-600 hover:text-blue-700">
                                {{ $info['phone_mobile'] }}
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Email --}}
                @if(!empty($info['email']))
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Email</h3>
                            <a href="mailto:{{ $info['email'] }}" class="text-blue-600 hover:text-blue-700">
                                {{ $info['email'] }}
                            </a>
                        </div>
                    </div>
                @endif

                {{-- Address --}}
                @if(!empty($info['address']))
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-red-100 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Indirizzo</h3>
                            <p class="text-gray-600">{{ $info['address'] }}</p>
                        </div>
                    </div>
                @endif

                {{-- Hours --}}
                @if(!empty($info['hours']))
                    <div class="flex items-start space-x-4">
                        <div class="flex-shrink-0 w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-900">Orari</h3>
                            <p class="text-gray-600">{{ $info['hours'] }}</p>
                        </div>
                    </div>
                @endif

                {{-- Social Media --}}
                @if(!empty($social))
                    <div class="pt-6 border-t border-gray-200">
                        <h3 class="font-semibold text-gray-900 mb-4">Seguici sui Social</h3>
                        <div class="flex space-x-4">
                            @if(!empty($social['linkedin']))
                                <a href="{{ $social['linkedin'] }}" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn - Profilo LinkedIn di Marco Sottana" class="flex items-center justify-center w-10 h-10 bg-blue-600 text-white rounded-full hover:bg-blue-700 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.79-1.75-1.764s.784-1.764 1.75-1.764 1.75.79 1.75 1.764-.783 1.764-1.75 1.764zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/>
                                    </svg>
                                </a>
                            @endif

                            @if(!empty($social['facebook']))
                                <a href="{{ $social['facebook'] }}" target="_blank" rel="noopener noreferrer" aria-label="Facebook - Pagina Facebook di Marco Sottana" class="flex items-center justify-center w-10 h-10 bg-blue-800 text-white rounded-full hover:bg-blue-900 transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                @endif
            </div>

            {{-- Map Preview (Google Maps - preciso per indirizzi italiani) --}}
            <div class="bg-gray-200 rounded-lg overflow-hidden h-96 flex items-center justify-center">
                @include('pub_theme::components.blocks.map.static-clickable', [
                    'title' => 'Dove Siamo',
                    'address' => $mapAddress,
                    'coordinates' => $mapCoordinates,
                ])
            </div>
        </div>
    </div>
</section>
