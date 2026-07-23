<?php

use function Livewire\Volt\{state, computed, mount, on};

state([
    'title' => '💰 Storie di Successo Reali',
    'subtitle' => 'Migliaia di utenti stanno già guadagnando',
    'testimonials' => [],
    'show_earnings' => true,
    'show_stats' => true,
    'auto_scroll' => true
]);

mount(function ($data = []) {
    foreach ($data as $key => $value) {
        if (property_exists($this, $key)) {
            $this->$key = $value;
        }
    }
    
    if (empty($this->testimonials)) {
        $this->testimonials = $this->getDefaultTestimonials();
    }
});

$getDefaultTestimonials = function() {
    return [
        [
            'id' => 1,
            'name' => 'Marco Rossi',
            'username' => 'crypto_king_23',
            'avatar' => null,
            'location' => 'Milano, IT',
            'earnings' => '€2,847',
            'period' => 'questo mese',
            'testimonial' => 'Non ci credevo all\'inizio, ma in 3 settimane ho guadagnato più di quanto guadagno in un mese di lavoro! Le previsioni su Bitcoin mi hanno cambiato la vita.',
            'rating' => 5,
            'verified' => true,
            'prediction_accuracy' => 89,
            'total_predictions' => 47,
            'join_date' => '2024-01-15',
            'biggest_win' => '€1,200',
            'favorite_market' => 'Crypto'
        ],
        [
            'id' => 2,
            'name' => 'Sofia Bianchi',
            'username' => 'sofia_oracle',
            'avatar' => null,
            'location' => 'Roma, IT',
            'earnings' => '€1,923',
            'period' => 'questa settimana',
            'testimonial' => 'Fantastico! Ho iniziato con €50 e ora ho un portafoglio di oltre €2000. Le elezioni USA sono state la mia miniera d\'oro!',
            'rating' => 5,
            'verified' => true,
            'prediction_accuracy' => 92,
            'total_predictions' => 34,
            'join_date' => '2024-02-03',
            'biggest_win' => '€850',
            'favorite_market' => 'Politica'
        ],
        [
            'id' => 3,
            'name' => 'Luca Verdi',
            'username' => 'luca_analyst',
            'avatar' => null,
            'location' => 'Torino, IT',
            'earnings' => '€3,156',
            'period' => 'ultimo mese',
            'testimonial' => 'Come analista finanziario, apprezzo la qualità dei mercati. Ho triplicato il mio investimento iniziale in sole 6 settimane!',
            'rating' => 5,
            'verified' => true,
            'prediction_accuracy' => 87,
            'total_predictions' => 62,
            'join_date' => '2023-12-10',
            'biggest_win' => '€1,500',
            'favorite_market' => 'Finanza'
        ],
        [
            'id' => 4,
            'name' => 'Anna Ferrari',
            'username' => 'anna_predictor',
            'avatar' => null,
            'location' => 'Napoli, IT',
            'earnings' => '€987',
            'period' => 'questa settimana',
            'testimonial' => 'Incredibile! In soli 10 giorni ho già recuperato il mio investimento iniziale e sto continuando a guadagnare ogni giorno.',
            'rating' => 5,
            'verified' => true,
            'prediction_accuracy' => 84,
            'total_predictions' => 28,
            'join_date' => '2024-03-01',
            'biggest_win' => '€450',
            'favorite_market' => 'Sport'
        ]
    ];
};

?>

