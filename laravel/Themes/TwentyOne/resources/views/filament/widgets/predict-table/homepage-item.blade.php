@php
    $record = $getRecord();
    $card = app(\Modules\Predict\Actions\Frontoffice\ResolvePredictHomepageCardDataAction::class)->execute($record);
@endphp

<article
    x-data="{ activeOutcome: null }"
    class="card-kinetic group relative overflow-hidden rounded-[2rem] border border-white/12 bg-transparent shadow-[0_28px_80px_rgba(2,6,23,0.35)]"
>
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_top_left,rgba(56,189,248,0.18),transparent_28%),radial-gradient(circle_at_bottom_right,rgba(14,165,233,0.16),transparent_32%)]"></div>
    <div class="pointer-events-none absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-sky-300/50 to-transparent"></div>

    <div class="relative p-5 md:p-6">
        <div class="mb-5 flex items-start justify-between gap-4">
            <div class="space-y-3">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="inline-flex items-center rounded-full border px-3 py-1 text-[11px] font-semibold uppercase tracking-[0.24em] {{ $card['status_classes'] }}">
                        {{ $card['status_label'] }}
                    </span>

                    @if($card['category_title'])
                        <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-medium text-slate-200">
                            {{ $card['category_title'] }}
                        </span>
                    @endif
                </div>

                <h3 class="max-w-2xl text-xl font-black leading-tight tracking-tight text-white md:text-2xl">
                    <a href="{{ $card['detail_url'] }}" class="transition hover:text-sky-200 focus:outline-none focus:underline">
                        {{ $card['title'] }}
                    </a>
                </h3>
            </div>

            <a
                href="{{ $card['detail_url'] }}"
                class="btn-kinetic hidden shrink-0 items-center gap-2 rounded-full border border-sky-300/25 bg-sky-400/15 px-4 py-2 text-sm font-semibold text-white md:inline-flex"
            >
                <span>{{ $card['open_market_label'] }}</span>
                <x-filament::icon icon="heroicon-o-arrow-up-right" class="h-4 w-4" />
            </a>
        </div>

        <div class="grid grid-cols-2 gap-3 md:gap-4">
            @foreach($card['visible_options'] as $option)
                <button
                    type="button"
                    class="group/option relative overflow-hidden rounded-[1.35rem] border border-white/10 bg-white/6 text-left shadow-[0_18px_50px_rgba(15,23,42,0.24)] transition duration-300 hover:-translate-y-1 hover:border-white/20 hover:shadow-[0_24px_60px_rgba(14,165,233,0.18)] focus:outline-none focus:ring-2 focus:ring-sky-300"
                    x-on:click="activeOutcome = {{ \Illuminate\Support\Js::from($option) }}"
                    aria-label="{{ $option['aria_label'] }}"
                >
                    <span class="relative block aspect-[4/3] overflow-hidden bg-slate-900">
                        @if($option['image_url'])
                            <img
                                src="{{ $option['image_url'] }}"
                                alt="{{ $option['title'] }}"
                                loading="lazy"
                                class="h-full w-full object-cover transition duration-700 group-hover/option:scale-105"
                            >
                        @else
                            <span class="flex h-full items-center justify-center bg-gradient-to-br from-slate-800 via-slate-700 to-slate-900">
                                <x-filament::icon icon="heroicon-o-photo" class="h-10 w-10 text-slate-400" />
                            </span>
                        @endif

                        <span class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/30 to-transparent"></span>
                        <span class="absolute right-3 top-3 rounded-full border border-white/15 bg-slate-950/70 px-3 py-1 text-sm font-bold text-white backdrop-blur">
                            {{ $option['percentage_label'] }}
                        </span>

                        <span class="absolute inset-x-3 bottom-3 space-y-2">
                            <span class="block h-2 overflow-hidden rounded-full bg-white/15">
                                <span
                                    class="probability-bar-animated block h-full rounded-full"
                                    style="width: {{ $option['progress_width'] }}%; background: linear-gradient(90deg, {{ $option['color'] }}, color-mix(in srgb, {{ $option['color'] }} 55%, white));"
                                ></span>
                            </span>
                        </span>
                    </span>

                    <span class="block px-3 pb-4 pt-3">
                        <span class="block text-sm font-semibold text-white md:text-[15px]">
                            {{ $option['title'] }}
                        </span>
                    </span>
                </button>
            @endforeach
        </div>

        @if($card['remaining_options'] > 0)
            <p class="mt-3 text-sm text-slate-300">
                +{{ $card['remaining_options'] }} {{ $card['more_options_label'] }}
            </p>
        @endif

        <div class="mt-5 flex flex-wrap items-center justify-between gap-4 border-t border-white/10 pt-4 text-sm">
            <div class="flex flex-wrap items-center gap-4 text-slate-300">
                <span>
                    {{ $card['volume_label'] }}
                    <span class="font-semibold text-white">{{ number_format($card['volume'], 0, ',', '.') }}</span>
                </span>
                <span>
                    {{ $card['participants_label'] }}
                    <span class="font-semibold text-white">{{ number_format($card['participants']) }}</span>
                </span>
            </div>

            <a href="{{ $card['detail_url'] }}" class="text-sm font-semibold text-sky-300 transition hover:text-sky-200">
                {{ $card['slug'] }}
            </a>
        </div>

        <a
            href="{{ $card['detail_url'] }}"
            class="btn-kinetic mt-5 inline-flex w-full items-center justify-center gap-2 rounded-full border border-sky-300/25 bg-sky-400/15 px-4 py-3 text-sm font-semibold text-white md:hidden"
        >
            <span>{{ $card['open_market_label'] }}</span>
            <x-filament::icon icon="heroicon-o-arrow-up-right" class="h-4 w-4" />
        </a>
    </div>

    <div
        x-cloak
        x-show="activeOutcome"
        x-on:click.self="activeOutcome = null"
        x-on:keydown.escape.window="activeOutcome = null"
        class="fixed inset-0 z-[70] flex items-center justify-center bg-slate-950/72 p-4 backdrop-blur-md"
        role="dialog"
        aria-modal="true"
    >
        <div
            x-show="activeOutcome"
            x-transition.opacity
            x-transition.scale
            class="w-full max-w-2xl overflow-hidden rounded-[2rem] border border-white/12 bg-slate-950 text-white shadow-[0_35px_90px_rgba(2,6,23,0.65)]"
        >
            <div class="relative aspect-[16/9] overflow-hidden bg-slate-900">
                <template x-if="activeOutcome?.image_url">
                    <img :src="activeOutcome.image_url" :alt="activeOutcome.title" class="h-full w-full object-cover">
                </template>
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/35 to-transparent"></div>
                <button
                    type="button"
                    class="absolute right-4 top-4 inline-flex h-10 w-10 items-center justify-center rounded-full border border-white/15 bg-slate-950/70 text-white"
                    x-on:click="activeOutcome = null"
                    aria-label="Chiudi"
                >
                    <x-filament::icon icon="heroicon-o-x-mark" class="h-5 w-5" />
                </button>
            </div>

            <div class="space-y-5 p-6">
                <div class="flex items-start justify-between gap-4">
                    <h4 class="text-2xl font-black tracking-tight" x-text="activeOutcome?.title"></h4>
                    <span class="rounded-full border border-white/12 bg-white/6 px-3 py-1 text-lg font-bold" x-text="activeOutcome?.percentage_label"></span>
                </div>

                <div class="space-y-2">
                    <div class="flex items-center justify-between text-sm text-slate-300">
                        <span>Distribuzione crediti</span>
                        <span x-text="activeOutcome?.percentage_label"></span>
                    </div>
                    <div class="h-3 overflow-hidden rounded-full bg-white/10">
                        <div
                            class="probability-bar-animated h-full rounded-full"
                            :style="`width:${activeOutcome?.progress_width ?? 0}%; background: linear-gradient(90deg, ${activeOutcome?.color ?? '#38bdf8'}, color-mix(in srgb, ${activeOutcome?.color ?? '#38bdf8'} 55%, white));`"
                        ></div>
                    </div>
                </div>

                <a
                    href="{{ $card['detail_url'] }}"
                    class="btn-kinetic inline-flex w-full items-center justify-center gap-2 rounded-full border border-sky-300/25 bg-sky-400/15 px-4 py-3 text-sm font-semibold text-white"
                >
                    <span>{{ $card['open_market_label'] }}</span>
                    <x-filament::icon icon="heroicon-o-arrow-up-right" class="h-4 w-4" />
                </a>
            </div>
        </div>
    </div>
</article>
