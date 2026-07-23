{{--
  Market Card con Immagine e Barra Percentuale
  Supporta sia binary (SI/NO) che multi-outcome (3-6+ opzioni)
  Usage: <x-predict.market-card :predict="$predict" />
--}}
@props(['predict'])

@php
    $probability = $predict->probability ?? 50;
    $volume = $predict->volume ?? 0;
    $participants = $predict->participants ?? 0;
    $isHot = $probability > 60 || $volume > 1000;

    // Check if predict has multiple outcomes (ratings)
    $ratings = $predict->ratings ?? null;
    if (!$ratings && method_exists($predict, 'ratings')) {
        $ratings = $predict->ratings()->get();
    }
    $isMultiOutcome = $ratings && $ratings->count() > 2;

    // Calculate outcome percentages if multi-outcome
    $outcomes = [];
    if ($isMultiOutcome) {
        $outcomes = app(\Modules\Predict\Actions\CalculateOutcomePercentages::class)->execute($predict);
    }
@endphp

<div class="predict-market-card group relative bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-1">
    {{-- Image Header --}}
    <div class="relative h-48 overflow-hidden bg-gray-100">
        @if($predict->getFirstMediaUrl('main_image'))
            <img 
                src="{{ $predict->getFirstMediaUrl('main_image') }}" 
                alt="{{ $predict->title }}"
                loading="lazy"
                class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
            >
        @else
            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-emerald-100 to-blue-100">
                <x-filament::icon icon="heroicon-o-trophy" class="w-16 h-16 text-emerald-300" />
            </div>
        @endif

        {{-- Hot Badge --}}
        @if($isHot)
            <div class="absolute top-3 right-3">
                <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full text-xs font-bold bg-orange-500 text-white shadow-lg shadow-orange-500/50 animate-pulse">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M12.395 2.553a1 1 0 00-1.45-.385c-.345.23-.614.558-.822.88-.214.33-.403.713-.57 1.116-.334.804-.614 1.768-.84 2.734a31.365 31.365 0 00-.613 3.58 2.64 2.64 0 01-.945-1.067c-.328-.68-.398-1.534-.398-2.654A1 1 0 005.05 6.05 6.981 6.981 0 003 11a7 7 0 1011.95-4.95c-.592-.591-.98-.985-1.348-1.467-.363-.476-.724-1.063-1.207-2.03zM12.12 15.12A3 3 0 017 13s.879.5 2.5.5c0-1 .5-4 1.25-4.5.5 1 .786 1.293 1.371 1.879A2.99 2.99 0 0113 13a2.99 2.99 0 01-.879 2.121z" clip-rule="evenodd" />
                    </svg>
                    HOT
                </span>
            </div>
        @endif

        {{-- Status Badge --}}
        <div class="absolute top-3 left-3">
            <span class="px-3 py-1 rounded-full text-xs font-bold bg-emerald-500 text-white shadow-lg">
                {{ ucfirst($predict->status ?? 'active') }}
            </span>
        </div>
    </div>

    {{-- Content --}}
    <div class="p-5">
        {{-- Title --}}
        <h3 class="text-lg font-bold text-gray-900 mb-4 line-clamp-2 min-h-[3.5rem]">
            {{ $predict->title }}
        </h3>

        {{-- Multi-Outcome Grid --}}
        @if($isMultiOutcome && count($outcomes) > 0)
            <div class="mb-5">
                <div class="grid grid-cols-2 gap-3">
                    @foreach($outcomes as $outcome)
                        <x-predict.multi-outcome-card
                            :outcome="$outcome"
                            :predict="$predict"
                        />
                    @endforeach
                </div>
            </div>
        @else
            {{-- Binary Outcome (SI/NO Classic) --}}
            <div class="mb-5">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-medium text-gray-600">Probabilità</span>
                    <span class="text-lg font-bold text-emerald-600">{{ number_format($probability, 1) }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-5 overflow-hidden shadow-inner">
                    <div
                        class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-full rounded-full transition-all duration-1000 ease-out shadow-lg"
                        style="width: {{ $probability }}%"
                    ></div>
                </div>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-2 gap-3 mb-5">
                <div class="bg-gray-50 rounded-lg p-3">
                    <div class="flex items-center gap-2 mb-1">
                        <x-filament::icon icon="heroicon-o-currency-dollar" class="w-4 h-4 text-gray-400" />
                        <span class="text-xs text-gray-500">Volume</span>
                    </div>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($volume, 0, ',', '.') }} CR</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3">
                    <div class="flex items-center gap-2 mb-1">
                        <x-filament::icon icon="heroicon-o-user-group" class="w-4 h-4 text-gray-400" />
                        <span class="text-xs text-gray-500">Traders</span>
                    </div>
                    <p class="text-lg font-bold text-gray-900">{{ number_format($participants) }}</p>
                </div>
            </div>
        @endif

        {{-- CTA Button --}}
        <a 
            href="{{ url('/' . app()->getLocale() . '/predicts/' . $predict->slug) }}"
            class="group/btn relative block w-full py-4 px-6 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white text-center font-bold rounded-xl hover:from-emerald-600 hover:to-emerald-700 transition-all duration-300 shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/50 overflow-hidden"
        >
            <span class="relative z-10 flex items-center justify-center gap-2">
                Trade Now
                <x-filament::icon icon="heroicon-o-arrow-right" class="w-5 h-5 group-hover/btn:translate-x-1 transition-transform" />
            </span>
            <div class="absolute inset-0 bg-gradient-to-r from-emerald-400 to-emerald-500 opacity-0 group-hover/btn:opacity-100 transition-opacity duration-300"></div>
        </a>
    </div>
</div>

@push('styles')
<style>
.predict-market-card {
    will-change: transform, box-shadow;
}

.predict-market-card img {
    will-change: transform;
}
</style>
@endpush
