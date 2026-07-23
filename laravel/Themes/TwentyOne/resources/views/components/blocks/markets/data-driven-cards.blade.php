@props([
    'source' => 'featured', // featured, trending, category
    'category' => 'all',
    'limit' => 6,
    'title' => 'Featured Markets',
    'subtitle' => 'Most popular prediction markets',
    'show_categories' => true,
    'show_volume' => true,
    'grid_cols' => 'md:grid-cols-2 lg:grid-cols-3'
])

@php
// Load market data from JSON
$marketsData = json_decode(file_get_contents(config_path('local/predict/database/content/markets-seed-data.json')), true);

// Get markets based on source
$markets = collect();
if ($source === 'featured') {
    $featuredIds = $marketsData['featured_markets'] ?? [];
    foreach ($featuredIds as $id) {
        foreach ($marketsData['categories'] as $cat) {
            $market = collect($cat['markets'])->firstWhere('id', $id);
            if ($market) {
                $market['category_info'] = [
                    'name' => $cat['name'],
                    'icon' => $cat['icon'], 
                    'color' => $cat['color']
                ];
                $markets->push($market);
            }
        }
    }
} elseif ($source === 'trending') {
    $trendingIds = $marketsData['trending_markets'] ?? [];
    foreach ($trendingIds as $id) {
        foreach ($marketsData['categories'] as $cat) {
            $market = collect($cat['markets'])->firstWhere('id', $id);
            if ($market) {
                $market['category_info'] = [
                    'name' => $cat['name'],
                    'icon' => $cat['icon'],
                    'color' => $cat['color']
                ];
                $markets->push($market);
            }
        }
    }
} elseif ($category !== 'all' && isset($marketsData['categories'][$category])) {
    $catData = $marketsData['categories'][$category];
    foreach ($catData['markets'] as $market) {
        $market['category_info'] = [
            'name' => $catData['name'],
            'icon' => $catData['icon'],
            'color' => $catData['color']
        ];
        $markets->push($market);
    }
} else {
    // All markets
    foreach ($marketsData['categories'] as $catKey => $cat) {
        foreach ($cat['markets'] as $market) {
            $market['category_info'] = [
                'name' => $cat['name'], 
                'icon' => $cat['icon'],
                'color' => $cat['color']
            ];
            $markets->push($market);
        }
    }
}

$markets = $markets->take($limit);

// Color map for badges
$colorMap = [
    'blue' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300',
    'green' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300',
    'yellow' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300',
    'purple' => 'bg-purple-100 text-purple-800 dark:bg-purple-900/20 dark:text-purple-300',
    'indigo' => 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/20 dark:text-indigo-300',
    'pink' => 'bg-pink-100 text-pink-800 dark:bg-pink-900/20 dark:text-pink-300',
];
@endphp

<section class="py-16 bg-gray-50 dark:bg-slate-900">
    <div class="container mx-auto px-4">
        {{-- Section Header --}}
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-900 dark:text-white mb-4">{{ $title }}</h2>
            @if($subtitle)
            <p class="text-xl text-gray-600 dark:text-gray-300 max-w-3xl mx-auto">{{ $subtitle }}</p>
            @endif
        </div>

        {{-- Markets Grid --}}
        <div class="grid {{ $grid_cols }} gap-6 mb-12">
            @forelse($markets as $market)
            <div class="group bg-white dark:bg-slate-800 rounded-2xl border border-gray-200 dark:border-slate-700 hover:border-blue-300 dark:hover:border-blue-600 transition-all duration-300 hover:shadow-xl overflow-hidden">
                <div class="p-6">
                    {{-- Category Badge & Resolution Date --}}
                    @if($show_categories && isset($market['category_info']))
                    <div class="flex items-center justify-between mb-4">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $colorMap[$market['category_info']['color']] ?? $colorMap['blue'] }}">
                            {{ $market['category_info']['icon'] }} {{ $market['category_info']['name'] }}
                        </span>
                        <div class="text-sm text-gray-500 dark:text-gray-400">
                            Resolves: {{ date('M Y', strtotime($market['resolution_date'])) }}
                        </div>
                    </div>
                    @endif

                    {{-- Market Title --}}
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-3 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2">
                        {{ $market['title'] }}
                    </h3>

                    {{-- Price Section --}}
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center space-x-4">
                            <div class="flex items-center space-x-2">
                                <span class="text-2xl font-bold text-green-600 dark:text-green-400">
                                    {{ number_format($market['yes_price'] * 100) }}¢
                                </span>
                                <span class="text-sm text-gray-500">YES</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-2xl font-bold text-red-600 dark:text-red-400">
                                    {{ number_format($market['no_price'] * 100) }}¢
                                </span>
                                <span class="text-sm text-gray-500">NO</span>
                            </div>
                        </div>
                        <div class="text-sm font-medium {{ $market['price_change_24h'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                            {{ $market['price_change_24h'] >= 0 ? '+' : '' }}{{ number_format($market['price_change_24h'] * 100) }}¢ (24h)
                        </div>
                    </div>

                    {{-- Volume Info --}}
                    @if($show_volume)
                    <div class="flex items-center justify-between text-sm text-gray-500 dark:text-gray-400 mb-4">
                        <span>Volume: €{{ number_format($market['volume'] / 1000, 1) }}K</span>
                        <span>{{ number_format($market['traders']) }} traders</span>
                    </div>
                    @endif

                    {{-- Action Button --}}
                    <button class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-lg font-medium transition-colors">
                        Trade Now
                    </button>
                </div>
            </div>
            @empty
            {{-- No Markets Found --}}
            <div class="col-span-full text-center py-12">
                <div class="text-gray-400 dark:text-gray-600 text-6xl mb-4">📊</div>
                <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">No Markets Found</h3>
                <p class="text-gray-600 dark:text-gray-400">No markets available for the selected criteria.</p>
            </div>
            @endforelse
        </div>

        {{-- View All Button --}}
        @if($markets->count() >= $limit)
        <div class="text-center">
            <a href="/markets?source={{ $source }}&category={{ $category }}" 
               class="inline-flex items-center px-8 py-4 bg-white dark:bg-slate-800 border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white dark:hover:bg-blue-600 rounded-xl font-semibold transition-all duration-300 hover:scale-105">
                View All Markets
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path>
                </svg>
            </a>
        </div>
        @endif
    </div>
</section>

<style>
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>