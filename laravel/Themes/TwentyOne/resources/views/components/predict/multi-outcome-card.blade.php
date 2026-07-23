{{--
  Multi-Outcome Card Component
  Usage: <x-predict.multi-outcome-card :outcome="$outcome" :predict="$predict" />

  Features:
  - Image with integrated percentage bar
  - Label below image
  - Click opens modal with details
  - Real data from database (NOT hardcoded)
--}}
@props(['outcome', 'predict'])

@php
    $percentage = (float) ($outcome['percentage'] ?? 0);
    $label = is_array($outcome['label'] ?? null)
        ? ($outcome['label'][app()->getLocale()] ?? $outcome['label']['en'] ?? 'Unknown')
        : ($outcome['label'] ?? 'Unknown');
    $imageUrl = $outcome['image_url'] ?? null;
    $volume = (float) ($outcome['volume'] ?? 0);
    $outcomeId = $outcome['id'] ?? null;
    $transactionsCount = (int) ($outcome['transactions_count'] ?? 0);

    // Generate initials for fallback image
    $initials = \Illuminate\Support\Str::upper(\Illuminate\Support\Str::substr($label, 0, 2));

    // Translation keys
    $volumeLabel = __('predict::common.labels.volume.label');
    $volumeLabel = is_string($volumeLabel) && $volumeLabel !== 'predict::labels.volume'
        ? $volumeLabel
        : 'Volume';

    $tradersLabel = __('predict::common.labels.traders.label');
    $tradersLabel = is_string($tradersLabel) && $tradersLabel !== 'predict::labels.traders'
        ? $tradersLabel
        : 'Traders';
@endphp

<div
    x-data="{ open: false }"
    class="multi-outcome-card group relative"
