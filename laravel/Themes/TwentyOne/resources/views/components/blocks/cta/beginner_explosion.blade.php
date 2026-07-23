@props([
    'title' => '🎁 BONUS BEGINNER ESPLOSIVO!',
    'subtitle' => 'Inizia GRATIS e ricevi bonus immediati',
    'offers' => []
])

@php
    $defaultOffers = [
        ['title' => 'Prime 3 previsioni', 'value' => 'GRATIS', 'description' => 'Nessun rischio per iniziare'],
        ['title' => 'Bonus benvenuto', 'value' => '200 crediti', 'description' => 'Bonus di benvenuto gratuito'],
        ['title' => 'Streak bonus', 'value' => '10x', 'description' => 'Moltiplicatore prime vittorie']
    ];
    $displayOffers = !empty($offers) ? $offers : $defaultOffers;
@endphp

<div class="relative bg-gradient-to-br from-green-900/40 via-blue-900/40 to-purple-900/40 border border-green-500/30 rounded-3xl p-8 overflow-hidden">
    <!-- Epic Background Effects -->
    <div class="absolute inset-0 bg-gradient-to-r from-green-600/10 via-blue-600/10 to-purple-600/10"></div>
    <div class="absolute top-0 left-1/2 transform -translate-x-1/2 w-64 h-64 bg-gradient-to-b from-green-400/20 to-transparent rounded-full blur-3xl animate-pulse"></div>

    <!-- Floating money emojis -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        @for($i = 0; $i < 8; $i++)
        <div class="absolute animate-bounce text-2xl"
             style="
                top: {{ rand(10, 90) }}%;
                left: {{ rand(10, 90) }}%;
                animation-delay: {{ $i * 0.5 }}s;
                animation-duration: {{ 2 + ($i * 0.3) }}s;
             ">💰</div>
        @endfor
    </div>

    <div class="relative z-10">
        <!-- Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center space-x-2 bg-gradient-to-r from-green-500/20 to-blue-500/20 border border-green-500/30 rounded-full px-4 py-2 mb-4">
                <div class="w-2 h-2 bg-green-400 rounded-full animate-ping"></div>
                <span class="text-green-300 text-xs font-bold uppercase tracking-wider">OFFERTA LIMITATA</span>
                <div class="w-2 h-2 bg-blue-400 rounded-full animate-ping" style="animation-delay: 0.5s;"></div>
            </div>

            <h2 class="text-4xl font-black text-white mb-3">
                <span class="bg-gradient-to-r from-green-400 via-blue-400 to-purple-400 bg-clip-text text-transparent">
                    {{ $title }}
                </span>
            </h2>
            <p class="text-gray-300 text-lg">{{ $subtitle }}</p>
        </div>

        <!-- Offers Grid -->
        <div class="grid md:grid-cols-3 gap-6 mb-8">
            @foreach($displayOffers as $offer)
            <div class="bg-gray-800/60 border border-gray-600/40 rounded-2xl p-6 text-center hover:scale-105 transition-all duration-300 relative overflow-hidden group">
                <!-- Glow effect -->
                <div class="absolute inset-0 bg-gradient-to-br from-green-500/10 to-blue-500/10 opacity-0 group-hover:opacity-100 transition-opacity"></div>

                <div class="relative z-10">
                    <h3 class="text-white font-bold text-lg mb-2">{{ $offer['title'] }}</h3>
                    <div class="text-3xl font-black text-green-400 mb-2">{{ $offer['value'] }}</div>
                    <p class="text-gray-400 text-sm">{{ $offer['description'] }}</p>
                </div>

                <!-- Sparkle effect -->
                <div class="absolute top-2 right-2 text-yellow-400 animate-pulse">✨</div>
            </div>
            @endforeach
        </div>

        <!-- Urgency Counter -->
        <div class="bg-red-500/20 border border-red-500/30 rounded-xl p-4 mb-6">
            <div class="text-center">
                <div class="text-red-300 text-sm font-bold mb-2">⏰ OFFERTA LIMITATA - Solo per i primi 100 utenti oggi</div>
                <div class="flex items-center justify-center space-x-2">
                    <div class="text-white font-bold">47 posti rimasti</div>
                    <div class="w-32 bg-gray-700 rounded-full h-2">
                        <div class="bg-gradient-to-r from-red-500 to-orange-500 h-2 rounded-full" style="width: 53%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main CTA -->
        <div class="text-center mb-6">
            <button class="px-12 py-6 bg-gradient-to-r from-green-500 via-blue-500 to-purple-500 hover:from-green-600 hover:via-blue-600 hover:to-purple-600 text-white font-black text-xl rounded-2xl transition-all duration-300 transform hover:scale-110 shadow-2xl relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></div>
                <div class="flex items-center justify-center space-x-3 relative">
                    <span class="text-3xl">🚀</span>
                    <span>INIZIA GRATIS ORA</span>
                    <span class="text-3xl animate-bounce">🎁</span>
                </div>
            </button>
        </div>

        <!-- Trust Badges -->
        <div class="flex items-center justify-center space-x-6 text-sm text-gray-400">
            <div class="flex items-center space-x-1">
                <span>🔒</span>
                <span>Nessuna carta di credito</span>
            </div>
            <div class="w-px h-4 bg-gray-600"></div>
            <div class="flex items-center space-x-1">
                <span>⚡</span>
                <span>Accesso immediato</span>
            </div>
            <div class="w-px h-4 bg-gray-600"></div>
            <div class="flex items-center space-x-1">
                <span>🎯</span>
                <span>Solo crediti virtuali</span>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes float {
    0%, 100% { transform: translateY(0px); }
    50% { transform: translateY(-10px); }
}

.animate-float {
    animation: float 3s ease-in-out infinite;
}
</style>
