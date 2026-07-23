@props([
    'hero_title' => null,
    'hero_subtitle' => null,
    'cta_primary' => null,
    'cta_secondary' => null,
])

@php
    $hero = app(\Themes\TwentyOne\Actions\Hero\ResolveCinematicHeroDataAction::class)
        ->execute($hero_title, $hero_subtitle, $cta_primary, $cta_secondary);
    $particlesColor = 'rgba(125,211,252,0.48)';
@endphp

<section class="antigravity-field relative overflow-hidden bg-[radial-gradient(circle_at_top,rgba(14,165,233,0.16),transparent_22%),linear-gradient(180deg,#020617_0%,#0f172a_55%,#020617_100%)]" data-antigravity-field>
    <x-ui.particles count="84" :color="$particlesColor" size="3px" zIndex="0" variant="antigravity" />
    <div class="antigravity-grid" aria-hidden="true"></div>
    <div class="antigravity-spotlight" aria-hidden="true"></div>
    <div class="antigravity-orb antigravity-orb-1" aria-hidden="true"></div>
    <div class="antigravity-orb antigravity-orb-2" aria-hidden="true"></div>
    <div class="antigravity-orb antigravity-orb-3" aria-hidden="true"></div>

    <div class="container relative z-10 mx-auto px-4 pb-16 pt-24 md:pb-24 md:pt-28">
        <div class="grid items-center gap-10 lg:grid-cols-[minmax(0,1.05fr)_minmax(340px,0.95fr)] lg:gap-14">
            <div class="max-w-3xl">
                <div class="reveal-kinetic inline-flex items-center gap-2 rounded-full border border-sky-300/20 bg-sky-400/10 px-4 py-2 text-xs font-semibold uppercase tracking-[0.26em] text-sky-100" data-kinetic-block="fade-up" data-kinetic-delay="0">
                    <x-filament::icon icon="heroicon-o-sparkles" class="h-4 w-4" />
                    <span>{{ $hero['eyebrow'] }}</span>
                </div>

                <h1 class="reveal-kinetic mt-6 max-w-4xl text-5xl font-black leading-[0.94] tracking-tight text-white md:text-7xl xl:text-[5.5rem]" data-kinetic-block="fade-up" data-kinetic-delay="80">
                    <span class="bg-gradient-to-r from-white via-sky-200 to-cyan-300 bg-clip-text text-transparent">
                        {{ $hero['title'] }}
                    </span>
                </h1>

                <p class="reveal-kinetic mt-6 max-w-2xl text-lg leading-8 text-slate-300 md:text-xl" data-kinetic-block="fade-up" data-kinetic-delay="140">
                    {{ $hero['subtitle'] }}
                </p>

                <div class="reveal-kinetic mt-8 flex flex-wrap gap-3" data-kinetic-block="fade-up" data-kinetic-delay="220">
                    @foreach($hero['highlights'] as $highlight)
                        <span class="inline-flex items-center gap-2 rounded-full border border-white/10 bg-white/6 px-4 py-2 text-sm text-slate-200 backdrop-blur">
                            <span class="h-2 w-2 rounded-full bg-cyan-300"></span>
                            <span>{{ $highlight }}</span>
                        </span>
                    @endforeach
                </div>

                <div class="reveal-kinetic mt-10 flex flex-col gap-4 sm:flex-row" data-kinetic-block="fade-up" data-kinetic-delay="300">
                    <a
                        href="{{ $hero['primaryCta']['url'] }}"
                        class="btn-kinetic inline-flex items-center justify-center gap-2 rounded-full border border-cyan-300/20 bg-gradient-to-r from-cyan-500 to-sky-500 px-7 py-4 text-base font-semibold text-slate-950 shadow-[0_18px_50px_rgba(14,165,233,0.3)]"
                    >
                        <span>{{ $hero['primaryCta']['text'] }}</span>
                        <x-filament::icon icon="heroicon-o-arrow-right" class="h-5 w-5" />
                    </a>

                    <a
                        href="{{ $hero['secondaryCta']['url'] }}"
                        class="btn-kinetic inline-flex items-center justify-center gap-2 rounded-full border border-white/12 bg-white/8 px-7 py-4 text-base font-semibold text-white backdrop-blur"
                    >
                        <x-filament::icon icon="heroicon-o-user-plus" class="h-5 w-5" />
                        <span>{{ $hero['secondaryCta']['text'] }}</span>
                    </a>
                </div>

                <div class="reveal-kinetic mt-10 grid gap-4 sm:grid-cols-3" data-kinetic-block="fade-up" data-kinetic-delay="380">
                    @foreach($hero['stats'] as $stat)
                        <div class="hero-stat-card card-kinetic rounded-[1.6rem] border border-white/10 bg-white/6 p-5 backdrop-blur">
                            <div class="flex items-center justify-between gap-3">
                                <div>
                                    <div class="text-3xl font-black tracking-tight text-white md:text-4xl">{{ $stat['value'] }}</div>
                                    <div class="mt-1 text-sm text-slate-300">{{ $stat['label'] }}</div>
                                </div>

                                <span class="inline-flex h-11 w-11 items-center justify-center rounded-2xl border border-white/10 bg-white/8 text-sky-200">
                                    <x-filament::icon :icon="$stat['icon']" class="h-5 w-5" />
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="reveal-kinetic hero-spotlight-shell rounded-[2rem] border border-white/10 bg-white/6 p-4 shadow-[0_30px_90px_rgba(2,6,23,0.42)] backdrop-blur md:p-5" data-kinetic-block="fade-left" data-kinetic-delay="180">
                <div class="mb-4 flex items-center justify-between gap-4">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.28em] text-sky-200/80">Mercati visuali</p>
                        <h2 class="mt-1 text-2xl font-black tracking-tight text-white">Le risposte si capiscono al primo sguardo</h2>
                    </div>

                    <span class="hidden rounded-full border border-emerald-300/20 bg-emerald-400/10 px-3 py-1 text-xs font-semibold text-emerald-100 md:inline-flex">
                        4+ opzioni
                    </span>
                </div>

                <div class="grid gap-4">
                    @forelse($hero['spotlightCards'] as $card)
                        <a href="{{ $card['url'] }}" class="card-kinetic hero-market-tile group block overflow-hidden rounded-[1.7rem] border border-white/10 bg-slate-950/70">
                            <div class="grid gap-0 md:grid-cols-[1.05fr_0.95fr]">
                                <div class="relative min-h-[250px] overflow-hidden bg-slate-900">
                                    @if($card['lead_outcome'] && $card['lead_outcome']['image_url'])
                                        <img
                                            src="{{ $card['lead_outcome']['image_url'] }}"
                                            alt="{{ $card['lead_outcome']['title'] }}"
                                            loading="lazy"
                                            class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                        >
                                    @elseif($card['image_url'])
                                        <img
                                            src="{{ $card['image_url'] }}"
                                            alt="{{ $card['title'] }}"
                                            loading="lazy"
                                            class="h-full w-full object-cover transition duration-700 group-hover:scale-105"
                                        >
                                    @else
                                        <div class="flex h-full items-center justify-center bg-gradient-to-br from-slate-800 via-slate-900 to-sky-950">
                                            <x-filament::icon icon="heroicon-o-photo" class="h-12 w-12 text-slate-500" />
                                        </div>
                                    @endif

                                    <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-slate-950/40 to-transparent"></div>
                                    <div class="absolute bottom-4 left-4 right-4">
                                        @if($card['category'])
                                            <span class="inline-flex rounded-full border border-white/12 bg-slate-950/72 px-3 py-1 text-xs font-semibold text-slate-100 backdrop-blur">
                                                {{ $card['category'] }}
                                            </span>
                                        @endif

                                        @if($card['lead_outcome'])
                                            <div class="mt-3 max-w-xs rounded-[1.15rem] border border-white/12 bg-slate-950/72 p-3 backdrop-blur">
                                                <p class="text-xs uppercase tracking-[0.24em] text-slate-300">Leader</p>
                                                <div class="mt-2 flex items-center justify-between gap-3">
                                                    <span class="text-sm font-semibold text-white">{{ $card['lead_outcome']['title'] }}</span>
                                                    <span class="text-base font-black text-cyan-300">{{ number_format($card['lead_outcome']['percentage'], 1, ',', '.') }}%</span>
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <div class="flex flex-col justify-between p-5">
                                    <div>
                                        <h3 class="text-xl font-black leading-tight text-white transition group-hover:text-sky-200">
                                            {{ $card['title'] }}
                                        </h3>

                                        <div class="mt-4 space-y-3">
                                            @foreach(array_slice($card['outcomes'], 0, 4) as $outcome)
                                                <div>
                                                    <div class="mb-1 flex items-center justify-between gap-3 text-sm">
                                                        <span class="truncate text-slate-200">{{ $outcome['title'] }}</span>
                                                        <span class="font-semibold text-white">{{ number_format($outcome['percentage'], 1, ',', '.') }}%</span>
                                                    </div>
                                                    <div class="h-2 overflow-hidden rounded-full bg-white/10">
                                                        <div class="probability-bar-animated h-full rounded-full bg-gradient-to-r from-cyan-400 via-sky-400 to-indigo-400" style="width: {{ max(4, min(100, $outcome['percentage'])) }}%"></div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="mt-5 flex flex-wrap items-center justify-between gap-3 border-t border-white/10 pt-4 text-sm text-slate-300">
                                        <span>{{ number_format($card['participants']) }} partecipanti</span>
                                        <span>{{ number_format($card['volume'], 0, ',', '.') }} crediti</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    @empty
                        <div class="rounded-[1.7rem] border border-dashed border-white/15 bg-slate-950/55 p-8 text-center text-slate-300">
                            <p class="text-lg font-semibold text-white">Nessun mercato visuale disponibile</p>
                            <p class="mt-2 text-sm">Popola il database con mercati multi-outcome e abilita `show_on_homepage` per vedere qui i migliori.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
