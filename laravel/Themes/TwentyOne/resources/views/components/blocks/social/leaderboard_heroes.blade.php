@php
/**
 * Leaderboard Heroes Component
 *
 * Displays top performers in prediction markets with hero-style presentation
 * Shows top 3 users with special styling and additional top performers
 * 
 * @var array $heroes Top 3 performers with special hero styling
 * @var array $topPerformers Additional top performers to display
 * @var string $title Component title
 * @var string $period Time period for the leaderboard (weekly, monthly, all-time)
 * @var int $limit Number of additional performers to show
 */

$heroes = $heroes ?? [];
$topPerformers = $topPerformers ?? [];
$title = $title ?? 'Eroi della Classifica';
$period = $period ?? 'settimanale';
$limit = $limit ?? 5;

// Default heroes data if none provided
if (empty($heroes)) {
    $heroes = [
        [
            'id' => 1,
            'name' => 'Marco Rossi',
            'username' => 'marco_predictor',
            'avatar' => null,
            'points' => 2850,
            'accuracy' => 89,
            'streak' => 12,
            'badge' => 'Campione',
            'position' => 1
        ],
        [
            'id' => 2,
            'name' => 'Sofia Bianchi',
            'username' => 'sofia_oracle',
            'avatar' => null,
            'points' => 2720,
            'accuracy' => 87,
            'streak' => 8,
            'badge' => 'Esperta',
            'position' => 2
        ],
        [
            'id' => 3,
            'name' => 'Luca Verdi',
            'username' => 'luca_analyst',
            'avatar' => null,
            'points' => 2590,
            'accuracy' => 85,
            'streak' => 6,
            'badge' => 'Analista',
            'position' => 3
        ]
    ];
}

// Default top performers if none provided
if (empty($topPerformers)) {
    $topPerformers = [
        ['name' => 'Anna Ferrari', 'points' => 2480, 'accuracy' => 83, 'position' => 4],
        ['name' => 'Giuseppe Neri', 'points' => 2350, 'accuracy' => 81, 'position' => 5],
        ['name' => 'Chiara Blu', 'points' => 2220, 'accuracy' => 79, 'position' => 6],
        ['name' => 'Roberto Gialli', 'points' => 2100, 'accuracy' => 77, 'position' => 7],
        ['name' => 'Elena Viola', 'points' => 1980, 'accuracy' => 75, 'position' => 8],
    ];
}

$podiumColors = [
    1 => ['bg' => 'from-yellow-400 to-yellow-600', 'text' => 'text-yellow-900', 'border' => 'border-yellow-300', 'icon' => '👑'],
    2 => ['bg' => 'from-gray-300 to-gray-500', 'text' => 'text-gray-900', 'border' => 'border-gray-300', 'icon' => '🥈'],
    3 => ['bg' => 'from-amber-600 to-amber-800', 'text' => 'text-amber-100', 'border' => 'border-amber-400', 'icon' => '🥉']
];
@endphp