>
    {{-- Card Container --}}
    <div
        @click="open = true"
        class="cursor-pointer overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-lg transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl dark:border-gray-700 dark:bg-gray-800"
        role="button"
        tabindex="0"
        @keydown.enter="open = true"
        @keydown.space.prevent="open = true"
        aria-label="{{ $label }} - {{ number_format($percentage, 1) }}% probabilità"
    >
        {{-- Image Header with Overlay --}}
        <div class="relative aspect-[4/3] overflow-hidden bg-gray-100 dark:bg-gray-700">
            {{-- Image or Fallback --}}
            @if($imageUrl)
                <img
                    src="{{ $imageUrl }}"
                    alt="{{ $label }}"
                    loading="lazy"
                    class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                >
            @else
                <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-emerald-500 to-blue-600">
                    <span class="text-5xl font-black tracking-tight text-white">{{ $initials }}</span>
                </div>
            @endif

            {{-- Percentage Badge (Large, Top Right) --}}
            <div class="absolute top-3 right-3 rounded-full bg-white/95 px-4 py-2 shadow-lg backdrop-blur-sm">
                <span class="text-3xl font-bold text-emerald-600">
                    {{ number_format($percentage, 1) }}%
                </span>
            </div>

            {{-- Integrated Progress Bar (Bottom) --}}
            <div class="absolute bottom-0 left-0 right-0 h-2 bg-gray-200/90 dark:bg-gray-600/90">
                <div
                    class="h-full bg-gradient-to-r from-emerald-500 to-emerald-600 transition-all duration-1000 ease-out"
                    style="width: {{ min(100, $percentage) }}%"
                ></div>
            </div>

            {{-- Hover Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
        </div>

        {{-- Label Below Image --}}
        <div class="p-4">
            <p class="text-center text-sm font-semibold text-gray-900 dark:text-white line-clamp-2 min-h-[2.5rem]">
                {{ $label }}
            </p>
        </div>
    </div>

    {{-- Modal --}}
    <div
        x-show="open"
        x-cloak
        @click.self="open = false"
        class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto overflow-x-hidden bg-black/60 p-4 backdrop-blur-sm"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-title-{{ $outcomeId }}"
    >
        <div
            x-show="open"
            x-transition:enter="ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="relative w-full max-w-2xl rounded-3xl bg-white p-6 shadow-2xl dark:bg-gray-800"
        >
            {{-- Close Button --}}
            <button
                @click="open = false"
                type="button"
                class="absolute top-4 right-4 rounded-full bg-gray-100 p-2 text-gray-500 transition-colors hover:bg-gray-200 hover:text-gray-700 dark:bg-gray-700 dark:text-gray-400 dark:hover:bg-gray-600 dark:hover:text-gray-200"
                aria-label="Chiudi modale"
            >
                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            {{-- Modal Content --}}
            <div class="space-y-6">
                {{-- Image --}}
                <div class="aspect-[16/9] overflow-hidden rounded-2xl bg-gray-100 dark:bg-gray-700">
                    @if($imageUrl)
                        <img
                            src="{{ $imageUrl }}"
                            alt="{{ $label }}"
                            loading="lazy"
                            class="h-full w-full object-cover"
                        >
                    @else
                        <div class="flex h-full w-full items-center justify-center bg-gradient-to-br from-emerald-500 to-blue-600">
                            <span class="text-7xl font-black tracking-tight text-white">{{ $initials }}</span>
                        </div>
                    @endif
                </div>

                {{-- Title --}}
                <h3
                    id="modal-title-{{ $outcomeId }}"
                    class="text-2xl font-bold text-gray-900 dark:text-white"
                >
                    {{ $label }}
                </h3>

                {{-- Stats Grid --}}
                <div class="grid grid-cols-3 gap-4">
                    {{-- Percentage --}}
                    <div class="rounded-xl bg-emerald-50 p-4 dark:bg-emerald-900/20">
                        <p class="text-sm font-medium text-emerald-700 dark:text-emerald-300">Probabilità</p>
                        <p class="mt-1 text-3xl font-bold text-emerald-600 dark:text-emerald-400">
                            {{ number_format($percentage, 1) }}%
                        </p>
                    </div>

                    {{-- Volume --}}
                    <div class="rounded-xl bg-blue-50 p-4 dark:bg-blue-900/20">
                        <p class="text-sm font-medium text-blue-700 dark:text-blue-300">{{ $volumeLabel }}</p>
                        <p class="mt-1 text-2xl font-bold text-blue-600 dark:text-blue-400">
                            {{ number_format($volume, 0, ',', '.') }}
                        </p>
                        <p class="text-xs text-blue-500 dark:text-blue-400">Credits</p>
                    </div>

                    {{-- Traders --}}
                    <div class="rounded-xl bg-purple-50 p-4 dark:bg-purple-900/20">
                        <p class="text-sm font-medium text-purple-700 dark:text-purple-300">{{ $tradersLabel }}</p>
                        <p class="mt-1 text-2xl font-bold text-purple-600 dark:text-purple-400">
                            {{ number_format($transactionsCount) }}
                        </p>
                    </div>
                </div>

                {{-- Progress Bar --}}
                <div>
                    <div class="mb-2 flex items-center justify-between">
                        <span class="text-sm font-medium text-gray-600 dark:text-gray-400">Distribuzione voti</span>
                        <span class="text-sm font-bold text-emerald-600 dark:text-emerald-400">{{ number_format($percentage, 1) }}%</span>
                    </div>
                    <div class="h-4 overflow-hidden rounded-full bg-gray-200 dark:bg-gray-700">
                        <div
                            class="h-full bg-gradient-to-r from-emerald-500 to-emerald-600 transition-all duration-1000 ease-out"
                            style="width: {{ min(100, $percentage) }}%"
                        ></div>
                    </div>
                </div>

                {{-- CTA Button --}}
                <a
                    href="{{ url('/' . app()->getLocale() . '/predicts/' . $predict->slug) }}"
                    class="block w-full py-4 text-center font-bold text-white bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-xl hover:from-emerald-600 hover:to-emerald-700 transition-all duration-300 shadow-lg shadow-emerald-500/30 hover:shadow-xl hover:shadow-emerald-500/50"
                >
                    Vedi Dettagli Mercato
                </a>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
[x-cloak] {
    display: none !important;
}

.multi-outcome-card {
    will-change: transform, box-shadow;
}

.multi-outcome-card img {
    will-change: transform;
}
</style>
@endpush
