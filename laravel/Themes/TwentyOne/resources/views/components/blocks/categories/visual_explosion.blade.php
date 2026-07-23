@props([
    'title' => '🎯 ESPLORA PER CATEGORIA',
    'subtitle' => 'Trova il tuo settore preferito e inizia a vincere',
    'categories' => []
])

@php
    $defaultCategories = [
        ['name' => '💰 Crypto & DeFi', 'hot_markets' => 23, 'volume' => '1.2M crediti', 'color' => 'orange'],
        ['name' => '🗳️ Politica & Elezioni', 'hot_markets' => 18, 'volume' => '890K crediti', 'color' => 'blue'],
        ['name' => '🚀 Tech & AI', 'hot_markets' => 31, 'volume' => '750K crediti', 'color' => 'purple'],
        ['name' => '⚽ Sport & Olimpiadi', 'hot_markets' => 45, 'volume' => '650K crediti', 'color' => 'green'],
        ['name' => '🎬 Entertainment', 'hot_markets' => 27, 'volume' => '420K crediti', 'color' => 'pink'],
        ['name' => '🌍 Clima & Ambiente', 'hot_markets' => 12, 'volume' => '280K crediti', 'color' => 'emerald']
    ];
    $displayCategories = !empty($categories) ? $categories : $defaultCategories;
@endphp

<div class="bg-gray-900/60 border border-purple-500/30 rounded-3xl p-6">
    <div class="text-center mb-8">
        <h2 class="text-3xl font-black text-white mb-2">
            <span class="bg-gradient-to-r from-purple-400 to-blue-400 bg-clip-text text-transparent">{{ $title }}</span>
        </h2>
        <p class="text-gray-300">{{ $subtitle }}</p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
        @foreach($displayCategories as $category)
        <div class="category-card bg-gradient-to-br from-gray-800/60 to-gray-900/80 border border-gray-600/40 rounded-2xl p-4 hover:scale-105 transition-all duration-300 cursor-pointer group relative overflow-hidden">
            <!-- Background Glow -->
            <div class="absolute inset-0 bg-gradient-to-br from-{{ $category['color'] }}-500/10 to-transparent opacity-0 group-hover:opacity-100 transition-opacity"></div>

            <div class="relative z-10">
                <div class="text-center">
                    <h3 class="text-white font-bold text-lg mb-3 group-hover:text-{{ $category['color'] }}-300 transition-colors">
                        {{ $category['name'] }}
                    </h3>

                    <div class="space-y-2 mb-4">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-400">Mercati Attivi:</span>
                            <span class="text-{{ $category['color'] }}-400 font-bold">{{ $category['hot_markets'] }}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-400">Volume:</span>
                            <span class="text-{{ $category['color'] }}-400 font-bold">{{ $category['volume'] }}</span>
                        </div>
                    </div>

                    <button class="w-full bg-gradient-to-r from-{{ $category['color'] }}-600/20 to-{{ $category['color'] }}-500/20 border border-{{ $category['color'] }}-500/30 hover:from-{{ $category['color'] }}-600/40 hover:to-{{ $category['color'] }}-500/40 text-{{ $category['color'] }}-300 font-bold py-2 rounded-lg transition-all">
                        ESPLORA
                    </button>
                </div>
            </div>

            <!-- Sparkle effects -->
            <div class="absolute top-2 right-2 text-{{ $category['color'] }}-400 opacity-60 group-hover:animate-pulse">✨</div>
        </div>
        @endforeach
    </div>

    <!-- Bottom CTA -->
    <div class="text-center mt-8">
        <button class="px-8 py-4 bg-gradient-to-r from-purple-600 via-blue-600 to-teal-600 hover:from-purple-700 hover:via-blue-700 hover:to-teal-700 text-white font-black rounded-xl transition-all duration-300 transform hover:scale-105 shadow-2xl">
            🚀 VEDI TUTTE LE CATEGORIE
        </button>
    </div>
</div>
