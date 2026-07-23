@props(['predict'])

@php
    $locale = app()->getLocale();
    $titleValue = $predict?->title ?? null;
    $title = is_array($titleValue)
        ? ($titleValue[$locale] ?? $titleValue['it'] ?? $titleValue['en'] ?? 'Mercato')
        : ($titleValue ?: 'Mercato');
    $descriptionValue = $predict?->description ?? null;
    $description = is_array($descriptionValue)
        ? ($descriptionValue[$locale] ?? $descriptionValue['it'] ?? $descriptionValue['en'] ?? null)
        : $descriptionValue;
    $ratings = $predict?->ratings?->take(3) ?? collect();
    $topOutcomes = $ratings->map(function ($rating) use ($locale): array {
        $ratingTitle = is_array($rating->title)
            ? ($rating->title[$locale] ?? $rating->title['it'] ?? $rating->title['en'] ?? 'Outcome')
            : ($rating->title ?? 'Outcome');

        return [
            'title' => $ratingTitle,
            'color' => $rating->color ?? '#0f766e',
        ];
    });
    $expiration = $predict?->getExpirationDate();
    $betsCount = $predict?->count_credit ?? 0;
    $volume = $predict?->sum_credit ?? 0;
    $isBettable = (bool) ($predict?->is_bettable ?? false);
@endphp

@if ($predict)
    <section class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-slate-950 px-6 py-8 text-white shadow-2xl shadow-slate-950/30 sm:px-8 lg:px-10">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,_rgba(16,185,129,0.18),_transparent_35%),radial-gradient(circle_at_bottom_left,_rgba(59,130,246,0.18),_transparent_30%)]"></div>

        <div class="relative space-y-8">
            <div class="flex flex-wrap items-center gap-3">
                <span class="inline-flex items-center rounded-full border border-emerald-400/30 bg-emerald-400/10 px-3 py-1 text-xs font-semibold uppercase tracking-[0.24em] text-emerald-200">
                    Predict
                </span>
                @if ($expiration)
                    <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-medium text-slate-200">
                        {{ $expiration }}
                    </span>
                @endif
                <span class="inline-flex items-center rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-medium text-slate-200">
                    {{ $isBettable ? 'Mercato aperto' : 'Mercato chiuso' }}
                </span>
            </div>

            <div class="grid gap-8 lg:grid-cols-[minmax(0,1.5fr)_minmax(18rem,0.9fr)]">
                <div class="space-y-5">
                    <div class="space-y-3">
                        <h1 class="max-w-4xl text-3xl font-black leading-tight text-white sm:text-4xl lg:text-5xl">
                            {{ $title }}
                        </h1>
                        @if (filled($description))
                            <p class="max-w-3xl text-base leading-7 text-slate-300 sm:text-lg">
                                {{ $description }}
                            </p>
                        @endif
                    </div>

                    @if ($topOutcomes->isNotEmpty())
                        <div class="flex flex-wrap gap-3">
                            @foreach ($topOutcomes as $outcome)
                                <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/5 px-4 py-2 text-sm font-semibold text-white">
                                    <span class="h-2.5 w-2.5 rounded-full" style="background-color: {{ $outcome['color'] }}"></span>
                                    {{ $outcome['title'] }}
                                </span>
                            @endforeach
                        </div>
                    @endif

                    <div class="flex flex-wrap gap-4">
                        <a href="#predict-main-content" class="inline-flex items-center justify-center rounded-full bg-emerald-400 px-5 py-3 text-sm font-bold text-slate-950 transition hover:scale-[1.02] hover:bg-emerald-300">
                            Vai al mercato
                        </a>
                    </div>
                </div>

                <aside class="grid gap-4 sm:grid-cols-3 lg:grid-cols-1">
                    <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5 backdrop-blur">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Scommesse</p>
                        <p class="mt-3 text-3xl font-black text-white">{{ number_format((float) $betsCount, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5 backdrop-blur">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Volume</p>
                        <p class="mt-3 text-3xl font-black text-white">{{ number_format((float) $volume, 0, ',', '.') }}</p>
                    </div>
                    <div class="rounded-[1.5rem] border border-white/10 bg-white/5 p-5 backdrop-blur">
                        <p class="text-xs font-semibold uppercase tracking-[0.24em] text-slate-400">Outcome</p>
                        <p class="mt-3 text-3xl font-black text-white">{{ number_format((float) ($predict->ratings?->count() ?? 0), 0, ',', '.') }}</p>
                    </div>
                </aside>
            </div>
        </div>
    </section>
@endif
