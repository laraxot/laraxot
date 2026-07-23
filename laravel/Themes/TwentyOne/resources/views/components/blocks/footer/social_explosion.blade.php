<?php

use function Livewire\Volt\{state, computed, mount, on};

state([
    'recent_winners' => [],
    'media_mentions' => [],
    'social_stats' => [],
    'show_live_feed' => true,
    'show_media' => true,
    'show_social' => true
]);

mount(function ($data = []) {
    foreach ($data as $key => $value) {
        if (property_exists($this, $key)) {
            $this->$key = $value;
        }
    }
    
    if (empty($this->recent_winners)) {
        $this->recent_winners = $this->getRecentWinners();
    }
    
    if (empty($this->media_mentions)) {
        $this->media_mentions = ['TechCrunch', 'Forbes', 'Wall Street Journal', 'Bloomberg'];
    }
    
    if (empty($this->social_stats)) {
        $this->social_stats = [
            'telegram' => '12K members',
            'twitter' => '25K followers', 
            'discord' => '8K members'
        ];
    }
});

$getRecentWinners = function() {
    return [
        ['user' => 'CryptoKing_23', 'amount' => '€2,847', 'time' => '2 min fa'],
        ['user' => 'Sofia_Oracle', 'amount' => '€1,923', 'time' => '5 min fa'],
        ['user' => 'Marco_Predictor', 'amount' => '€3,156', 'time' => '8 min fa'],
        ['user' => 'Anna_Winner', 'amount' => '€987', 'time' => '12 min fa'],
        ['user' => 'Luca_Analyst', 'amount' => '€1,445', 'time' => '15 min fa']
    ];
};

?>

