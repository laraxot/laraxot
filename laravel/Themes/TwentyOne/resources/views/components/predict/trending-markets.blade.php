@props([
    'title' => __('predict::home.trending_markets.label'),
    'markets' => [],
])

@php
    $markets = collect($markets);
    if ($markets->isEmpty()) {
        $markets = \Modules\Predict\Models\Predict::query()
            ->with(['ratings'])
            ->whereIn('status', ['active', 'open', 'published'])
            ->whereRaw('(sum_credit_yes + sum_credit_no) > 0')
            ->orderByRaw('(sum_credit_yes + sum_credit_no) DESC')
            ->limit(4)
            ->get();
    }
@endphp

@if($markets->isNotEmpty())
{{-- Trending Markets Section --}}
<section class="py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        {{-- Section Header --}}
        <div class="flex items-center justify-between mb-8">
            <div>
                <h2 class="text-3xl font-bold text-slate-900 dark:text-white mb-2 flex items-center gap-2">
                    <x-filament::icon icon="heroicon-o-fire" class="h-8 w-8 text-orange-500" aria-hidden="true" />
                    {{ $title }}
                </h2>
                <p class="text-slate-600 dark:text-slate-400">
                    I mercati più popolari di oggi
                </p>
            </div>
            <a href="{{ url('/' . app()->getLocale() . '/predicts') }}" 
               class="inline-flex items-center gap-2 text-indigo-600 dark:text-indigo-400 font-semibold hover:text-indigo-700 dark:hover:text-indigo-300 transition-colors">
                Show all
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>

        {{-- Markets Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            @foreach($markets as $market)
                @php
                    $mTitle = $market->title;
                    if (is_array($mTitle)) {
                        $locale = app()->getLocale();
                        $mTitle = $mTitle[$locale] ?? $mTitle['en'] ?? (is_string(reset($mTitle)) ? reset($mTitle) : '');
                    }
                    
                    $sumTotal = (float) ($market->sum_credit_yes ?? 0) + (float) ($market->sum_credit_no ?? 0);
                    $mVolume = round($sumTotal / 100, 0);
                    $mParticipants = (int) ($market->count_credit_yes ?? 0) + (int) ($market->count_credit_no ?? 0);
                    $isHot = $mVolume > 200;
                @endphp

                <a href="{{ url('/' . app()->getLocale() . '/predicts/' . $market->slug) }}" 
                   class="group bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 p-5 hover:shadow-lg hover:border-indigo-300 dark:hover:border-indigo-700 transition-all duration-200">
                    
                    {{-- Hot Badge --}}
                    @if($isHot)
                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-bold bg-gradient-to-r from-orange-500 to-red-500 text-white mb-3">
                        <x-filament::icon icon="heroicon-o-fire" class="h-3.5 w-3.5" aria-hidden="true" />
                        Hot
                    </span>
                    @endif

                    {{-- Title --}}
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white mb-3 line-clamp-2 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">

                        {{ Str::limit($mTitle, 60) }}
                    </h3>

                    {{-- Volume & Participants --}}
                    <div class="flex items-center gap-3 text-xs text-slate-500 dark:text-slate-400 mb-4">
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                            </svg>
                            {{ number_format($mParticipants) }}
                        </span>
                        <span class="flex items-center gap-1">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 5.293a1 1 0 00-1.414-1.414L9 11.172 7.707 9.879a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <x-filament::icon icon="predict-currency" class="h-4 w-4 shrink-0" aria-hidden="true" />
                            {{ number_format($mVolume) }} CAPS
                        </span>
                    </div>

                    {{-- Top Outcome --}}
                    @if($market->ratings->isNotEmpty())
                        @php
                            $topRating = $market->ratings->sortByDesc('percentage')->first();
                            $topTitle = $topRating->title ?? 'Outcome';
                            if (is_array($topTitle)) {
                                $topTitle = $topTitle[$locale] ?? $topTitle['en'] ?? 'Outcome';
                            }
                            $topPercentage = round($topRating->percentage ?? 0, 1);
                        @endphp
                        <div class="flex items-center justify-between">
                            <span class="text-xs text-slate-500 dark:text-slate-400 truncate">
                                {{ Str::limit($topTitle, 20) }}
                            </span>
                            <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">
                                {{ $topPercentage }}%
                            </span>
                        </div>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@push('translations')
<script>
    if (typeof window.predict === 'undefined') {
        window.predict = {};
    }
    window.predict.homepage = window.predict.homepage || {};
    window.predict.homepage.trending = {
        title: 'Trending Now'
    };
</script>
@endpush
