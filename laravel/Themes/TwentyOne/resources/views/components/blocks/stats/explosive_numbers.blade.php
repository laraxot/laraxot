<?php

use function Livewire\Volt\{state, computed, mount, on};

state([
    'title' => '📊 Numeri che Parlano Chiaro',
    'subtitle' => 'I risultati della nostra community',
    'stats' => [],
    'show_live_counter' => true,
    'show_growth_indicators' => true,
    'counter_speed' => 50 // milliseconds
]);

mount(function ($data = []) {
    foreach ($data as $key => $value) {
        if (property_exists($this, $key)) {
            $this->$key = $value;
        }
    }
    
    if (empty($this->stats)) {
        $this->stats = $this->getDefaultStats();
    }
});

$getDefaultStats = function() {
    return [
        [
            'id' => 'total_earnings',
            'label' => 'Guadagni Totali',
            'value' => 2400000,
            'display_value' => '€2.4M+',
            'icon' => '💰',
            'color' => 'green',
            'growth' => '+127%',
            'growth_period' => 'questo mese',
            'description' => 'Pagati agli utenti',
            'animated' => true
        ],
        [
            'id' => 'active_users',
            'label' => 'Utenti Attivi',
            'value' => 12847,
            'display_value' => '12.8K+',
            'icon' => '👥',
            'color' => 'blue',
            'growth' => '+89%',
            'growth_period' => 'ultima settimana',
            'description' => 'Predictor attivi',
            'animated' => true
        ],
        [
            'id' => 'success_rate',
            'label' => 'Tasso di Successo',
            'value' => 89,
            'display_value' => '89%',
            'icon' => '🎯',
            'color' => 'purple',
            'growth' => '+12%',
            'growth_period' => 'vs media settore',
            'description' => 'Previsioni vincenti',
            'animated' => true
        ],
        [
            'id' => 'markets_available',
            'label' => 'Mercati Disponibili',
            'value' => 247,
            'display_value' => '247+',
            'icon' => '📈',
            'color' => 'orange',
            'growth' => '+34%',
            'growth_period' => 'questo mese',
            'description' => 'Categorie diverse',
            'animated' => true
        ],
        [
            'id' => 'daily_predictions',
            'label' => 'Previsioni Giornaliere',
            'value' => 1547,
            'display_value' => '1.5K+',
            'icon' => '⚡',
            'color' => 'yellow',
            'growth' => '+156%',
            'growth_period' => 'vs ieri',
            'description' => 'Ogni giorno',
            'animated' => true
        ],
        [
            'id' => 'avg_rating',
            'label' => 'Rating Medio',
            'value' => 4.9,
            'display_value' => '4.9★',
            'icon' => '⭐',
            'color' => 'pink',
            'growth' => '+0.3',
            'growth_period' => 'ultimo mese',
            'description' => 'Soddisfazione utenti',
            'animated' => false
        ]
    ];
};

$getColorClasses = function($color) {
    $colors = [
        'green' => [
            'bg' => 'from-green-500 to-emerald-600',
            'text' => 'text-green-600',
            'border' => 'border-green-200',
            'icon_bg' => 'bg-green-100',
            'growth' => 'text-green-600 bg-green-100'
        ],
        'blue' => [
            'bg' => 'from-blue-500 to-cyan-600',
            'text' => 'text-blue-600',
            'border' => 'border-blue-200',
            'icon_bg' => 'bg-blue-100',
            'growth' => 'text-blue-600 bg-blue-100'
        ],
        'purple' => [
            'bg' => 'from-purple-500 to-violet-600',
            'text' => 'text-purple-600',
            'border' => 'border-purple-200',
            'icon_bg' => 'bg-purple-100',
            'growth' => 'text-purple-600 bg-purple-100'
        ],
        'orange' => [
            'bg' => 'from-orange-500 to-red-600',
            'text' => 'text-orange-600',
            'border' => 'border-orange-200',
            'icon_bg' => 'bg-orange-100',
            'growth' => 'text-orange-600 bg-orange-100'
        ],
        'yellow' => [
            'bg' => 'from-yellow-500 to-orange-500',
            'text' => 'text-yellow-600',
            'border' => 'border-yellow-200',
            'icon_bg' => 'bg-yellow-100',
            'growth' => 'text-yellow-600 bg-yellow-100'
        ],
        'pink' => [
            'bg' => 'from-pink-500 to-rose-600',
            'text' => 'text-pink-600',
            'border' => 'border-pink-200',
            'icon_bg' => 'bg-pink-100',
            'growth' => 'text-pink-600 bg-pink-100'
        ]
    ];
    
    return $colors[$color] ?? $colors['blue'];
};

?>

