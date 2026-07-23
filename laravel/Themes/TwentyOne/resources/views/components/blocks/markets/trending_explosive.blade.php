<?php

use function Livewire\Volt\{state, computed, mount, on};
use Modules\Predict\Models\Predict;

state([
    'title' => '🔥 Mercati in Tendenza',
    'subtitle' => 'I mercati più caldi del momento',
    'markets' => [],
    'show_volume' => true,
    'show_participants' => true,
    'limit' => 6
]);

mount(function ($data = []) {
    foreach ($data as $key => $value) {
        if (property_exists($this, $key)) {
            $this->$key = $value;
        }
    }
    
    // Load trending markets if not provided
    if (empty($this->markets)) {
        $this->loadTrendingMarkets();
    }
});

$loadTrendingMarkets = function() {
    try {
        $this->markets = Predict::with(['ratings'])
            ->where('is_active', true)
            ->orderBy('created_at', 'desc')
            ->limit($this->limit)
            ->get()
            ->map(function ($predict) {
                $ratings = $predict->getArrayRatingsWithImage();
                $totalVolume = $predict->ratings->sum('volume') ?? 0;
                $participants = $predict->ratings->count() ?? 0;
                
                return [
                    'id' => $predict->id,
                    'title' => $predict->title,
                    'description' => $predict->subtitle ?? '',
                    'image' => $predict->image ?? null,
                    'volume' => $totalVolume,
                    'participants' => $participants,
                    'options' => $ratings,
                    'trending_score' => rand(85, 98),
                    'change_24h' => rand(-15, 25),
                    'closes_at' => $predict->closes_at ?? now()->addDays(7),
                    'slug' => $predict->slug ?? 'market-' . $predict->id
                ];
            })
            ->toArray();
    } catch (\Exception $e) {
        // Fallback to demo data
        $this->markets = $this->getDemoMarkets();
    }
};

$getDemoMarkets = function() {
    return [
        [
            'id' => 1,
            'title' => 'Bitcoin raggiungerà $100,000 entro Natale?',
            'description' => 'Il prezzo di Bitcoin supererà i $100,000 entro il 25 dicembre 2024',
            'image' => null,
            'volume' => 125000,
            'participants' => 1247,
            'options' => [
                ['id' => 1, 'title' => 'Sì', 'percentage' => 67, 'color' => 'green'],
                ['id' => 2, 'title' => 'No', 'percentage' => 33, 'color' => 'red']
            ],
            'trending_score' => 95,
            'change_24h' => 12,
            'closes_at' => '2024-12-25',
            'slug' => 'bitcoin-100k-natale'
        ],
        [
            'id' => 2,
            'title' => 'Chi vincerà le elezioni USA 2024?',
            'description' => 'Previsioni per le elezioni presidenziali americane',
            'image' => null,
            'volume' => 89000,
            'participants' => 892,
            'options' => [
                ['id' => 1, 'title' => 'Democratici', 'percentage' => 52, 'color' => 'blue'],
                ['id' => 2, 'title' => 'Repubblicani', 'percentage' => 48, 'color' => 'red']
            ],
            'trending_score' => 88,
            'change_24h' => -3,
            'closes_at' => '2024-11-05',
            'slug' => 'elezioni-usa-2024'
        ],
        [
            'id' => 3,
            'title' => 'Tesla supererà $300 per azione?',
            'description' => 'Il prezzo delle azioni Tesla raggiungerà $300 entro fine anno',
            'image' => null,
            'volume' => 67000,
            'participants' => 543,
            'options' => [
                ['id' => 1, 'title' => 'Sì', 'percentage' => 41, 'color' => 'green'],
                ['id' => 2, 'title' => 'No', 'percentage' => 59, 'color' => 'red']
            ],
            'trending_score' => 82,
            'change_24h' => 8,
            'closes_at' => '2024-12-31',
            'slug' => 'tesla-300-dollari'
        ]
    ];
};

?>

