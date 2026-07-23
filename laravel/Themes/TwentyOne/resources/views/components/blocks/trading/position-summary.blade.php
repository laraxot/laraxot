@props([
    'user_positions' => [],
    'total_value' => 5420.00,
    'total_pnl' => 186.50,
    'total_pnl_percent' => 3.56
])

<div class="bg-white dark:bg-slate-800 rounded-xl shadow-lg border border-gray-200 dark:border-slate-700 p-6">
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-semibold text-gray-900 dark:text-white">Your Portfolio</h3>
        <div class="flex items-center space-x-4">
            <div class="text-right">
                <div class="text-sm text-gray-500 dark:text-gray-400">Total Value</div>
                <div class="text-lg font-semibold text-gray-900 dark:text-white">
                    ${{ number_format($total_value, 2) }}
                </div>
            </div>
            <div class="text-right">
                <div class="text-sm text-gray-500 dark:text-gray-400">Total P&L</div>
                <div class="text-lg font-semibold {{ $total_pnl >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                    {{ $total_pnl >= 0 ? '+' : '' }}${{ number_format($total_pnl, 2) }}
                    <span class="text-sm">({{ $total_pnl >= 0 ? '+' : '' }}{{ number_format($total_pnl_percent, 1) }}%)</span>
                </div>
            </div>
        </div>
    </div>

    @if(empty($user_positions))
    <!-- Empty State -->
    <div class="text-center py-12">
        <div class="w-16 h-16 mx-auto mb-4 bg-gray-100 dark:bg-slate-700 rounded-full flex items-center justify-center">
            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
        </div>
        <h4 class="text-lg font-medium text-gray-900 dark:text-white mb-2">No positions yet</h4>
        <p class="text-gray-500 dark:text-gray-400 mb-6">Start trading to build your portfolio</p>
        <a href="/markets" 
           class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200">
            Explore Markets
        </a>
    </div>
    @else
    <!-- Portfolio Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="bg-gray-50 dark:bg-slate-700 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ count($user_positions) }}</div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Active Positions</div>
        </div>
        <div class="bg-gray-50 dark:bg-slate-700 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-green-600 dark:text-green-400">
                {{ collect($user_positions)->where('pnl', '>', 0)->count() }}
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Winning</div>
        </div>
        <div class="bg-gray-50 dark:bg-slate-700 rounded-lg p-4 text-center">
            <div class="text-2xl font-bold text-red-600 dark:text-red-400">
                {{ collect($user_positions)->where('pnl', '<', 0)->count() }}
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">Losing</div>
        </div>
    </div>

    <!-- Positions List -->
    <div class="space-y-3">
        <div class="grid grid-cols-6 gap-4 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wide border-b border-gray-200 dark:border-slate-600 pb-2">
            <div class="col-span-2">Market</div>
            <div class="text-center">Position</div>
            <div class="text-center">Avg Price</div>
            <div class="text-center">Current</div>
            <div class="text-right">P&L</div>
        </div>

        @foreach($user_positions as $position)
        <div class="grid grid-cols-6 gap-4 py-3 hover:bg-gray-50 dark:hover:bg-slate-700/50 rounded-lg px-2 -mx-2 transition-colors">
            <div class="col-span-2">
                <div class="font-medium text-gray-900 dark:text-white">{{ $position['market_title'] }}</div>
                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $position['market_id'] }}</div>
            </div>
            <div class="text-center">
                <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium {{ $position['side'] === 'YES' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' }}">
                    {{ $position['side'] }} {{ $position['shares'] }}
                </div>
            </div>
            <div class="text-center font-mono text-sm text-gray-900 dark:text-white">
                {{ number_format($position['avg_price'] * 100, 1) }}¢
            </div>
            <div class="text-center font-mono text-sm text-gray-900 dark:text-white">
                {{ number_format($position['current_price'] * 100, 1) }}¢
            </div>
            <div class="text-right">
                <div class="font-semibold {{ $position['pnl'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                    {{ $position['pnl'] >= 0 ? '+' : '' }}${{ number_format($position['pnl'], 2) }}
                </div>
                <div class="text-xs {{ $position['pnl'] >= 0 ? 'text-green-600 dark:text-green-400' : 'text-red-600 dark:text-red-400' }}">
                    {{ $position['pnl'] >= 0 ? '+' : '' }}{{ number_format($position['pnl_percent'], 1) }}%
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Quick Actions -->
    <div class="mt-6 pt-4 border-t border-gray-200 dark:border-slate-600">
        <div class="flex items-center justify-between">
            <div class="flex space-x-3">
                <button class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg text-sm transition-colors duration-200">
                    View Full Portfolio
                </button>
                <button class="px-4 py-2 border border-gray-300 dark:border-slate-600 hover:bg-gray-50 dark:hover:bg-slate-700 text-gray-700 dark:text-gray-300 font-medium rounded-lg text-sm transition-colors duration-200">
                    Export Data
                </button>
            </div>
            <div class="text-sm text-gray-500 dark:text-gray-400">
                Last updated: <span class="font-medium">2 minutes ago</span>
            </div>
        </div>
    </div>
    @endif
</div>