<div class="relative overflow-hidden bg-gradient-to-br from-blue-50 to-indigo-50 dark:from-gray-800 dark:to-gray-900 rounded-xl shadow-lg border border-blue-100 dark:border-gray-700">
    <div class="absolute top-0 right-0 -mt-8 -mr-8 w-40 h-40 bg-gradient-to-bl from-blue-400/20 to-indigo-500/20 rounded-full blur-3xl"></div>
    <div class="absolute bottom-0 left-0 -mb-8 -ml-8 w-40 h-40 bg-gradient-to-tr from-blue-400/20 to-indigo-500/20 rounded-full blur-3xl"></div>
    
    <div class="px-4 py-5 sm:p-6 relative z-10">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <div class="flex-shrink-0 bg-gradient-to-r from-blue-600 to-indigo-600 p-2 rounded-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 text-white">
                        <path fill-rule="evenodd" d="M5.166 2.621v.858c-1.035.148-2.059.33-3.071.543a.75.75 0 00-.584.859 6.753 6.753 0 006.138 5.6 6.73 6.73 0 002.743 1.346A6.707 6.707 0 019.279 15H8.54c-1.036 0-1.875.84-1.875 1.875V19.5h-.75a2.25 2.25 0 00-2.25 2.25c0 .414.336.75.75.75h15a.75.75 0 00.75-.75 2.25 2.25 0 00-2.25-2.25H16.5v-2.625c0-1.036-.84-1.875-1.875-1.875h-.739a6.706 6.706 0 01-1.112-3.173 6.73 6.73 0 002.743-1.347 6.753 6.753 0 006.139-5.6.75.75 0 00-.585-.858 47.077 47.077 0 00-3.07-.543V2.62a.75.75 0 00-.658-.744 49.22 49.22 0 00-6.093-.377c-2.063 0-4.096.128-6.093.377a.75.75 0 00-.657.744z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $title }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Classifica {{ $period }}</p>
                </div>
            </div>
            <div class="text-right">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-200">
                    🏆 Top Performers
                </span>
            </div>
        </div>

        <!-- Heroes Podium -->
        <div class="mb-8">
            <div class="flex justify-center items-end space-x-4 mb-6">
                @foreach($heroes as $hero)
                    @php
                        $colors = $podiumColors[$hero['position']] ?? $podiumColors[3];
                        $heightClass = $hero['position'] === 1 ? 'h-24' : ($hero['position'] === 2 ? 'h-20' : 'h-16');
                    @endphp
                    
                    <div class="flex flex-col items-center {{ $hero['position'] === 1 ? 'order-2' : ($hero['position'] === 2 ? 'order-1' : 'order-3') }}">
                        <!-- Avatar -->
                        <div class="relative mb-2">
                            <img class="h-16 w-16 rounded-full border-4 {{ $colors['border'] }} shadow-lg" 
                                 src="{{ $hero['avatar'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($hero['name']) . '&color=7F9CF5&background=EBF4FF' }}" 
                                 alt="{{ $hero['name'] }}">
                            <div class="absolute -top-2 -right-2 text-2xl">{{ $colors['icon'] }}</div>
                        </div>
                        
                        <!-- Podium -->
                        <div class="bg-gradient-to-t {{ $colors['bg'] }} {{ $heightClass }} w-20 rounded-t-lg flex flex-col justify-center items-center text-center shadow-lg">
                            <div class="text-lg font-bold {{ $colors['text'] }}">{{ $hero['position'] }}</div>
                            <div class="text-xs font-medium {{ $colors['text'] }} opacity-90">{{ $hero['points'] }}pt</div>
                        </div>
                        
                        <!-- Hero Info -->
                        <div class="mt-3 text-center">
                            <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $hero['name'] }}</p>
                            <p class="text-xs text-gray-500 dark:text-gray-400">@{{ $hero['username'] ?? strtolower(str_replace(' ', '_', $hero['name'])) }}</p>
                            <div class="flex items-center justify-center space-x-2 mt-1">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-200">
                                    {{ $hero['accuracy'] }}% precisione
                                </span>
                                @if(isset($hero['streak']) && $hero['streak'] > 0)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/50 dark:text-orange-200">
                                        🔥 {{ $hero['streak'] }}
                                    </span>
                                @endif
                            </div>
                            @if(isset($hero['badge']))
                                <div class="mt-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-200">
                                        {{ $hero['badge'] }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Additional Top Performers -->
        @if(count($topPerformers) > 0)
            <div class="border-t border-gray-200 dark:border-gray-700 pt-4">
                <h4 class="text-sm font-medium text-gray-900 dark:text-white mb-3">{{ __('Altri Top Performers') }}</h4>
                <div class="space-y-2">
                    @foreach(array_slice($topPerformers, 0, $limit) as $performer)
                        <div class="flex items-center justify-between p-3 rounded-lg bg-white/60 dark:bg-gray-800/60 border border-gray-100 dark:border-gray-700 hover:bg-white/80 dark:hover:bg-gray-800/80 transition-all duration-200">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gradient-to-br from-blue-100 to-indigo-100 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center">
                                    <span class="text-sm font-bold text-blue-700 dark:text-blue-300">{{ $performer['position'] }}</span>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $performer['name'] }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $performer['accuracy'] }}% precisione</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-sm font-semibold text-blue-600 dark:text-blue-400">{{ $performer['points'] }}</span>
                                <p class="text-xs text-gray-500 dark:text-gray-400">punti</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- CTA Section -->
        <div class="mt-6 text-center">
            <a href="{{-- route('predict.index') --}}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 shadow-sm transition-all duration-200">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M12.293 5.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-2.293-2.293a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
                {{ __('Entra in Classifica') }}
            </a>
        </div>
    </div>
</div>

@push('styles')
<style>
    .hero-glow {
        box-shadow: 0 0 20px rgba(59, 130, 246, 0.5);
        animation: glow-pulse 2s infinite alternate;
    }
    
    @keyframes glow-pulse {
        from { box-shadow: 0 0 20px rgba(59, 130, 246, 0.5); }
        to { box-shadow: 0 0 30px rgba(59, 130, 246, 0.8); }
    }
</style>
@endpush