@php
    // Support multiple data sources from CMS
    $record = $record ?? $predict ?? $item ?? $article ?? null;
    
    // Try to get from $data array (CMS standard)
    if (!$record && isset($data['record'])) {
        $record = $data['record'];
    }

    if (!$record && isset($data['predict'])) {
        $record = $data['predict'];
    }

    if (!$record && isset($data['item'])) {
        $record = $data['item'];
    }
    
    // Try to get from page attribute
    if (!$record && isset($data['article'])) {
        $record = $data['article'];
    }
    
    // Build marketData if not provided
    $marketData = $marketData ?? $data['marketData'] ?? [];
    
    if (empty($marketData) && $record) {
        $marketData = [
            'current_prices' => [
                'yes' => $record->value_buy ?? 67,
                'no' => $record->value_sell ?? 33,
            ],
            'volume_24h' => $record->volume_24h ?? 24580,
            'total_volume' => $record->total_volume ?? ($record->volume_24h ?? 24580) * 1.5,
            'participants' => $record->participants_count ?? 1245,
            'open_interest' => $record->open_interest ?? ($record->volume_24h ?? 24580) * 0.75,
            'spread' => abs(($record->value_buy ?? 67) - ($record->value_sell ?? 33)),
            'liquidity' => $record->liquidity ?? 'high',
            'last_updated' => now(),
        ];
    }

    $tx = static function (string $key, string $fallback): string {
        $translated = __($key);
        if (is_string($translated) && $translated !== $key) {
            return $translated;
        }

        $labelKey = $key . '.label';
        $translatedLabel = __($labelKey);
        if (is_string($translatedLabel) && $translatedLabel !== $labelKey) {
            return $translatedLabel;
        }

        return $fallback;
    };
@endphp

@if($record)
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
    <div class="px-6 py-5">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">
            {{ is_array($record->title ?? null) ? ($record->title['it'] ?? $record->title['en'] ?? 'Market Detail') : ($record->title ?? 'Market Detail') }}
        </h1>
        
        @if($record->description)
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                {{ Str::limit(is_array($record->description ?? null) ? ($record->description['it'] ?? $record->description['en'] ?? '') : $record->description, 200) }}
            </p>
        @endif

        {{-- Status Badges --}}
        <div class="mt-4 flex flex-wrap gap-2">
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                <span class="w-2 h-2 mr-2 bg-green-500 rounded-full"></span>
                {{ __('predict::common.labels.status.active.label', ['default' => 'Active']) }}
            </span>
            @if($record->volume_24h)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                    {{ number_format($record->volume_24h, 0) }} CAPS {{ __('predict::market.stats.volume_24h.label') ?: 'Volume 24h' }}
                </span>
            @endif
        </div>

        {{-- Expiration Date --}}
        <div class="mt-3 flex items-center text-sm text-gray-600 dark:text-gray-400">
            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
            </svg>
            @if($record->ends_at)
                {{ __('predict::market.dates.ends_in') ?: 'Ends in' }}: {{ \Carbon\Carbon::parse($record->ends_at)->diffForHumans() }}
            @elseif($record->closed_at)
                {{ __('predict::market.dates.ends_in') ?: 'Ends in' }}: {{ \Carbon\Carbon::parse($record->closed_at)->diffForHumans() }}
            @endif
        </div>
    </div>
</div>
@else
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden p-6">
    <p class="text-gray-500 dark:text-gray-400 text-center">Market not found</p>
</div>
@endif
