@props([
    'predictions' => [
        ['user' => 'Marco', 'action' => 'YES', 'market' => 'F1 Champion 2026', 'amount' => 50, 'time' => '2m'],
        ['user' => 'Lisa', 'action' => 'NO', 'market' => 'Oscar Best Picture', 'amount' => 30, 'time' => '5m'],
        ['user' => 'Alex', 'action' => 'YES', 'market' => 'Bitcoin $100K', 'amount' => 100, 'time' => '8m'],
        ['user' => 'Sofia', 'action' => 'YES', 'market' => 'Election 2026', 'amount' => 75, 'time' => '12m'],
        ['user' => 'Luca', 'action' => 'NO', 'market' => 'Crypto Crash', 'amount' => 45, 'time' => '15m'],
    ],
])

{{-- Live Feed Ticker - Polymarket Style --}}
<div class="bg-slate-800/50 border-b border-slate-700 overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 py-2">
        <div class="flex items-center gap-2 text-sm">
            <div class="flex items-center gap-2 text-emerald-400">
                <span class="relative flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                </span>
                <span class="font-semibold text-white">LIVE</span>
            </div>
            <span class="text-slate-500">•</span>
            <span class="text-slate-300 font-medium">Ultime previsioni:</span>
            
            {{-- Scrolling Feed --}}
            <div class="flex-1 overflow-hidden">
                <div class="flex gap-6 animate-scroll">
                    @foreach($predictions as $pred)
                    <div class="flex items-center gap-2 text-sm whitespace-nowrap">
                        <span class="text-indigo-400 font-semibold">{{ $pred['user'] }}</span>
                        <span class="text-slate-500">ha predetto</span>
                        <span class="{{ $pred['action'] === 'YES' ? 'text-emerald-400' : 'text-rose-400' }} font-bold">
                            {{ $pred['action'] }}
                        </span>
                        <span class="text-slate-300">{{ Str::limit($pred['market'], 20) }}</span>
                        <span class="text-amber-400 font-semibold inline-flex items-center gap-1">{{ $pred['amount'] }} <x-filament::icon icon="predict-currency" class="h-4 w-4" aria-hidden="true" /></span>
                        <span class="text-slate-500 text-xs">{{ $pred['time'] }} fa</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    @keyframes scroll {
        0% { transform: translateX(0); }
        100% { transform: translateX(-50%); }
    }
    .animate-scroll {
        animation: scroll 30s linear infinite;
    }
    .animate-scroll:hover {
        animation-play-state: paused;
    }
</style>
@endpush
