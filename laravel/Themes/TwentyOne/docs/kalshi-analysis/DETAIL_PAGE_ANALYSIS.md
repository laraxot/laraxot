# Kalshi Detail Page Analysis

> **Data**: 2026-03-19 03:00 CET  
> **Fonte**: Kalshi.com Predict Detail Pattern  
> **Scopo**: Replicare e migliorare per Base Predict

---

## 📊 Layout Detail Page

```
┌────────────────────────────────────────────────────────┐
│  HEADER (Sticky, 64px)                                 │
│  [Logo] Markets [Search] Notifications [Profile]       │
├────────────────────────────────────────────────────────┤
│  BREADCRUMB                                            │
│  Markets > Politics > "Will Trump win 2024?"           │
├────────────────────────────────────────────────────────┤
│  MARKET TITLE (H1, 32px, Bold)                         │
│  "Will Bitcoin reach $100,000 by December 31, 2024?"   │
├────────────────────────────────────────────────────────┤
│  ┌──────────────────────┐ ┌──────────────────────┐    │
│  │  BUY YES             │ │  BUY NO              │    │
│  │  @ 65¢               │ │  @ 35¢               │    │
│  │  ┌────────────────┐  │ │  ┌────────────────┐  │    │
│  │  │   [Button]     │  │ │  │   [Button]     │  │    │
│  │  └────────────────┘  │ │  └────────────────┘  │    │
│  │  Max: $500           │ │  Max: $500           │    │
│  └──────────────────────┘ └──────────────────────┘    │
├────────────────────────────────────────────────────────┤
│  PROBABILITY BAR (Large, Animated)                     │
│  ████████████████░░░░░░░░░░░░░░░░ 65% YES             │
│  └─ Green gradient with shimmer effect ─┘             │
├────────────────────────────────────────────────────────┤
│  STATS ROW                                             │
│  Volume: $52,450 │ Participants: 1,234 │ Liquidity    │
├────────────────────────────────────────────────────────┤
│  TAB NAVIGATION                                        │
│  [Overview] [Order Book] [Rules] [Comments] [Chart]   │
├────────────────────────────────────────────────────────┤
│  CONTENT AREA                                          │
│  ┌─────────────────┐ ┌──────────────────┐             │
│  │  ORDER BOOK     │ │  RECENT TRADES    │             │
│  │  Yes   No  Qty  │ │  Time  Price Qty  │             │
│  │  65    35  100  │ │  14:32  65¢  50   │             │
│  │  64    36  250  │ │  14:30  64¢  100  │             │
│  │  63    37  500  │ │  14:28  65¢  75   │             │
│  └─────────────────┘ └──────────────────┘             │
├────────────────────────────────────────────────────────┤
│  MARKET RULES (Expandable)                             │
│  "This market will resolve to Yes if Bitcoin..."       │
│  [Show more ▼]                                         │
├────────────────────────────────────────────────────────┤
│  RELATED MARKETS                                       │
│  [Card] [Card] [Card] [Card]                           │
└────────────────────────────────────────────────────────┘
```

---

## 🎨 Componenti UI Detail Page

### 1. Buy/Sell Buttons (Sticky su Mobile)

```blade
{{-- Trade Buttons — Kalshi Style Improved —}}
<div class="trade-buttons sticky bottom-0 z-50 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 p-4 md:static md:bg-transparent md:dark:bg-transparent md:border-0 md:p-0">
    <div class="grid grid-cols-2 gap-4 max-w-2xl mx-auto">
        {{-- Buy YES —}}
        <button class="group relative overflow-hidden bg-gradient-to-br from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white font-black py-6 px-8 rounded-2xl transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-emerald-500/50 active:scale-95">
            <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
            <div class="relative z-10">
                <div class="text-sm font-bold opacity-90 mb-1">BUY YES</div>
                <div class="text-3xl font-black">65¢</div>
                <div class="text-xs font-semibold opacity-75 mt-1">Returns $1.00</div>
            </div>
        </button>
        
        {{-- Buy NO —}}
        <button class="group relative overflow-hidden bg-gradient-to-br from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 text-white font-black py-6 px-8 rounded-2xl transition-all duration-300 hover:scale-105 hover:shadow-2xl hover:shadow-rose-500/50 active:scale-95">
            <div class="absolute inset-0 bg-gradient-to-r from-white/20 to-transparent transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
            <div class="relative z-10">
                <div class="text-sm font-bold opacity-90 mb-1">BUY NO</div>
                <div class="text-3xl font-black">35¢</div>
                <div class="text-xs font-semibold opacity-75 mt-1">Returns $1.00</div>
            </div>
        </button>
    </div>
</div>
```

