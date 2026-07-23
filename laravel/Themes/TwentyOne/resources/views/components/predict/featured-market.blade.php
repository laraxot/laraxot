@props([
    'predict' => \Modules\Predict\Models\Predict::query()
        ->with(['ratings'])
        ->whereIn('status', ['active', 'open', 'published'])
        ->whereRaw('(sum_credit_yes + sum_credit_no) > 0')
        ->orderByRaw('(sum_credit_yes + sum_credit_no) DESC')
        ->first(),
])

@if(!$predict)
    @php
        $predict = \Modules\Predict\Models\Predict::query()->first();
    @endphp
@endif

@if($predict)
@php
    $title = $predict->title;
    if (is_array($title)) {
        $locale = app()->getLocale();
        $title = $title[$locale] ?? $title['en'] ?? (is_string(reset($title)) ? reset($title) : '');
    }

    $sumYes = (float) ($predict->sum_credit_yes ?? 0);
    $sumNo = (float) ($predict->sum_credit_no ?? 0);
    $total = $sumYes + $sumNo;
    $volume = round($total / 100, 0);
    $isHot = $volume > 200;
    
    $endDate = $predict->closed_at ? \Carbon\Carbon::parse($predict->closed_at) : null;
@endphp

{{-- Featured Market Card - Polymarket Style --}}
<section class="relative overflow-hidden bg-gradient-to-br from-indigo-900 via-slate-900 to-indigo-950 rounded-3xl shadow-2xl border border-indigo-500/30">
    
    {{-- Background Pattern --}}
    <div class="absolute inset-0 opacity-5">

        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.15) 1px, transparent 0); background-size: 24px 24px;"></div>
    </div>

    {{-- Hot Badge --}}
    @if($isHot)
    <div class="absolute top-4 right-4 z-10">
        <span class="inline-flex items-center gap-2 px-4 py-2 rounded-full text-sm font-bold bg-gradient-to-r from-orange-500 to-red-500 text-white shadow-lg shadow-orange-500/30">

            <span class="relative flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-white"></span>
            </span>
            <x-filament::icon icon="heroicon-o-fire" class="h-4 w-4" aria-hidden="true" /> TRENDING
        </span>
    </div>
    @endif

    <div class="relative p-6 md:p-8 lg:p-10">
        {{-- Header --}}
        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-6">
            <div class="flex-1">
                {{-- Category Tag --}}
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold bg-indigo-500/20 text-indigo-300 border border-indigo-500/30 mb-3">

                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    {{ $predict->category->title ?? 'Featured' }}
                </div>

                {{-- Title --}}
                <h2 class="text-2xl md:text-3xl lg:text-4xl font-extrabold text-white mb-2">

                    {{ Str::limit($title, 80) }}
                </h2>

                {{-- Description --}}
                <p class="text-indigo-200 text-sm md:text-base mb-4">

                    {{ Str::limit($predict->description ?? 'Prevedi il risultato di questo evento', 120) }}
                </p>

                {{-- Meta Info --}}
                <div class="flex flex-wrap items-center gap-4 text-sm">
                    <div class="flex items-center gap-2 text-emerald-400">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-bold inline-flex items-center gap-1">{{ number_format($volume) }} <x-filament::icon icon="predict-currency" class="h-4 w-4" aria-hidden="true" /> Volume</span>
                    </div>
                    @if($endDate)
                    <div class="flex items-center gap-2 text-amber-400">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-semibold">Ends {{ $endDate->diffForHumans(['short' => true]) }}</span>
                    </div>
                    @endif
                </div>
            </div>

            {{-- CTA Button --}}
            <div class="flex-shrink-0">
                <a href="{{ url('/' . app()->getLocale() . '/predicts/' . $predict->slug) }}" 
                   class="btn-kinetic inline-flex items-center gap-3 px-8 py-4 bg-gradient-to-r from-indigo-500 to-violet-500 hover:from-indigo-600 hover:to-violet-600 text-white font-bold rounded-xl shadow-lg shadow-indigo-500/30 hover:shadow-xl hover:shadow-indigo-500/50 transition-all duration-200">
                    Trade Now
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>
        </div>

        {{-- Multi-Outcome Display --}}
        @if($predict->ratings->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($predict->ratings->take(6) as $index => $rating)
                @php
                    $ratingTitle = $rating->title ?? 'Outcome ' . ($index + 1);
                    if (is_array($ratingTitle)) {
                        $ratingTitle = $ratingTitle[$locale] ?? $ratingTitle['en'] ?? 'Outcome ' . ($index + 1);
                    }
                    $percentage = round($rating->percentage ?? 0, 1);
                    $colorClass = $percentage > 60 ? 'bg-emerald-500' : ($percentage < 40 ? 'bg-rose-500' : 'bg-amber-500');
                @endphp

                <div class="card-kinetic bg-white/5 backdrop-blur-sm rounded-xl p-4 border border-white/10 hover:border-indigo-500/50">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-white truncate">{{ Str::limit($ratingTitle, 25) }}</span>
                        <span class="text-lg font-bold text-white">{{ $percentage }}%</span>
                    </div>
                    <div class="w-full bg-white/10 rounded-full h-2 overflow-hidden">
                        <div class="h-full {{ $colorClass }} rounded-full transition-all duration-500" 
                             style="width: {{ $percentage }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
        @endif

        {{-- Bottom Stats --}}
        <div class="mt-6 pt-6 border-t border-white/10 flex flex-wrap gap-6 text-sm">
            <div class="flex items-center gap-2 text-indigo-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span>{{ number_format($predict->count_credit_yes + $predict->count_credit_no) }} Participants</span>
            </div>
            <div class="flex items-center gap-2 text-indigo-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                <span>{{ $predict->ratings->count() }} Outcomes</span>
            </div>
        </div>
    </div>
</section>
@endif