<div class="py-16 bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-900 dark:to-gray-800 relative overflow-hidden">
    
    <!-- Background Effects -->
    <div class="absolute inset-0">
        <div class="absolute top-1/4 left-1/4 w-64 h-64 bg-purple-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-blue-300 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-pulse animation-delay-2000"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        
        <!-- Header -->
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white mb-4">
                {{ $title }}
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                {{ $subtitle }}
            </p>
        </div>
        
        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-12">
            @foreach($stats as $stat)
                @php
                    $colors = $getColorClasses($stat['color']);
                @endphp
                
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border-2 {{ $colors['border'] }} dark:border-gray-700 p-8 relative overflow-hidden transform hover:scale-105 transition-all duration-300 hover:shadow-2xl">
                    
                    <!-- Background Gradient -->
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-gradient-to-br {{ $colors['bg'] }} rounded-full opacity-10 blur-2xl"></div>
                    
                    <!-- Icon -->
                    <div class="flex items-center justify-between mb-6">
                        <div class="w-16 h-16 {{ $colors['icon_bg'] }} dark:bg-gray-700 rounded-2xl flex items-center justify-center text-2xl">
                            {{ $stat['icon'] }}
                        </div>
                        
                        @if($show_growth_indicators && isset($stat['growth']))
                            <div class="text-right">
                                <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-bold {{ $colors['growth'] }} dark:bg-gray-700 dark:text-white">
                                    ↗ {{ $stat['growth'] }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    {{ $stat['growth_period'] }}
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Value -->
                    <div class="mb-4">
                        @if($show_live_counter && $stat['animated'])
                            <div class="text-4xl font-black {{ $colors['text'] }} dark:text-white mb-2" 
                                 x-data="{ 
                                     count: 0, 
                                     target: {{ $stat['value'] }},
                                     display: '{{ $stat['display_value'] }}'
                                 }"
                                 x-init="
                                     let increment = target / 100;
                                     let timer = setInterval(() => {
                                         count += increment;
                                         if (count >= target) {
                                             count = target;
                                             clearInterval(timer);
                                         }
                                     }, {{ $counter_speed }});
                                 ">
                                <span x-text="display"></span>
                            </div>
                        @else
                            <div class="text-4xl font-black {{ $colors['text'] }} dark:text-white mb-2">
                                {{ $stat['display_value'] }}
                            </div>
                        @endif
                        
                        <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">
                            {{ $stat['label'] }}
                        </h3>
                        
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $stat['description'] }}
                        </p>
                    </div>
                    
                    <!-- Progress Bar (for percentage stats) -->
                    @if(str_contains($stat['display_value'], '%'))
                        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 mb-4">
                            <div class="bg-gradient-to-r {{ $colors['bg'] }} h-2 rounded-full transition-all duration-1000 ease-out" 
                                 style="width: {{ $stat['value'] }}%"
                                 x-data
                                 x-init="setTimeout(() => $el.style.width = '{{ $stat['value'] }}%', 500)">
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>
        
        <!-- Live Activity Feed -->
        @if($show_live_counter)
            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-8 mb-12">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-2xl font-bold text-gray-900 dark:text-white flex items-center">
                        <div class="w-4 h-4 bg-green-400 rounded-full animate-pulse mr-3"></div>
                        🔥 Attività in Tempo Reale
                    </h3>
                    <div class="text-sm text-gray-500 dark:text-gray-400">
                        Aggiornato ogni secondo
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="text-center p-4 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-xl">
                        <div class="text-3xl font-black text-green-600 dark:text-green-400" 
                             x-data="{ count: 0 }"
                             x-init="setInterval(() => count = Math.floor(Math.random() * 50) + 1200, 2000)">
                            €<span x-text="count.toLocaleString()"></span>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Guadagnati ora</div>
                    </div>
                    
                    <div class="text-center p-4 bg-gradient-to-br from-blue-50 to-cyan-50 dark:from-blue-900/20 dark:to-cyan-900/20 rounded-xl">
                        <div class="text-3xl font-black text-blue-600 dark:text-blue-400"
                             x-data="{ count: 0 }"
                             x-init="setInterval(() => count = Math.floor(Math.random() * 20) + 80, 3000)">
                            <span x-text="count"></span>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Utenti online</div>
                    </div>
                    
                    <div class="text-center p-4 bg-gradient-to-br from-purple-50 to-violet-50 dark:from-purple-900/20 dark:to-violet-900/20 rounded-xl">
                        <div class="text-3xl font-black text-purple-600 dark:text-purple-400"
                             x-data="{ count: 0 }"
                             x-init="setInterval(() => count = Math.floor(Math.random() * 10) + 25, 1500)">
                            <span x-text="count"></span>
                        </div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Previsioni/min</div>
                    </div>
                </div>
            </div>
        @endif
        
        <!-- Call to Action -->
        <div class="text-center bg-gradient-to-r from-purple-600 to-pink-600 rounded-2xl p-12 text-white">
            <h3 class="text-3xl font-bold mb-4">Pronto a Far Parte di Questi Numeri?</h3>
            <p class="text-purple-100 mb-8 max-w-2xl mx-auto text-lg">
                Unisciti alla community di predictor più vincente d'Italia. I numeri parlano chiaro: qui si guadagna davvero!
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-6">
                <a href="/register" 
                   class="inline-flex items-center px-8 py-4 text-lg font-bold text-purple-600 bg-white rounded-full hover:bg-gray-100 transform hover:scale-105 transition-all duration-300 shadow-lg">
                    🚀 Inizia Gratis
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                    </svg>
                </a>
                <a href="/demo" 
                   class="inline-flex items-center px-6 py-4 text-lg font-semibold text-white border-2 border-white/30 rounded-full hover:bg-white/10 transition-all duration-300">
                    📊 Vedi Demo
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .animation-delay-2000 {
        animation-delay: 2s;
    }
</style>
@endpush