### 2. Probability Bar (Animated)

```blade
{{-- Probability Bar — Kalshi Style Improved —}}
<div class="probability-container my-8">
    <div class="flex justify-between items-center mb-3">
        <span class="text-emerald-600 dark:text-emerald-400 font-bold text-lg">YES</span>
        <span class="text-rose-600 dark:text-rose-400 font-bold text-lg">NO</span>
    </div>
    
    <div class="relative h-4 bg-slate-200 dark:bg-slate-700 rounded-full overflow-hidden shadow-inner">
        {{-- YES Bar —}}
        <div class="absolute left-0 top-0 h-full bg-gradient-to-r from-emerald-500 via-emerald-400 to-emerald-500 transition-all duration-1000 ease-out" 
             style="width: 65%">
            {{-- Shimmer effect —}}
            <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/30 to-transparent animate-shimmer"></div>
        </div>
        
        {{-- NO Bar (overlay) —}}
        <div class="absolute right-0 top-0 h-full bg-gradient-to-l from-rose-500 via-rose-400 to-rose-500 transition-all duration-1000 ease-out" 
             style="width: 35%">
            <div class="absolute inset-0 bg-gradient-to-l from-transparent via-white/30 to-transparent animate-shimmer" 
                 style="animation-delay: 0.5s"></div>
        </div>
        
        {{-- Percentage Label —}}
        <div class="absolute inset-0 flex items-center justify-center">
            <span class="text-white font-black text-sm drop-shadow-lg">65% PROBABILITÀ</span>
        </div>
    </div>
    
    <div class="flex justify-between mt-2 text-sm text-slate-600 dark:text-slate-400">
        <span>Improbable</span>
        <span>Certain</span>
    </div>
</div>

@push('styles')
<style>
@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}
.animate-shimmer {
    animation: shimmer 2s infinite;
}
</style>
@endpush
```

### 3. Order Book Display

```blade
{{-- Order Book — Kalshi Style Improved —}}
<div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
    <div class="p-4 border-b border-slate-200 dark:border-slate-700">
        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Order Book</h3>
    </div>
    
    <div class="p-4">
        {{-- Header —}}
        <div class="grid grid-cols-3 gap-2 pb-3 border-b-2 border-slate-200 dark:border-slate-700 mb-3">
            <div class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide">Yes</div>
            <div class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide text-center">Qty</div>
            <div class="text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wide text-right">No</div>
        </div>
        
        {{-- Rows —}}
        <div class="space-y-2">
            @foreach($orderBook as $row)
            <div class="group grid grid-cols-3 gap-2 p-3 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors cursor-pointer relative overflow-hidden">
                {{-- Depth background —}}
                <div class="absolute inset-0 bg-emerald-500/5" style="width: {{ $row['yesDepth'] }}%"></div>
                
                <div class="relative z-10">
                    <span class="text-emerald-600 dark:text-emerald-400 font-bold font-mono">{{ $row['yesPrice'] }}¢</span>
                </div>
                
                <div class="relative z-10 text-center">
                    <span class="text-slate-700 dark:text-slate-300 font-mono">{{ number_format($row['quantity']) }}</span>
                </div>
                
                <div class="relative z-10 text-right">
                    <span class="text-rose-600 dark:text-rose-400 font-bold font-mono">{{ $row['noPrice'] }}¢</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
```

### 4. Stats Display

