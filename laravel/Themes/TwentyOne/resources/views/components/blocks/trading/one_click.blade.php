@props([
    'market' => null
])

@php
use Modules\Predict\Actions\FetchTrendingMarketsAction;

// If market is a string (method name) or null, fetch featured market
if (is_string($market) || is_null($market)) {
    $featuredMarket = app(FetchTrendingMarketsAction::class)->execute(1)->first();
} else {
    $featuredMarket = is_object($market) ? $market : (object) $market;
}

// Fallback if no market data
if (!$featuredMarket) {
    $featuredMarket = (object) [
        'id' => 'bitcoin-100k-2025',
        'title' => 'Bitcoin raggiungerà 100K$ entro il 2025?',
        'time_remaining' => '347 giorni',
        'yes_percent' => 72,
        'current_price' => '72%',
        'volume' => '€2.4M',
        'trend' => '+15%',
        'yes_price' => 0.72,
        'no_price' => 0.28,
        'urgency' => 'TRENDING #1'
    ];
}

// Ensure we have required properties
$market = [
    'id' => $featuredMarket->id ?? 'market-' . uniqid(),
    'title' => $featuredMarket->title ?? 'Featured Market',
    'time_remaining' => $featuredMarket->time_remaining ?? '24 ore',
    'yes_percent' => $featuredMarket->yes_percent ?? 50,
    'yes_price' => $featuredMarket->yes_price ?? ($featuredMarket->yes_percent ?? 50) / 100,
    'no_price' => $featuredMarket->no_price ?? (100 - ($featuredMarket->yes_percent ?? 50)) / 100,
    'volume' => $featuredMarket->volume ?? '€1M',
    'trend' => $featuredMarket->trend ?? '+0%',
    'urgency' => $featuredMarket->urgency ?? 'ATTIVO'
];
@endphp

<div class="one-click-trading bg-gradient-to-br from-gray-800 to-gray-900 rounded-xl p-6 border-2 border-indigo-500/30 hover:border-indigo-400/50 transition-all duration-300 shadow-2xl relative overflow-hidden">

  <!-- Urgency Badge -->
  @if($market['urgency'] !== 'ATTIVO')
  <div class="absolute top-4 right-4 bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full animate-pulse z-10">
    {{ $market['urgency'] }}
  </div>
  @endif

  <!-- Market Header -->
  <div class="flex justify-between items-start mb-4">
    <div class="flex-1 pr-4">
      <h3 class="text-xl font-bold text-white leading-tight">{{ $market['title'] }}</h3>
      <div class="flex items-center space-x-4 mt-2">
        <div class="text-sm text-gray-400">⏰ {{ $market['time_remaining'] }}</div>
        <div class="text-sm text-gray-400">📈 {{ $market['volume'] }}</div>
      </div>
    </div>
    <div class="text-right">
      <div class="text-3xl font-black {{ $market['yes_percent'] > 50 ? 'text-green-400' : 'text-red-400' }}">
        {{ $market['yes_percent'] }}%
      </div>
      <div class="text-xs text-gray-400">PROBABILITÀ</div>
    </div>
  </div>

  <!-- Enhanced Probability Bar -->
  <div class="probability-bar h-4 bg-gray-700 rounded-full overflow-hidden mb-6 relative shadow-inner">
    <div
      class="h-full bg-gradient-to-r from-green-500 via-yellow-500 to-red-500 transition-all duration-1000 ease-out"
      style="width: {{ $market['yes_percent'] }}%"
    ></div>
    <div class="absolute inset-0 flex items-center justify-center text-xs font-bold text-white mix-blend-difference">
      {{ $market['yes_percent'] }}% SÌ
    </div>
  </div>

  <!-- Trend Indicator -->
  <div class="flex items-center justify-center mb-4">
    <div class="flex items-center space-x-2 {{ str_starts_with($market['trend'], '+') ? 'text-green-400' : 'text-red-400' }}">
      <span class="text-sm">{{ str_starts_with($market['trend'], '+') ? '📈' : '📉' }}</span>
      <span class="font-bold">{{ $market['trend'] }} nelle ultime 24h</span>
    </div>
  </div>

  <!-- One-Click Trading Buttons -->
  <div class="grid grid-cols-2 gap-3 mb-4">
    <button
      onclick="quickTrade('{{ $market['id'] }}', 'yes')"
      class="btn-trade-yes bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white py-4 px-4 rounded-xl font-black text-lg transition-all duration-200 transform hover:scale-105 active:scale-95 flex flex-col items-center space-y-1 shadow-lg">
      <div class="flex items-center space-x-2">
        <span>👍</span>
        <span>SÌ</span>
      </div>
      <div class="text-xs opacity-80">{{ number_format($market['yes_price'], 2) }}</div>
    </button>
    <button
      onclick="quickTrade('{{ $market['id'] }}', 'no')"
      class="btn-trade-no bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white py-4 px-4 rounded-xl font-black text-lg transition-all duration-200 transform hover:scale-105 active:scale-95 flex flex-col items-center space-y-1 shadow-lg">
      <div class="flex items-center space-x-2">
        <span>👎</span>
        <span>NO</span>
      </div>
      <div class="text-xs opacity-80">{{ number_format($market['no_price'], 2) }}</div>
    </button>
  </div>

  <!-- Quick Info -->
  <div class="text-center">
    <div class="text-xs text-gray-400 mb-2">
      💸 Bet minimo €10 • ⚡ Esecuzione istantanea • 🚫 0% commissioni
    </div>
    <div class="text-xs text-yellow-400 font-semibold">
      💡 Tip: {{ $market['yes_percent'] > 50 ? 'Mercato rialzista' : 'Mercato ribassista' }}
    </div>
  </div>

  <!-- Background Animation -->
  <div class="absolute inset-0 opacity-5 pointer-events-none">
    <div class="absolute top-0 left-0 w-4 h-4 bg-white rounded-full animate-ping" style="animation-delay: 0s;"></div>
    <div class="absolute top-1/2 right-0 w-3 h-3 bg-indigo-400 rounded-full animate-ping" style="animation-delay: 1s;"></div>
    <div class="absolute bottom-0 left-1/3 w-2 h-2 bg-green-400 rounded-full animate-ping" style="animation-delay: 2s;"></div>
  </div>
