@props([
    'markets' => [],
    'title' => '',
    'description' => ''
])

@php
    // Convert markets to collection if not already
    $markets = collect($markets);
    
    // Ensure all markets have required properties
    $markets = $markets->map(function($market) {
        return (object) array_merge([
            'title' => '',
            'trending_up' => false,
            'change' => 0,
            'yes_percent' => 50,
            'yes_price' => 0.50,
            'no_price' => 0.50
        ], (array) $market);
    });
@endphp

<div class="market-carousel relative" wire:key="carousel-{{ md5(serialize($markets)) }}">
    @if($title || $description)
        <div class="mb-8">
            @if($title)
                <h2 class="text-2xl font-bold text-white mb-2">{{ $title }}</h2>
            @endif
            @if($description)
                <p class="text-gray-400">{{ $description }}</p>
            @endif
        </div>
    @endif
    
    <div class="glide">
        <div class="glide__track" data-glide-el="track">
            <ul class="glide__slides">
                @foreach($markets as $market)
                <li class="glide__slide">
                    <div class="market-card bg-gray-800 rounded-xl p-6 border border-gray-700 hover:border-indigo-400 transition-colors">
                        <div class="flex justify-between items-start mb-3">
                            <h3 class="text-lg font-bold text-white">{{ $market->title }}</h3>
                            <div class="trend-badge {{ $market->trending_up ? 'bg-green-500' : 'bg-red-500' }} text-xs px-2 py-1 rounded-full">
                                {{ $market->trending_up ? '▲' : '▼' }} {{ $market->change }}%
                            </div>
                        </div>
                        
                        <div class="probability-meter h-3 bg-gray-700 rounded-full overflow-hidden mb-4">
                            <div 
                                class="h-full bg-gradient-to-r from-green-500 to-red-500"
                                style="width: {{ $market->yes_percent }}%"
                            ></div>
                        </div>
                        
                        <div class="flex justify-between text-sm text-gray-400 mb-4">
                            <span>SI {{ $market->yes_percent }}%</span>
                            <span>NO {{ 100 - $market->yes_percent }}%</span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-3">
                            <button class="btn-trade bg-green-500/20 hover:bg-green-500/40 text-green-300 py-2 px-3 rounded text-sm transition-colors">
                                SI @ {{ $market->yes_price }}
                            </button>
                            <button class="btn-trade bg-red-500/20 hover:bg-red-500/40 text-red-300 py-2 px-3 rounded text-sm transition-colors">
                                NO @ {{ $market->no_price }}
                            </button>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</div>