```blade
{{-- Stats Grid — Kalshi Style Improved —}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
    <div class="bg-gradient-to-br from-blue-500/10 to-indigo-500/10 backdrop-blur-sm border border-blue-400/20 rounded-xl p-4">
        <div class="flex items-center gap-2 mb-2">
            <x-heroicon-o-chart-bar class="w-5 h-5 text-blue-500" />
            <span class="text-xs font-bold text-blue-600 dark:text-blue-400 uppercase">Volume</span>
        </div>
        <div class="text-2xl font-black text-slate-900 dark:text-white">$52,450</div>
        <div class="text-xs text-slate-600 dark:text-slate-400 mt-1">Ultimi 7 giorni</div>
    </div>
    
    <div class="bg-gradient-to-br from-purple-500/10 to-pink-500/10 backdrop-blur-sm border border-purple-400/20 rounded-xl p-4">
        <div class="flex items-center gap-2 mb-2">
            <x-heroicon-o-user-group class="w-5 h-5 text-purple-500" />
            <span class="text-xs font-bold text-purple-600 dark:text-purple-400 uppercase">Traders</span>
        </div>
        <div class="text-2xl font-black text-slate-900 dark:text-white">1,234</div>
        <div class="text-xs text-slate-600 dark:text-slate-400 mt-1">Partecipanti unici</div>
    </div>
    
    <div class="bg-gradient-to-br from-emerald-500/10 to-teal-500/10 backdrop-blur-sm border border-emerald-400/20 rounded-xl p-4">
        <div class="flex items-center gap-2 mb-2">
            <x-heroicon-o-currency-dollar class="w-5 h-5 text-emerald-500" />
            <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase">Liquidity</span>
        </div>
        <div class="text-2xl font-black text-slate-900 dark:text-white">$12,340</div>
        <div class="text-xs text-slate-600 dark:text-slate-400 mt-1">Nel order book</div>
    </div>
    
    <div class="bg-gradient-to-br from-orange-500/10 to-amber-500/10 backdrop-blur-sm border border-orange-400/20 rounded-xl p-4">
        <div class="flex items-center gap-2 mb-2">
            <x-heroicon-o-clock class="w-5 h-5 text-orange-500" />
            <span class="text-xs font-bold text-orange-600 dark:text-orange-400 uppercase">Ends In</span>
        </div>
        <div class="text-2xl font-black text-slate-900 dark:text-white">2d 14h</div>
        <div class="text-xs text-slate-600 dark:text-slate-400 mt-1">31 Dic 2024</div>
    </div>
</div>
```

### 5. Tab Navigation

```blade
{{-- Tab Navigation — Kalshi Style Improved —}}
<div class="border-b border-slate-200 dark:border-slate-700 mb-6 overflow-x-auto">
    <div class="flex gap-8 min-w-max">
        <button class="tab-button active py-4 px-2 border-b-2 border-blue-500 text-blue-600 dark:text-blue-400 font-bold transition-all" 
                data-tab="overview">
            Overview
        </button>
        <button class="tab-button py-4 px-2 border-b-2 border-transparent text-slate-600 dark:text-slate-400 font-medium hover:text-slate-900 dark:hover:text-white transition-all" 
                data-tab="orderbook">
            Order Book
        </button>
        <button class="tab-button py-4 px-2 border-b-2 border-transparent text-slate-600 dark:text-slate-400 font-medium hover:text-slate-900 dark:hover:text-white transition-all" 
                data-tab="rules">
            Rules
        </button>
        <button class="tab-button py-4 px-2 border-b-2 border-transparent text-slate-600 dark:text-slate-400 font-medium hover:text-slate-900 dark:hover:text-white transition-all" 
                data-tab="comments">
            Comments
        </button>
        <button class="tab-button py-4 px-2 border-b-2 border-transparent text-slate-600 dark:text-slate-400 font-medium hover:text-slate-900 dark:hover:text-white transition-all" 
                data-tab="chart">
            Chart
        </button>
    </div>
</div>

{{-- Tab Content —}}
<div class="tab-content active" data-content="overview">
    {{-- Overview content —}}
</div>
<div class="tab-content" data-content="orderbook">
    {{-- Order book content —}}
</div>
<div class="tab-content" data-content="rules">
    {{-- Rules content —}}
</div>
<div class="tab-content" data-content="comments">
    {{-- Comments content —}}
</div>
<div class="tab-content" data-content="chart">
    {{-- Chart content —}}
</div>

@push('scripts')
<script>
document.querySelectorAll('.tab-button').forEach(button => {
    button.addEventListener('click', function() {
        const tab = this.dataset.tab;
        
        // Update buttons
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('active', 'border-blue-500', 'text-blue-600', 'dark:text-blue-400');
            btn.classList.add('border-transparent', 'text-slate-600', 'dark:text-slate-400');
        });
        this.classList.add('active', 'border-blue-500', 'text-blue-600', 'dark:text-blue-400');
        this.classList.remove('border-transparent', 'text-slate-600', 'dark:text-slate-400');
        
        // Update content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.remove('active');
        });
        document.querySelector(`[data-content="${tab}"]`).classList.add('active');
    });
});
</script>
@endpush
```

