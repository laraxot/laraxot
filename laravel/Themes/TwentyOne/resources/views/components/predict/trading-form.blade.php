@props(['predict'])

{{-- Trading Form Component --}}
<div class="trading-form bg-gradient-to-br from-slate-900/90 to-slate-800/90 backdrop-blur-sm p-6 rounded-2xl border border-slate-700/50">
    {{-- Header with Value Prop --}}
    <div class="mb-6">
        <h3 class="text-xl font-bold text-white flex items-center gap-2">
            <svg class="w-6 h-6 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
            </svg>
            Piazza la Tua Previsione
        </h3>
        <p class="text-slate-400 text-sm mt-1">Indovina e vinci 10x la tua puntata</p>
    </div>
    
    <form wire:submit="placeOrder" class="space-y-5">
        {{-- Select Outcome --}}
        <div>
            <label for="outcome" class="block text-sm font-medium text-slate-300 mb-2 flex items-center gap-2">
                <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Scegli l'esito
            </label>
            <select 
                id="outcome" 
                wire:model.live="selectedOutcomeId"
                class="w-full rounded-xl bg-slate-800/80 border border-slate-600/50 text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all py-3 px-4"
                aria-label="Seleziona esito"
            >
                <option value="">-- Seleziona un esito --</option>
                @foreach($predict->outcomes ?? [] as $outcome)
                    <option value="{{ $outcome->id }}">
                        {{ $outcome->name }} - {{ number_format($outcome->probability ?? 0, 1) }}%
                    </option>
                @endforeach
            </select>
        </div>
        
        {{-- Quick Amount Buttons --}}
        <div>
            <label for="quantity" class="block text-sm font-medium text-slate-300 mb-2 flex items-center gap-2">
                <svg class="w-4 h-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                Quante quote?
            </label>
            <div class="flex gap-2 mb-3">
                <button type="button" wire:click="$set('quantity', 10)" class="flex-1 py-2 px-3 rounded-lg bg-slate-800/50 border border-slate-600/50 text-slate-300 text-sm font-medium hover:bg-slate-700/50 hover:border-slate-500 transition-all">10</button>
                <button type="button" wire:click="$set('quantity', 50)" class="flex-1 py-2 px-3 rounded-lg bg-slate-800/50 border border-slate-600/50 text-slate-300 text-sm font-medium hover:bg-slate-700/50 hover:border-slate-500 transition-all">50</button>
                <button type="button" wire:click="$set('quantity', 100)" class="flex-1 py-2 px-3 rounded-lg bg-slate-800/50 border border-slate-600/50 text-slate-300 text-sm font-medium hover:bg-slate-700/50 hover:border-slate-500 transition-all">100</button>
            </div>
            <input 
                type="number" 
                id="quantity"
                wire:model.live="quantity"
                min="1"
                max="1000"
                placeholder="Oppure inserisci un importo"
                class="w-full rounded-xl bg-slate-800/80 border border-slate-600/50 text-white focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 transition-all py-3 px-4"
                aria-label="Quantità quote"
            />
        </div>
        
        {{-- Preview Card --}}
        <div class="p-4 bg-gradient-to-r from-slate-800/50 to-slate-700/30 rounded-xl border border-slate-700/50">
            <div class="flex justify-between items-center mb-3">
                <span class="text-slate-400 text-sm flex items-center gap-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                    </svg>
                    Costo per quota:
                </span>
                <span class="text-white font-semibold">{{ number_format($pricePerShare ?? 0, 2) }} Credits</span>
            </div>
            <div class="flex justify-between items-center pt-3 border-t border-slate-700/50">
                <span class="text-slate-300 text-sm font-medium">Totale da pagare:</span>
                <span class="text-emerald-400 font-bold text-xl">{{ number_format($totalPrice ?? 0, 0) }} Credits</span>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-700/50 flex items-center gap-2 text-sm">
                <span class="text-slate-500">Se vinci riceverai:</span>
                <span class="text-amber-400 font-bold">{{ number_format(($totalPrice ?? 0) * 10, 0) }} Credits</span>
                <span class="text-emerald-500/80">(10x)</span>
            </div>
        </div>
        
        {{-- Trust Badges --}}
        <div class="flex items-center justify-center gap-4 py-2 text-xs text-slate-500">
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4 text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                </svg>
                Transazione sicura
            </span>
            <span class="flex items-center gap-1">
                <svg class="w-4 h-4 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
                Instant payout
            </span>
        </div>
        
        {{-- Buttons --}}
        <div class="grid grid-cols-2 gap-3">
            <button 
                type="button"
                wire:click="buy"
                wire:loading.attr="disabled"
                class="btn-buy inline-flex items-center justify-center gap-2 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-400 active:from-emerald-700 active:to-emerald-600 text-white font-bold py-4 px-4 rounded-xl transition-all duration-200 min-h-[56px] focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-slate-900 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-emerald-500/20"
                aria-label="Compra quote"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
                </svg>
                <span>Compra</span>
            </button>
            <button 
                type="button"
                wire:click="sell"
                wire:loading.attr="disabled"
                class="btn-sell inline-flex items-center justify-center gap-2 bg-gradient-to-r from-rose-600 to-pink-500 hover:from-rose-500 hover:to-pink-400 active:from-rose-700 active:to-pink-600 text-white font-bold py-4 px-4 rounded-xl transition-all duration-200 min-h-[56px] focus:outline-none focus:ring-2 focus:ring-rose-500 focus:ring-offset-2 focus:ring-offset-slate-900 disabled:opacity-50 disabled:cursor-not-allowed shadow-lg shadow-rose-500/20"
                aria-label="Vendi quote"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
                </svg>
                <span>Vendi</span>
            </button>
        </div>
        
        {{-- Loading State --}}
        <div wire:loading wire:target="placeOrder" class="text-center py-2">
            <div class="inline-block animate-spin rounded-full h-5 w-5 border-b-2 border-emerald-500"></div>
            <span class="ml-2 text-slate-400 text-sm">Elaborazione...</span>
        </div>
        
        {{-- Error Message --}}
        @if($errors->has('quantity'))
            <div class="mt-3 p-3 bg-rose-500/10 border border-rose-500/20 rounded-lg text-rose-400 text-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ $errors->first('quantity') }}
            </div>
        @endif
        
        @if(session()->has('error'))
            <div class="mt-3 p-3 bg-rose-500/10 border border-rose-500/20 rounded-lg text-rose-400 text-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('error') }}
            </div>
        @endif
        
        @if(session()->has('success'))
            <div class="mt-3 p-3 bg-emerald-500/10 border border-emerald-500/20 rounded-lg text-emerald-400 text-sm flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                {{ session('success') }}
            </div>
        @endif
    </form>
</div>
