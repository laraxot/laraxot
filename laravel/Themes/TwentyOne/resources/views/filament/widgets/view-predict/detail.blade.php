<?php

use Modules\Predict\Models\Predict;

$record = $getRecord();
if (!$record instanceof Predict) {
    return '';
}

$card = app(\Modules\Predict\Actions\Frontoffice\ResolvePredictCardDataAction::class)->execute($record);

$visibleOptions = collect($card['options'])->values();
$outcomesCount = $visibleOptions->count();
$marketShapeLabel = $outcomesCount > 2 ? 'Multi-esito' : 'Binario';

$statusClasses = match($card['status_tone']) {
    'active' => 'border-emerald-400/30 bg-emerald-500/12 text-emerald-400',
    'open' => 'border-blue-400/30 bg-blue-500/12 text-blue-400',
    'published' => 'border-purple-400/30 bg-purple-500/12 text-purple-400',
    default => 'border-slate-400/30 bg-slate-500/18 text-slate-300',
};
?>

{{-- Skip Link for Accessibility --}}
<a href="#predict-main-content" class="skip-link sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 focus:z-50 focus:px-4 focus:py-2 focus:bg-slate-900 focus:text-white focus:rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-500">
    Salta al contenuto principale
</a>

{{-- Breadcrumb Navigation --}}
<nav aria-label="Breadcrumb" class="mb-6">
    <ol class="flex items-center gap-2 text-sm">
        <li>
            <a href="{{ url('/' . app()->getLocale()) }}" class="text-slate-400 hover:text-white transition-colors flex items-center gap-1">
                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span>Home</span>
            </a>
        </li>
        <li class="text-slate-600">/</li>
        <li>
            <a href="{{ url('/' . app()->getLocale() . '/predicts') }}" class="text-slate-400 hover:text-white transition-colors">
                Mercati
            </a>
        </li>
        <li class="text-slate-600">/</li>
        <li class="text-white font-medium truncate max-w-[200px]">{{ $card['title'] }}</li>
    </ol>
</nav>

