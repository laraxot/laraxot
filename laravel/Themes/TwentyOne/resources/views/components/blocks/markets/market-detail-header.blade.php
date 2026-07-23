@props([
    'market_id' => 'crypto_001'
])

@php
    $categoryConfig = [
        'politics' => ['icon' => '🗳️', 'color' => 'blue'],
        'sports' => ['icon' => '⚽', 'color' => 'green'],
        'crypto' => ['icon' => '₿', 'color' => 'yellow'],
        'technology' => ['icon' => '💻', 'color' => 'purple'],
        'economics' => ['icon' => '📈', 'color' => 'indigo'],
        'entertainment' => ['icon' => '🎬', 'color' => 'pink'],
    ];
    
    $config = $categoryConfig[$market['category']] ?? ['icon' => '📊', 'color' => 'gray'];
    $resolutionDate = \Carbon\Carbon::parse($market['resolution_date']);
    $daysUntilResolution = now()->diffInDays($resolutionDate);
@endphp

<div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-gray-200 dark:border-slate-700 overflow-hidden">
    <!-- Header with category and status -->
    <div class="bg-gradient-to-r from-{{ $config['color'] }}-50 to-{{ $config['color'] }}-100 dark:from-{{ $config['color'] }}-900/20 dark:to-{{ $config['color'] }}-800/20 px-6 py-4">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <div class="text-2xl">{{ $config['icon'] }}</div>
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-{{ $config['color'] }}-100 text-{{ $config['color'] }}-800 dark:bg-{{ $config['color'] }}-900/20 dark:text-{{ $config['color'] }}-400">
                            {{ ucfirst($market['category']) }}
                        </span>
                        @if($is_featured)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-400">
                            Featured
                        </span>
                        @endif
                        <div class="flex items-center space-x-1">
                            <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                            <span class="text-xs text-gray-600 dark:text-gray-300">Live</span>
                        </div>
                    </div>
                    <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                        Market ID: {{ $market['id'] }}
                    </div>
                </div>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-500 dark:text-gray-400">Closes in</div>
                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $daysUntilResolution }} days
                </div>
            </div>
        </div>
    </div>

    <!-- Market title and description -->
    <div class="px-6 py-6">
        <h1 class="text-2xl md:text-3xl font-bold text-gray-900 dark:text-white mb-4 leading-tight">
            {{ $market['title'] }}
        </h1>
        
        <div class="prose prose-gray dark:prose-invert max-w-none mb-6">
            <p class="text-gray-600 dark:text-gray-300 leading-relaxed">
                {{ $market['description'] }}
            </p>
        </div>

        <!-- Price indicators -->
        <div class="grid grid-cols-2 gap-4 mb-6">
            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4 border border-green-200 dark:border-green-800">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-green-800 dark:text-green-400">YES</div>
                        <div class="text-2xl font-bold text-green-900 dark:text-green-300">
                            {{ number_format($market['yes_price'] * 100, 1) }}¢
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-green-600 dark:text-green-400">
                            {{ $market['price_change_24h'] >= 0 ? '↗' : '↘' }}
                            {{ abs($market['price_change_24h'] * 100) }}%
                        </div>
                        <div class="text-xs text-green-600 dark:text-green-400">24h</div>
                    </div>
                </div>
            </div>

            <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-4 border border-red-200 dark:border-red-800">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="text-sm font-medium text-red-800 dark:text-red-400">NO</div>
                        <div class="text-2xl font-bold text-red-900 dark:text-red-300">
                            {{ number_format($market['no_price'] * 100, 1) }}¢
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-xs text-red-600 dark:text-red-400">
                            {{ $market['price_change_24h'] >= 0 ? '↘' : '↗' }}
                            {{ abs($market['price_change_24h'] * 100) }}%
                        </div>
                        <div class="text-xs text-red-600 dark:text-red-400">24h</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Market statistics -->
        <div class="grid grid-cols-4 gap-6 pt-4 border-t border-gray-200 dark:border-slate-600">
            <div class="text-center">
                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                    ${{ number_format($market['volume']) }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">24h Volume</div>
            </div>
            <div class="text-center">
                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ number_format($market['traders']) }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Traders</div>
            </div>
            <div class="text-center">
                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ number_format(($market['no_price'] - $market['yes_price']) * 100, 1) }}¢
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Spread</div>
            </div>
            <div class="text-center">
                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ $resolutionDate->format('M j, Y') }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">Resolution</div>
            </div>
        </div>

        <!-- Action buttons -->
        <div class="flex items-center justify-between mt-6 pt-4 border-t border-gray-200 dark:border-slate-600">
            <div class="flex items-center space-x-4">
                <button class="flex items-center space-x-2 px-4 py-2 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                    </svg>
                    <span class="text-sm">Watch</span>
                </button>
                <button class="flex items-center space-x-2 px-4 py-2 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.367 2.684 3 3 0 00-5.367-2.684z"></path>
                    </svg>
                    <span class="text-sm">Share</span>
                </button>
                <button class="flex items-center space-x-2 px-4 py-2 bg-gray-100 dark:bg-slate-700 hover:bg-gray-200 dark:hover:bg-slate-600 text-gray-700 dark:text-gray-300 rounded-lg transition-colors duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="text-sm">Analytics</span>
                </button>
            </div>
            
            <div class="flex items-center space-x-2">
                <button class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-lg transition-colors duration-200">
                    Buy YES
                </button>
                <button class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white font-medium rounded-lg transition-colors duration-200">
                    Buy NO
                </button>
            </div>
        </div>
    </div>
</div>