</div>

@once
@push('scripts')
<script>
async function quickTrade(marketId, position) {
    try {
        // Show loading state
        const button = event.target.closest('button');
        const originalContent = button.innerHTML;
        button.innerHTML = '<div class="animate-spin h-6 w-6 border-2 border-white border-t-transparent rounded-full mx-auto"></div>';
        button.disabled = true;

        // Simulate API call with realistic delay
        await new Promise(resolve => setTimeout(resolve, 800));

        // Calculate potential win
        const betAmount = 10; // Default bet amount
        const multiplier = position === 'yes' ?
            (100 / {{ $market['yes_percent'] }}) :
            (100 / {{ 100 - $market['yes_percent'] }});
        const potentialWin = Math.round(betAmount * multiplier);

        // Show success toast with celebration
        showSuccessToast(`🎉 Bet ${position.toUpperCase()} piazzato!\n💰 Potential win: €${potentialWin}\n⚡ Elaborazione in corso...`);

        // Add visual feedback
        button.classList.add('animate-pulse');
        setTimeout(() => button.classList.remove('animate-pulse'), 2000);

        // Reset button
        setTimeout(() => {
            button.innerHTML = originalContent;
            button.disabled = false;
        }, 1000);

    } catch (error) {
        showErrorToast('❌ Errore nel trading. Riprova tra poco.');
        button.innerHTML = originalContent;
        button.disabled = false;
    }
}

function showSuccessToast(message) {
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 z-50 px-6 py-4 rounded-lg font-semibold text-white bg-gradient-to-r from-green-500 to-emerald-600 transform translate-x-full transition-transform duration-300 shadow-2xl border border-green-400/30';
    toast.innerHTML = message.replace(/\n/g, '<br>');

    document.body.appendChild(toast);

    // Animate in
    setTimeout(() => toast.classList.remove('translate-x-full'), 100);

    // Remove after 4 seconds
    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => document.body.removeChild(toast), 300);
    }, 4000);
}

function showErrorToast(message) {
    const toast = document.createElement('div');
    toast.className = 'fixed top-4 right-4 z-50 px-6 py-3 rounded-lg font-semibold text-white bg-gradient-to-r from-red-500 to-red-600 transform translate-x-full transition-transform duration-300 shadow-2xl';
    toast.textContent = message;

    document.body.appendChild(toast);

    setTimeout(() => toast.classList.remove('translate-x-full'), 100);

    setTimeout(() => {
        toast.classList.add('translate-x-full');
        setTimeout(() => document.body.removeChild(toast), 300);
    }, 3000);
}
</script>
@endpush
@endonce