<div class="py-12 bg-gradient-to-br from-gray-50 to-blue-50 dark:from-gray-900 dark:to-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Header -->
        <div class="text-center mb-12">
            <h2 class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white mb-4">
                {{ $title }}
            </h2>
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">
                {{ $subtitle }}
            </p>
        </div>
        
        <!-- Markets Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($markets as $market)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
                    
                    <!-- Market Header -->
                    <div class="p-6 pb-4">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-2 line-clamp-2">
                                    {{ $market['title'] }}
                                </h3>
                                @if($market['description'])
                                    <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                        {{ $market['description'] }}
                                    </p>
                                @endif
                            </div>
                            
                            <!-- Trending Badge -->
                            <div class="ml-3 flex flex-col items-end">
                                <div class="flex items-center px-2 py-1 rounded-full text-xs font-bold {{ $market['change_24h'] >= 0 ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200' : 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-200' }}">
                                    @if($market['change_24h'] >= 0)
                                        ↗ +{{ $market['change_24h'] }}%
                                    @else
                                        ↘ {{ $market['change_24h'] }}%
                                    @endif
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    🔥 {{ $market['trending_score'] }}
                                </div>
                            </div>
                        </div>
                        
                        <!-- Market Stats -->
                        <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
                            @if($show_volume)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M8.433 7.418c.155-.103.346-.196.567-.267v1.698a2.305 2.305 0 01-.567-.267C8.07 8.34 8 8.114 8 8c0-.114.07-.34.433-.582zM11 12.849v-1.698c.22.071.412.164.567.267.364.243.433.468.433.582 0 .114-.07.34-.433.582a2.305 2.305 0 01-.567.267z"></path>
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-13a1 1 0 10-2 0v.092a4.535 4.535 0 00-1.676.662C6.602 6.234 6 7.009 6 8c0 .99.602 1.765 1.324 2.246.48.32 1.054.545 1.676.662v1.941c-.391-.127-.68-.317-.843-.504a1 1 0 10-1.51 1.31c.562.649 1.413 1.076 2.353 1.253V15a1 1 0 102 0v-.092a4.535 4.535 0 001.676-.662C13.398 13.766 14 12.991 14 12c0-.99-.602-1.765-1.324-2.246A4.535 4.535 0 0011 9.092V7.151c.391.127.68.317.843.504a1 1 0 101.511-1.31c-.563-.649-1.413-1.076-2.354-1.253V5z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ number_format($market['volume']) }} crediti
                                </div>
                            @endif
                            
                            @if($show_participants)
                                <div class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"></path>
                                    </svg>
                                    {{ number_format($market['participants']) }} partecipanti
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <!-- Market Options -->
                    <div class="px-6 pb-4">
                        <div class="space-y-2">
                            @foreach($market['options'] as $option)
                                <div class="flex items-center justify-between p-3 rounded-lg bg-gray-50 dark:bg-gray-700/50 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors cursor-pointer">
                                    <div class="flex items-center">
                                        <div class="w-3 h-3 rounded-full mr-3 {{ $option['color'] === 'green' ? 'bg-green-500' : ($option['color'] === 'red' ? 'bg-red-500' : 'bg-blue-500') }}"></div>
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $option['title'] }}</span>
                                    </div>
                                    <div class="flex items-center">
                                        <span class="font-bold text-gray-900 dark:text-white">{{ $option['percentage'] }}%</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    
                    <!-- Market Footer -->
                    <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/30 border-t border-gray-200 dark:border-gray-600">
                        <div class="flex items-center justify-between">
                            <div class="text-sm text-gray-500 dark:text-gray-400">
                                Chiude: {{ \Carbon\Carbon::parse($market['closes_at'])->format('d/m/Y') }}
                            </div>
                            <a href="/markets/{{ $market['slug'] }}" 
                               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-purple-600 rounded-lg hover:from-blue-700 hover:to-purple-700 transition-all duration-200">
                                Partecipa
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        
        <!-- View All Button -->
        <div class="text-center mt-12">
            <a href="/markets" 
               class="inline-flex items-center px-8 py-3 text-lg font-semibold text-white bg-gradient-to-r from-purple-600 to-pink-600 rounded-full hover:from-purple-700 hover:to-pink-700 transform hover:scale-105 transition-all duration-300 shadow-lg">
                Vedi Tutti i Mercati
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                </svg>
            </a>
        </div>
    </div>
</div>

@push('styles')
<style>
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
</style>
@endpush