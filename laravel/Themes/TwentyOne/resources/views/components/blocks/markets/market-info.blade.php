@props([
    'market_id' => 'crypto_001'
])

@php
    // Load market data from JSON seed file
    $jsonPath = config_path('local/predict/database/content/markets-seed-data.json');
    $data = json_decode(file_get_contents($jsonPath), true);
    $market = null;
    
    // Find market by ID
    foreach ($data['categories'] as $category) {
        foreach ($category['markets'] as $marketData) {
            if ($marketData['id'] === $market_id) {
                $market = $marketData;
                break 2;
            }
        }
    }
    
    // Fallback to first crypto market if not found
    if (!$market) {
        $market = $data['categories']['crypto']['markets'][0];
        $market['id'] = $market_id;
    }
@endphp

<div class="space-y-8">
    <!-- Market Information -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-gray-200 dark:border-slate-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Resolution Criteria</h3>
        <div class="prose prose-sm prose-gray dark:prose-invert">
            <p>{{ $market['description'] }}</p>
            
            <h4 class="text-sm font-semibold text-gray-900 dark:text-white mt-4">Key Details:</h4>
            <ul class="text-sm text-gray-600 dark:text-gray-300">
                <li>Resolution Date: {{ \Carbon\Carbon::parse($market['resolution_date'])->format('F j, Y') }}</li>
                <li>Category: {{ ucfirst($market['category']) }}</li>
                <li>Market ID: {{ $market['id'] }}</li>
            </ul>
        </div>
    </div>

    <!-- Related Markets -->
    <div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-gray-200 dark:border-slate-700 p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Related Markets</h3>
        <div class="space-y-3">
            @php
                $relatedMarkets = [];
                // Find 2 related markets from same category
                foreach ($data['categories'][$market['category']]['markets'] as $relatedMarket) {
                    if ($relatedMarket['id'] !== $market['id'] && count($relatedMarkets) < 2) {
                        $relatedMarkets[] = $relatedMarket;
                    }
                }
            @endphp
            
            @foreach($relatedMarkets as $related)
            <a href="/it/markets/{{ $related['id'] }}" class="block p-3 bg-gray-50 dark:bg-slate-700 rounded-lg hover:bg-gray-100 dark:hover:bg-slate-600 transition-colors">
                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $related['title'] }}</div>
                <div class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    {{ number_format($related['yes_price'] * 100, 0) }}¢ YES • {{ number_format($related['no_price'] * 100, 0) }}¢ NO
                </div>
            </a>
            @endforeach
        </div>
    </div>
</div>