<article class="predict-detail-kinetic" aria-labelledby="predict-title">
    <header class="mb-8">
        {{-- Back Button --}}
        <a href="{{ url('/' . app()->getLocale() . '/predicts') }}"
           class="inline-flex items-center gap-2 text-slate-400 hover:text-white transition-colors mb-4 group"
        >
            <svg class="h-4 w-4 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            <span>Torna ai mercati</span>
        </a>
        <div class="flex flex-wrap items-center gap-2 mb-3">
            <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-wider {{ $statusClasses }}" role="status">
                {{ $card['status_label'] }}
            </span>
            @if($card['category_title'])
                <span class="inline-flex items-center rounded-full border border-white/10 bg-white/6 px-3 py-1 text-xs font-medium uppercase tracking-wider text-slate-200">
                    {{ $card['category_title'] }}
                </span>
            @endif
        </div>

        {{-- H1 for SEO & Accessibility --}}
        <h1 id="predict-title" class="text-3xl sm:text-4xl font-black text-white leading-tight">
            {{ $card['title'] ?: 'Mercato in aggiornamento' }}
        </h1>
        
        {{-- Meta info with Icons --}}
        <div class="flex flex-wrap items-center gap-4 mt-3">
            <span class="flex items-center gap-2 text-slate-400 text-sm">
                <svg class="h-4 w-4 text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
                <time datetime="{{ $record->resolution_at?->format('Y-m-d') ?? '' }}">
                    Risoluzione: {{ $record->resolution_at?->format('d/m/Y') ?? 'Da definire' }}
                </time>
            </span>
            @if($record->created_at)
            <span class="flex items-center gap-2 text-slate-400 text-sm">
                <svg class="h-4 w-4 text-violet-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <time datetime="{{ $record->created_at->format('Y-m-d') }}">
                    Creato: {{ $record->created_at->format('d/m/Y') }}
                </time>
            </span>
            @endif
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Main Content --}}
        <main id="predict-main-content" class="lg:col-span-2 space-y-6">
            {{-- Outcomes Section with Enhanced Cards --}}
            <section aria-labelledby="outcomes-heading" class="relative">
                <div class="flex items-center justify-between mb-4">
                    <h2 id="outcomes-heading" class="text-lg font-bold text-white flex items-center gap-2">
                        <svg class="h-5 w-5 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Esiti Disponibili
                    </h2>
                    <span class="text-xs text-slate-500 uppercase tracking-wider">{{ $marketShapeLabel }}</span>
                </div>
                
                <div class="space-y-4">
                @foreach($visibleOptions as $index => $option)
                    @php
                        $optionTitle = is_string($option['title'] ?? null) ? $option['title'] : 'Opzione';
                        $optionImage = is_string($option['image_url'] ?? null) && $option['image_url'] !== ''
                            ? $option['image_url']
                            : 'https://source.unsplash.com/800x450/?'.urlencode($optionTitle);
                        $percentage = isset($option['percentage']) ? (float) $option['percentage'] : 0.0;
                        $odds = isset($option['odds']) ? (float) $option['odds'] : 0.0;
                        $progressWidth = isset($option['progress_width']) ? (float) $option['progress_width'] : $percentage;
                        $color = is_string($option['color'] ?? null) ? $option['color'] : '#6366f1';
                        $isLeading = $index === 0 && $percentage > 50;
                        $isUnderdog = $index > 0 && $percentage < 30;
                    @endphp
                    <div class="group relative">
                        {{-- Leading/Underdog Badge --}}
                        @if($isLeading)
                        <div class="absolute -top-2 -right-2 z-10">
                            <span class="inline-flex items-center gap-1 rounded-full bg-gradient-to-r from-emerald-500 to-cyan-500 px-3 py-1 text-xs font-bold text-slate-950 shadow-lg shadow-emerald-500/30">
                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                                Favorto
                            </span>
                        </div>
                        @endif
                        @if($isUnderdog)
                        <div class="absolute -top-2 -right-2 z-10">
                            <span class="inline-flex items-center gap-1 rounded-full bg-gradient-to-r from-amber-500 to-orange-500 px-3 py-1 text-xs font-bold text-slate-950 shadow-lg shadow-amber-500/30">
                                <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z" clip-rule="evenodd" />
                                </svg>
                                Sorpresa
                            </span>
                        </div>
                        @endif
                        <a href="{{ $card['detail_url'] }}?option={{ $index }}"
                           class="block overflow-hidden rounded-2xl border border-white/10 bg-white/5 transition-all duration-300 hover:border-white/20 hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-slate-950"
                           aria-label="{{ $option['aria_label'] ?? 'Vedi dettagli per ' . $optionTitle }}"
                        >
                            <div class="relative aspect-video">
                                <img src="{{ $optionImage }}" alt="{{ $optionTitle }}" class="h-full w-full object-cover" loading="lazy">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent"></div>
                                <div class="absolute bottom-0 left-0 right-0 p-4">
                                    {{-- Progress Bar --}}
                                    <div class="mb-3 h-2 w-full overflow-hidden rounded-full bg-white/10">
                                        <div class="h-full rounded-full transition-all duration-500" style="width: {{ $progressWidth }}%; background: linear-gradient(90deg, {{ $color }}, color-mix(in srgb, {{ $color }} 55%, white));"></div>
                                    </div>
                                    <div class="flex items-end justify-between">
                                        <div>
                                            <span class="text-xl font-bold text-white">{{ $optionTitle }}</span>
                                            @if($odds > 0)
                                            <span class="ml-2 text-sm font-medium text-slate-400">@ {{ $odds }}x</span>
                                            @endif
                                        </div>
                                        <span class="rounded-full bg-slate-950/80 px-4 py-2 text-2xl font-bold text-white" aria-label="Probabilità {{ number_format($percentage, 1) }} percento">
                                            {{ number_format($percentage, 1) }}%
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endforeach
                </div>
            </section>
            
            {{-- Trading Form Section --}}
            <section aria-labelledby="trading-heading">
                <h2 id="trading-heading" class="sr-only">Piazza Ordine</h2>
                <x-predict.trading-form :predict="$record" />
            </section>
        </main>

        {{-- Sidebar --}}
        <aside class="lg:col-span-1" aria-label="Statistiche del mercato">
            <div class="space-y-4">
                {{-- Main Stats Card --}}
                <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6">
                    <h3 class="mb-4 text-lg font-bold text-white flex items-center gap-2">
                        <svg class="h-5 w-5 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                        </svg>
                        Statistiche
                    </h3>
                    <dl class="space-y-4">
                        <div class="flex justify-between items-center py-2 border-b border-slate-800">
                            <dt class="text-slate-400 flex items-center gap-2">
                                <svg class="h-4 w-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Volume
                            </dt>
                            <dd class="font-bold text-white">{{ number_format($card['volume'], 0, ',', '.') }} Credits</dd>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-800">
                            <dt class="text-slate-400 flex items-center gap-2">
                                <svg class="h-4 w-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Trader
                            </dt>
                            <dd class="font-bold text-white">{{ number_format($card['participants']) }}</dd>
                        </div>
                        <div class="flex justify-between items-center py-2 border-b border-slate-800">
                            <dt class="text-slate-400 flex items-center gap-2">
                                <svg class="h-4 w-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                </svg>
                                Opzioni
                            </dt>
                            <dd class="font-bold text-white">{{ $card['total_options'] }}</dd>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <dt class="text-slate-400 flex items-center gap-2">
                                <svg class="h-4 w-4 text-rose-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                                </svg>
                                Tipo
                            </dt>
                            <dd class="font-bold text-white">{{ $card['is_binary'] ? 'Binario' : 'Multi-esito' }}</dd>
                        </div>
                        <div class="flex justify-between items-center py-2">
                            <dt class="text-slate-400 flex items-center gap-2">
                                <svg class="h-4 w-4 text-amber-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14" />
                                </svg>
                                Opzioni
                            </dt>
                            <dd class="font-bold text-white">{{ $outcomesCount }}</dd>
                        </div>
                    </dl>
                </div>

                {{-- How It Works Card --}}
                <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6">
                    <h3 class="mb-4 text-lg font-bold text-white flex items-center gap-2">
                        <svg class="h-5 w-5 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        Come Funziona
                    </h3>
                    <ol class="space-y-3 text-sm">
                        <li class="flex gap-3">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-cyan-500/20 text-xs font-bold text-cyan-400">1</span>
                            <span class="text-slate-300">Scegli un esito e decidi quanto puntare</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-500/20 text-xs font-bold text-blue-400">2</span>
                            <span class="text-slate-300">Se indovini ricevi 10x la puntata</span>
                        </li>
                        <li class="flex gap-3">
                            <span class="flex h-6 w-6 items-center justify-center rounded-full bg-emerald-500/20 text-xs font-bold text-emerald-400">3</span>
                            <span class="text-slate-300">I crediti vengono accreditati subito</span>
                        </li>
                    </ol>
                </div>

                {{-- Share Card --}}
                <div class="rounded-2xl border border-slate-800 bg-slate-900/50 p-6">
                    <h3 class="mb-4 text-lg font-bold text-white flex items-center gap-2">
                        <svg class="h-5 w-5 text-violet-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z" />
                        </svg>
                        Condividi
                    </h3>
                    <div class="flex gap-3">
                        <button class="flex-1 flex items-center justify-center gap-2 rounded-lg bg-slate-800 hover:bg-slate-700 py-2 text-sm text-slate-300 transition-colors">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                            X
                        </button>
                        <button class="flex-1 flex items-center justify-center gap-2 rounded-lg bg-slate-800 hover:bg-slate-700 py-2 text-sm text-slate-300 transition-colors">
                            <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.85.38-1.78.64-2.75.76 1-.6 1.76-1.55 2.12-2.68-.93.55-1.96.95-3.06 1.17-.88-.94-2.13-1.53-3.51-1.53-2.66 0-4.81 2.16-4.81 4.81 0 .38.04.75.13 1.1-4-.2-7.58-2.11-9.96-5.02-.42.72-.66 1.56-.66 2.46 0 1.68.85 3.16 2.14 4.02-.79-.02-1.53-.24-2.18-.6v.06c0 2.35 1.67 4.31 3.88 4.76-.4.1-.83.16-1.27.16-.31 0-.62-.03-.92-.08.63 1.96 2.45 3.39 4.61 3.43-1.69 1.32-3.83 2.1-6.15 2.1-.4 0-.8-.02-1.19-.07 2.19 1.4 4.78 2.22 7.57 2.22 9.07 0 14.02-7.52 14.02-14.02 0-.21 0-.43-.01-.64.96-.69 1.79-1.56 2.45-2.55z"/>
                            </svg>
                            Telegram
                        </button>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</article>
