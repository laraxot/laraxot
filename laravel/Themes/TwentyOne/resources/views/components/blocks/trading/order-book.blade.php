@props([
    'market_id' => 'crypto_001',
    'yes_price' => 0.42,
    'no_price' => 0.58,
    'volume' => 89300,
    'traders' => 3789,
    'price_change_24h' => 0.05,
    'user_position' => null
])

@php
    // Simulate order book data for demo
    $yesOrders = [
        ['price' => $yes_price + 0.01, 'size' => 1200, 'total' => 1200],
        ['price' => $yes_price, 'size' => 2500, 'total' => 3700],
        ['price' => $yes_price - 0.01, 'size' => 1800, 'total' => 5500],
        ['price' => $yes_price - 0.02, 'size' => 3200, 'total' => 8700],
        ['price' => $yes_price - 0.03, 'size' => 2100, 'total' => 10800],
    ];
    
    $noOrders = [
        ['price' => $no_price + 0.03, 'size' => 1900, 'total' => 1900],
        ['price' => $no_price + 0.02, 'size' => 2800, 'total' => 4700],
        ['price' => $no_price + 0.01, 'size' => 1600, 'total' => 6300],
        ['price' => $no_price, 'size' => 3400, 'total' => 9700],
        ['price' => $no_price - 0.01, 'size' => 2200, 'total' => 11900],
    ];
@endphp

<div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-gray-200 dark:border-slate-700 p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Order Book</h3>
        <div class="flex items-center space-x-4 text-sm">
            <div class="flex items-center space-x-2">
                <div class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></div>
                <span class="text-gray-600 dark:text-gray-300">Live</span>
            </div>
            <div class="text-gray-600 dark:text-gray-300">
                {{ number_format($traders) }} traders
            </div>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        <!-- YES Orders -->
        <div class="space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-gray-200 dark:border-slate-600">
                <h4 class="font-medium text-green-600 dark:text-green-400">YES Orders</h4>
                <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                    {{ number_format($yes_price * 100, 1) }}¢
                </div>
            </div>
            
            <div class="space-y-1">
                <div class="grid grid-cols-3 gap-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                    <div>Price</div>
                    <div class="text-right">Size</div>
                    <div class="text-right">Total</div>
                </div>
                
                @foreach($yesOrders as $order)
                <div class="grid grid-cols-3 gap-2 py-1 hover:bg-green-50 dark:hover:bg-green-900/20 rounded px-2 -mx-2 transition-colors">
                    <div class="text-green-600 dark:text-green-400 font-mono text-sm">
                        {{ number_format($order['price'] * 100, 1) }}¢
                    </div>
                    <div class="text-right text-gray-600 dark:text-gray-300 font-mono text-sm">
                        {{ number_format($order['size']) }}
                    </div>
                    <div class="text-right text-gray-500 dark:text-gray-400 font-mono text-sm">
                        {{ number_format($order['total']) }}
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Place YES Order Form -->
            <div class="mt-4 p-3 bg-green-50 dark:bg-green-900/20 rounded-lg">
                <form class="space-y-3" wire:submit.prevent="placeYesOrder">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Price</label>
                            <div class="relative">
                                <input type="number" 
                                       class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                       placeholder="{{ number_format($yes_price * 100, 1) }}"
                                       step="0.1"
                                       min="0.1"
                                       max="99.9">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 dark:text-gray-400 text-xs">¢</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Quantity</label>
                            <input type="number" 
                                   class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-green-500 focus:border-transparent"
                                   placeholder="100"
                                   min="1">
                        </div>
                    </div>
                    <button type="submit" 
                            class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded-md font-medium text-sm transition-colors duration-200 focus:ring-2 focus:ring-green-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800">
                        Buy YES
                    </button>
                </form>
            </div>
        </div>

        <!-- NO Orders -->
        <div class="space-y-3">
            <div class="flex items-center justify-between pb-2 border-b border-gray-200 dark:border-slate-600">
                <h4 class="font-medium text-red-600 dark:text-red-400">NO Orders</h4>
                <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                    {{ number_format($no_price * 100, 1) }}¢
                </div>
            </div>
            
            <div class="space-y-1">
                <div class="grid grid-cols-3 gap-2 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide">
                    <div>Price</div>
                    <div class="text-right">Size</div>
                    <div class="text-right">Total</div>
                </div>
                
                @foreach($noOrders as $order)
                <div class="grid grid-cols-3 gap-2 py-1 hover:bg-red-50 dark:hover:bg-red-900/20 rounded px-2 -mx-2 transition-colors">
                    <div class="text-red-600 dark:text-red-400 font-mono text-sm">
                        {{ number_format($order['price'] * 100, 1) }}¢
                    </div>
                    <div class="text-right text-gray-600 dark:text-gray-300 font-mono text-sm">
                        {{ number_format($order['size']) }}
                    </div>
                    <div class="text-right text-gray-500 dark:text-gray-400 font-mono text-sm">
                        {{ number_format($order['total']) }}
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Place NO Order Form -->
            <div class="mt-4 p-3 bg-red-50 dark:bg-red-900/20 rounded-lg">
                <form class="space-y-3" wire:submit.prevent="placeNoOrder">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Price</label>
                            <div class="relative">
                                <input type="number" 
                                       class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                       placeholder="{{ number_format($no_price * 100, 1) }}"
                                       step="0.1"
                                       min="0.1"
                                       max="99.9">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <span class="text-gray-500 dark:text-gray-400 text-xs">¢</span>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700 dark:text-gray-300 mb-1">Quantity</label>
                            <input type="number" 
                                   class="w-full px-3 py-2 text-sm border border-gray-300 dark:border-slate-600 rounded-md bg-white dark:bg-slate-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-red-500 focus:border-transparent"
                                   placeholder="100"
                                   min="1">
                        </div>
                    </div>
                    <button type="submit" 
                            class="w-full bg-red-600 hover:bg-red-700 text-white py-2 px-4 rounded-md font-medium text-sm transition-colors duration-200 focus:ring-2 focus:ring-red-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800">
                        Buy NO
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Market Statistics -->
    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-slate-600">
        <div class="grid grid-cols-3 gap-4 text-center">
            <div>
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">24h Volume</div>
                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                    ${{ number_format($volume) }}
                </div>
            </div>
            <div>
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">24h Change</div>
                <div class="text-lg font-semibold {{ $price_change_24h >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                    {{ $price_change_24h >= 0 ? '+' : '' }}{{ number_format($price_change_24h * 100, 1) }}%
                </div>
            </div>
            <div>
                <div class="text-sm font-medium text-gray-500 dark:text-gray-400">Spread</div>
                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                    {{ number_format(($no_price - $yes_price) * 100, 1) }}¢
                </div>
            </div>
        </div>
    </div>

    @if($user_position)
    <!-- User Position -->
    <div class="mt-4 p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                <span class="text-sm font-medium text-blue-900 dark:text-blue-100">Your Position</span>
            </div>
            <div class="text-sm font-semibold text-blue-900 dark:text-blue-100">
                {{ $user_position['shares'] }} shares @ {{ number_format($user_position['avg_price'] * 100, 1) }}¢
            </div>
        </div>
    </div>
    @endif
</div>

<style>
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.animate-pulse {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>