<footer class="bg-gradient-to-br from-gray-900 via-purple-900 to-indigo-900 text-white relative overflow-hidden">
    
    <!-- Background Effects -->
    <div class="absolute inset-0">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-purple-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse animation-delay-2000"></div>
    </div>
    
    <div class="relative z-10">
        
    
    <div class="relative z-10">
        
        {{-- Fix per $show_live_feed e $recent_winners --}}
        @if(($show_live_feed ?? true) && is_array($recent_winners) && count($recent_winners) > 0)
            <!-- Live Winners Feed -->
            <div class="border-b border-white/10 py-6">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-bold text-white flex items-center">
                            <div class="w-3 h-3 bg-green-400 rounded-full animate-pulse mr-2"></div>
                            🔥 Vincite in Tempo Reale
                        </h3>
                        <div class="text-sm text-gray-300">Aggiornato ogni minuto</div>
                    </div>
                    
                    {{-- Contenuto feed live --}}
                </div>
            </div>
        @endif
    </div>
    
    {{-- Sezione Media Mentions con fix --}}
    @if(($show_media ?? true) && is_array($media_mentions) && count($media_mentions) > 0)
        <!-- Media Mentions -->
        <div class="border-t border-white/10 py-8">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-6">
                    <h4 class="text-lg font-semibold text-white mb-2">📰 Citati da</h4>
                </div>
                <div class="flex flex-wrap justify-center items-center space-x-8 opacity-60">
                    @foreach($media_mentions as $media)
                        <div class="text-white font-semibold text-lg">{{ $media }}</div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

        
        <!-- Main Footer Content -->
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    
                    <!-- Brand Section -->
                    <div class="lg:col-span-2">
                        <div class="flex items-center mb-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-lg flex items-center justify-center mr-3">
                                <span class="text-xl font-bold">P</span>
                            </div>
                            <h3 class="text-2xl font-bold">PredictFuture</h3>
                        </div>
                        <p class="text-gray-300 mb-6 max-w-md">
                            La piattaforma leader per previsioni intelligenti. Unisciti a migliaia di utenti che stanno già guadagnando prevedendo il futuro.
                        </p>
                        
                        <!-- Quick Stats -->
                        <div class="grid grid-cols-3 gap-4 mb-6">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-green-400">€2.4M+</div>
                                <div class="text-xs text-gray-400">Pagati</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-blue-400">12K+</div>
                                <div class="text-xs text-gray-400">Utenti</div>
                            </div>
                            <div class="text-center">
                                <div class="text-2xl font-bold text-purple-400">4.9★</div>
                                <div class="text-xs text-gray-400">Rating</div>
                            </div>
                        </div>
                        
                        <!-- Newsletter -->
                        <div class="bg-white/10 backdrop-blur-sm rounded-lg p-4 border border-white/20">
                            <h4 class="font-semibold mb-2">🚀 Newsletter VIP</h4>
                            <p class="text-sm text-gray-300 mb-3">Ricevi previsioni esclusive e bonus</p>
                            <div class="flex">
                                <input type="email" placeholder="La tua email" 
                                       class="flex-1 px-3 py-2 bg-white/20 border border-white/30 rounded-l-lg text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                <button class="px-4 py-2 bg-gradient-to-r from-purple-600 to-pink-600 rounded-r-lg hover:from-purple-700 hover:to-pink-700 transition-all duration-200">
                                    Iscriviti
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Quick Links -->
                    <div>
                        <h4 class="text-lg font-semibold mb-4">🎯 Mercati</h4>
                        <ul class="space-y-2">
                            <li><a href="/markets/crypto" class="text-gray-300 hover:text-white transition-colors">Crypto</a></li>
                            <li><a href="/markets/sports" class="text-gray-300 hover:text-white transition-colors">Sport</a></li>
                            <li><a href="/markets/politics" class="text-gray-300 hover:text-white transition-colors">Politica</a></li>
                            <li><a href="/markets/tech" class="text-gray-300 hover:text-white transition-colors">Tech</a></li>
                            <li><a href="/markets/finance" class="text-gray-300 hover:text-white transition-colors">Finanza</a></li>
                        </ul>
                    </div>
                    
                    <!-- Support -->
                    <div>
                        <h4 class="text-lg font-semibold mb-4">🛟 Supporto</h4>
                        <ul class="space-y-2">
                            <li><a href="/help" class="text-gray-300 hover:text-white transition-colors">Centro Aiuto</a></li>
                            <li><a href="/tutorial" class="text-gray-300 hover:text-white transition-colors">Come Funziona</a></li>
                            <li><a href="/faq" class="text-gray-300 hover:text-white transition-colors">FAQ</a></li>
                            <li><a href="/contact" class="text-gray-300 hover:text-white transition-colors">Contatti</a></li>
                            <li><a href="/terms" class="text-gray-300 hover:text-white transition-colors">Termini</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        
        @if($show_media && count($media_mentions) > 0)
            <!-- Media Mentions -->
            <div class="border-t border-white/10 py-8">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-6">
                        <h4 class="text-lg font-semibold text-white mb-2">📰 Citati da</h4>
                    </div>
                    <div class="flex flex-wrap justify-center items-center space-x-8 opacity-60">
                        @foreach($media_mentions as $media)
                            <div class="text-white font-semibold text-lg">{{ $media }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
        
        @if($show_social && count($social_stats) > 0)
            <!-- Social Stats & Links -->
            <div class="border-t border-white/10 py-8">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        
                        <!-- Social Links -->
                        <div class="flex items-center space-x-6 mb-4 md:mb-0">
                            <span class="text-gray-300">Seguici:</span>
                            
                            <a href="#" class="flex items-center space-x-2 bg-blue-600 hover:bg-blue-700 px-3 py-2 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                                </svg>
                                <span class="text-sm">{{ $social_stats['twitter'] ?? '25K' }}</span>
                            </a>
                            
                            <a href="#" class="flex items-center space-x-2 bg-blue-500 hover:bg-blue-600 px-3 py-2 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                                </svg>
                                <span class="text-sm">{{ $social_stats['telegram'] ?? '12K' }}</span>
                            </a>
                            
                            <a href="#" class="flex items-center space-x-2 bg-indigo-600 hover:bg-indigo-700 px-3 py-2 rounded-lg transition-colors">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M20.317 4.37a19.791 19.791 0 0 0-4.885-1.515.074.074 0 0 0-.079.037c-.21.375-.444.864-.608 1.25a18.27 18.27 0 0 0-5.487 0 12.64 12.64 0 0 0-.617-1.25.077.077 0 0 0-.079-.037A19.736 19.736 0 0 0 3.677 4.37a.07.07 0 0 0-.032.027C.533 9.046-.32 13.58.099 18.057a.082.082 0 0 0 .031.057 19.9 19.9 0 0 0 5.993 3.03.078.078 0 0 0 .084-.028 14.09 14.09 0 0 0 1.226-1.994.076.076 0 0 0-.041-.106 13.107 13.107 0 0 1-1.872-.892.077.077 0 0 1-.008-.128 10.2 10.2 0 0 0 .372-.292.074.074 0 0 1 .077-.01c3.928 1.793 8.18 1.793 12.062 0a.074.074 0 0 1 .078.01c.12.098.246.198.373.292a.077.077 0 0 1-.006.127 12.299 12.299 0 0 1-1.873.892.077.077 0 0 0-.041.107c.36.698.772 1.362 1.225 1.993a.076.076 0 0 0 .084.028 19.839 19.839 0 0 0 6.002-3.03.077.077 0 0 0 .032-.054c.5-5.177-.838-9.674-3.549-13.66a.061.061 0 0 0-.031-.03zM8.02 15.33c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.956-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.956 2.418-2.157 2.418zm7.975 0c-1.183 0-2.157-1.085-2.157-2.419 0-1.333.955-2.419 2.157-2.419 1.21 0 2.176 1.096 2.157 2.42 0 1.333-.946 2.418-2.157 2.418z"/>
                                </svg>
                                <span class="text-sm">{{ $social_stats['discord'] ?? '8K' }}</span>
                            </a>
                        </div>
                        
                        <!-- Copyright -->
                        <div class="text-center md:text-right">
                            <p class="text-gray-400 text-sm">
                                © {{ date('Y') }} PredictFuture. Tutti i diritti riservati.
                            </p>
                            <p class="text-gray-500 text-xs mt-1">
                                Piattaforma sicura e regolamentata
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</footer>

@push('styles')
<style>
    @keyframes scroll {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    
    .animate-scroll {
        animation: scroll 30s linear infinite;
    }
    
    .animation-delay-2000 {
        animation-delay: 2s;
    }
</style>
@endpush