---

## 🎯 Miglioramenti Rispetto a Kalshi

### 1. Più Moderno e Attraente

**Kalshi**: Design pulito ma un po' freddo  
**Base Predict**: Design caldo, gradienti, animazioni fluide

```blade
{{-- Base Predict — More Modern —}}
<div class="bg-gradient-to-br from-slate-900 via-blue-900 to-indigo-900">
    {{-- Animated orbs, particles, film grain —}}
    {{-- Glassmorphism cards —}}
    {{-- Smooth animations —}}
</div>
```

### 2. Dark Mode Nativa

```blade
{{-- Dark mode toggle —}}
<button @click="darkMode = !darkMode" 
        class="p-3 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 hover:scale-110 transition-all">
    <x-heroicon-o-sun x-show="darkMode" class="w-6 h-6 text-yellow-400" />
    <x-heroicon-o-moon x-show="!darkMode" class="w-6 h-6 text-blue-400" />
</button>
```

### 3. Gamification

```blade
{{-- Achievement badges —}}
<div class="flex gap-2 mt-4">
    <span class="inline-flex items-center gap-1 px-3 py-1 bg-gradient-to-r from-yellow-500/20 to-amber-500/20 border border-yellow-400/30 rounded-full text-yellow-600 dark:text-yellow-400 text-xs font-bold">
        <x-heroicon-o-trophy class="w-3 h-3" />
        Top Trader
    </span>
    <span class="inline-flex items-center gap-1 px-3 py-1 bg-gradient-to-r from-blue-500/20 to-indigo-500/20 border border-blue-400/30 rounded-full text-blue-600 dark:text-blue-400 text-xs font-bold">
        <x-heroicon-o-fire class="w-3 h-3" />
        7 Day Streak
    </span>
</div>
```

### 4. Mobile-First

```blade
{{-- Sticky buttons su mobile —}}
<div class="fixed bottom-0 left-0 right-0 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 p-4 md:static md:bg-transparent md:border-0 md:p-0">
    {{-- Trade buttons —}}
</div>

{{-- Bottom navigation —}}
<nav class="fixed bottom-0 left-0 right-0 bg-white dark:bg-slate-900 border-t border-slate-200 dark:border-slate-800 md:hidden">
    <div class="grid grid-cols-4 gap-1">
        <a href="/predicts" class="flex flex-col items-center py-3 text-blue-600 dark:text-blue-400">
            <x-heroicon-o-compass class="w-6 h-6" />
            <span class="text-xs font-bold mt-1">Markets</span>
        </a>
        {{-- Other nav items —}}
    </div>
</nav>
```

---

## 📁 Documentazione

### File nella Cartella

1. `HOMEPAGE_ANALYSIS.md` — Homepage analysis
2. `DETAIL_PAGE_ANALYSIS.md` — Questo file
3. `COLOR_PALETTE.md` — Colori e tipografia
4. `COMPONENTS.md` — Componenti UI

### Prossimi File

- [ ] `MOBILE_DESIGN.md` — Mobile-first design
- [ ] `DARK_MODE.md` — Dark mode implementation
- [ ] `GAMIFICATION.md` — Badges, streaks, XP
- [ ] `ANIMATIONS.md` — Animation patterns

---

**Creato**: 2026-03-19 03:00 CET  
**Stato**: ✅ **ANALISI DETTAGLIO COMPLETATA**  
**Prossimo Step**: Implementazione componenti