<div class="py-16 bg-gradient-to-br from-green-50 to-emerald-50 dark:from-gray-900 dark:to-green-900/20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-16">
            <h2 class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white mb-4">
                {{ $title }}
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto mb-8">
                {{ $subtitle }}
            </p>
            
            @if($show_stats)
                <!-- Success Stats -->
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 max-w-4xl mx-auto">
                    @php
                        $totalUsers = \Illuminate\Support\Facades\DB::connection('user')->table('users')->count();
                        $totalPredicts = \Illuminate\Support\Facades\DB::connection('blog')->table('articles')
                            ->where('type', 'Modules\Predict\Models\Predict')
                            ->whereNull('deleted_at')
                            ->count();
                        $totalVolume = (int) \Illuminate\Support\Facades\DB::connection('predict')->table('transactions')
                            ->sum('credits') ?: 0;
                        $formattedVolume = $totalVolume >= 1000000
                            ? number_format($totalVolume / 1000000, 1).'M'
                            : ($totalVolume >= 1000
                                ? number_format($totalVolume / 1000, 0).'K'
                                : number_format($totalVolume));
                    @endphp
                    <div class="text-center">
                        <div class="text-3xl font-black text-green-600 dark:text-green-400">{{ $formattedVolume }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Crediti in gioco</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-black text-blue-600 dark:text-blue-400">{{ number_format($totalUsers) }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Utenti Registrati</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-black text-purple-600 dark:text-purple-400">{{ $totalPredicts }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Mercati Attivi</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-black text-orange-600 dark:text-orange-400">{{ $totalPredicts > 0 ? number_format($totalUsers / max($totalPredicts, 1), 1) : '0' }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">Utenti/Mercato</div>
                    </div>
                </div>
            @endif
        </div>
        
        <!-- Testimonials Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-12">
            @foreach($testimonials as $testimonial)
                <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700 p-8 relative overflow-hidden transform hover:scale-105 transition-all duration-300">
                    
                    <!-- Background Pattern -->
                    <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-gradient-to-br from-green-400/20 to-emerald-500/20 rounded-full blur-2xl"></div>
                    
                    <!-- Earnings Badge -->
                    @if($show_earnings && isset($testimonial['earnings']))
                        <div class="absolute top-4 right-4">
                            <div class="bg-gradient-to-r from-green-500 to-emerald-600 text-white px-3 py-1 rounded-full text-sm font-bold">
                                {{ $testimonial['earnings'] }} {{ $testimonial['period'] }}
                            </div>
                        </div>
                    @endif
                    
                    <!-- User Info -->
                    <div class="flex items-start mb-6">
                        <img class="h-16 w-16 rounded-full border-4 border-green-200 dark:border-green-700" 
                             src="{{ $testimonial['avatar'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($testimonial['name']) . '&color=10B981&background=D1FAE5' }}" 
                             alt="{{ $testimonial['name'] }}">
                        
                        <div class="ml-4 flex-1">
                            <div class="flex items-center">
                                <h4 class="text-lg font-bold text-gray-900 dark:text-white">{{ $testimonial['name'] }}</h4>
                                @if($testimonial['verified'])
                                    <svg class="w-5 h-5 text-blue-500 ml-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                    </svg>
                                @endif
                            </div>
                            <p class="text-sm text-gray-500 dark:text-gray-400">@{{ $testimonial['username'] }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">📍 {{ $testimonial['location'] }}</p>
                            
                            <!-- Rating Stars -->
                            <div class="flex items-center mt-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $testimonial['rating'] ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                            </div>
                        </div>
                    </div>
                    
                    <!-- Testimonial Text -->
                    <blockquote class="text-gray-700 dark:text-gray-300 mb-6 text-lg leading-relaxed">
                        "{{ $testimonial['testimonial'] }}"
                    </blockquote>
                    
                    <!-- Stats -->
                    <div class="grid grid-cols-2 gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="text-center">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $testimonial['prediction_accuracy'] }}%</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Precisione</div>
                        </div>
                        <div class="text-center">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $testimonial['total_predictions'] }}</div>
                            <div class="text-xs text-gray-500 dark:text-gray-400">Previsioni</div>
                        </div>
                    </div>
                    
                    <!-- Additional Info -->
                    <div class="mt-4 flex flex-wrap gap-2">
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200">
                            💎 {{ $testimonial['favorite_market'] }}
                        </span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/50 dark:text-orange-200">
                            🏆 Max: {{ $testimonial['biggest_win'] }}
                        </span>
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                            📅 Dal {{ \Carbon\Carbon::parse($testimonial['join_date'])->format('M Y') }}
                        </span>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- CTA Section -->
        <div class="text-center bg-gradient-to-r from-green-600 to-emerald-600 rounded-2xl p-8 text-white">
            <h3 class="text-2xl font-bold mb-4">Pronto a Diventare il Prossimo Vincitore?</h3>
            <p class="text-green-100 mb-6 max-w-2xl mx-auto">
                Unisciti a migliaia di utenti che stanno già guadagnando con le loro previsioni. Inizia gratis oggi stesso!
            </p>
            <div class="flex flex-col sm:flex-row justify-center items-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="/register" 
                   class="inline-flex items-center px-8 py-3 text-lg font-bold text-green-600 bg-white rounded-full hover:bg-gray-100 transform hover:scale-105 transition-all duration-300 shadow-lg">
                    🚀 Inizia Gratis Ora
                </a>
                <a href="/demo" 
                   class="inline-flex items-center px-6 py-3 text-lg font-semibold text-white border-2 border-white/30 rounded-full hover:bg-white/10 transition-all duration-300">
                    📊 Vedi Demo
                </a>
            </div>
        </div>
    </div>
</div>