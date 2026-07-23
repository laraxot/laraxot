@props([
    'title' => 'MERCATI IN PRIMO PIANO',
    'subtitle' => 'Scopri su cosa stanno puntando migliaia di utenti',
    'show_all_link' => '/markets',
    'limit' => 12
])

@php
use Modules\Predict\Actions\FetchTrendingMarketsAction;
$markets = app(FetchTrendingMarketsAction::class)->execute(limit: $limit);
@endphp

<section class="py-16 bg-gradient-to-br from-gray-900 via-purple-900/50 to-gray-900">
    <div class="container mx-auto px-4">
        <!-- Header -->
        <div class="text-center mb-12">
            <h2 class="text-4xl md:text-5xl font-black text-white mb-4 flex items-center justify-center">
                <svg class="w-12 h-12 mr-4 text-yellow-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                </svg>
                {{ $title }}
            </h2>
            <p class="text-xl text-gray-300 mb-8">{{ $subtitle }}</p>
            <div class="text-green-400 font-bold text-lg flex items-center justify-center space-x-4">
                <div class="flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z"/>
                    </svg>
                    <span>{{ $markets->count() }} mercati attivi</span>
                </div>
                <span class="text-orange-400 flex items-center space-x-2">
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M13.5.67s.74 2.65.74 4.8c0 2.06-1.35 3.73-3.41 3.73-2.07 0-3.63-1.67-3.63-3.73l.03-.36C5.21 7.51 4 10.62 4 14c0 4.42 3.58 8 8 8s8-3.58 8-8C20 8.61 17.41 3.8 13.5.67z"/>
                    </svg>
                    <span>Nuovi mercati ogni giorno</span>
                </span>
            </div>
        </div>

        <!-- Markets Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-12">
            @foreach($markets as $market)
            @endforeach
        </div>

        <!-- Call to Action -->
        <div class="text-center">
            <a href="{{ $show_all_link }}"
               class="inline-flex items-center bg-gradient-to-r from-purple-500 to-pink-500 hover:from-purple-600 hover:to-pink-600 text-white font-bold py-4 px-8 rounded-xl text-lg transform hover:scale-105 transition-all duration-300 shadow-2xl">
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                ESPLORA TUTTI I {{ $markets->count() }}+ MERCATI
            </a>
            <div class="mt-4 text-gray-400 text-sm flex items-center justify-center space-x-4">
                <div class="flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M13.5.67s.74 2.65.74 4.8c0 2.06-1.35 3.73-3.41 3.73-2.07 0-3.63-1.67-3.63-3.73l.03-.36C5.21 7.51 4 10.62 4 14c0 4.42 3.58 8 8 8s8-3.58 8-8C20 8.61 17.41 3.8 13.5.67z"/>
                    </svg>
                    <span>Nuovi mercati aggiunti ogni giorno</span>
                </div>
                <div class="flex items-center space-x-1">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z"/>
                    </svg>
                    <span>Gioca con crediti virtuali</span>
                </div>
            </div>
        </div>
    </div>
</section>
