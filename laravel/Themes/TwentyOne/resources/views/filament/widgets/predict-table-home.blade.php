{{--
  Predict Table Widget - Versione homepage con hot badge, probabilità evidenti, CTA inline.
  Design: card cinetiche, badge colorati, progress bars per probabilità.
  Accessibilità: aria-label, focus indicators, target 44x44px.
--}}
@php
    use Illuminate\Support\Facades\Schema;

    $predictClass = 'Modules\\Predict\\Models\\Predict';
    $predicts = [];

    if (class_exists($predictClass)) {
        $predictModel = new $predictClass();
        $tableName = $predictModel->getTable();
        $connectionName = $predictModel->getConnectionName();

        $predictsQuery = $predictClass::query()
            ->with(['ratings', 'category'])
            ->latest('created_at');

        if (Schema::connection($connectionName)->hasColumn($tableName, 'is_active')) {
            $predictsQuery->where('is_active', true);
        }

        if (Schema::connection($connectionName)->hasColumn($tableName, 'show_on_homepage')) {
            $predictsQuery->where('show_on_homepage', true);
        }

        $predicts = $predictsQuery->limit(20)->get(); // ✅ Mostra 20 record
    }

    $title = $title ?? 'Mercati in Evidenza';
    $subtitle = $subtitle ?? 'Scopri i prediction market più popolari';
@endphp

<section class="relative overflow-hidden bg-gradient-to-b from-slate-800 to-slate-900 py-16 md:py-24">
    {{-- Background decorative --}}
    <div class="absolute inset-0" aria-hidden="true">
        <div class="absolute left-1/2 top-0 h-96 w-96 -translate-x-1/2 rounded-full bg-cyan-500/5 blur-3xl"></div>
    </div>

    <div class="relative z-10 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        {{-- Header --}}
        <div class="mb-12 text-center">
            <h2 class="mb-4 text-3xl font-black tracking-tight text-white sm:text-4xl md:text-5xl">
                {{ $title }}
            </h2>
            <p class="mx-auto max-w-2xl text-lg text-slate-300">
                {{ $subtitle }}
            </p>
        </div>

        {{-- Predicts Grid --}}
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($predicts as $predict)
                @php
                    $isHot = $predict->volume > 10000 || $predict->transactions()->count() > 100;
                    $isTrending = $predict->transactions()->where('created_at', '>', now()->subDays(7))->count() > 20;
                    $leadOutcome = collect($predict->getRatingsPercentageByVolume())
                        ->sortByDesc('percentage')
                        ->first();
                    $participants = $predict->transactions()->distinct('user_id')->count('user_id') ?: 0;
                @endphp

                <div class="reveal-kinetic group card-kinetic relative overflow-hidden rounded-2xl border border-white/10 bg-slate-800/50 backdrop-blur transition-all duration-300 hover:scale-105 hover:border-cyan-400/30 hover:shadow-xl hover:shadow-cyan-500/10" data-kinetic-block="fade-up">
                    {{-- Hot Badge --}}
                    @if($isHot)
                        <div class="absolute -right-2 -top-2 z-20">
                            <span class="inline-flex items-center gap-1 rounded-full border border-orange-400/30 bg-orange-500/20 px-3 py-1 text-xs font-bold text-orange-300 backdrop-blur">
                                <x-filament::icon icon="heroicon-s-fire" class="h-3 w-3" />
                                HOT
                            </span>
                        </div>
                    @endif

                    {{-- Trending Badge --}}
                    @if($isTrending && !$isHot)
                        <div class="absolute -right-2 -top-2 z-20">
                            <span class="inline-flex items-center gap-1 rounded-full border border-cyan-400/30 bg-cyan-500/20 px-3 py-1 text-xs font-bold text-cyan-300 backdrop-blur">
                                <x-filament::icon icon="heroicon-s-arrow-trending-up" class="h-3 w-3" />
                                Trending
                            </span>
                        </div>
                    @endif

                    {{-- Image --}}
                    <div class="relative h-48 overflow-hidden">
                        @if($predict->main_image_url)
                            <img
                                src="{{ $predict->main_image_url }}"
                                alt="{{ $predict->title }}"
                                loading="lazy"
                                class="h-full w-full object-cover transition-transform duration-500 group-hover:scale-110"
                            >
                        @else
                            <div class="flex h-full items-center justify-center bg-gradient-to-br from-slate-700 via-slate-800 to-slate-900">
                                <x-filament::icon icon="heroicon-o-chart-bar" class="h-16 w-16 text-slate-600" />
                            </div>
                        @endif

                        {{-- Overlay gradient --}}
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-900 via-slate-900/20 to-transparent"></div>

                        {{-- Category badge --}}
                        @if($predict->category)
                            <div class="absolute left-4 top-4">
                                <span class="inline-flex rounded-full border border-white/20 bg-white/10 px-3 py-1 text-xs font-semibold text-white backdrop-blur">
                                    {{ ucfirst($predict->category) }}
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Content --}}
                    <div class="p-5">
                        <h3 class="mb-3 line-clamp-2 text-lg font-bold text-white">
                            {{ $predict->title }}
                        </h3>

                        {{-- Lead Outcome with Probability --}}
                        @if($leadOutcome)
                            <div class="mb-4">
                                <div class="mb-2 flex items-center justify-between text-sm">
                                    <span class="truncate text-slate-300">{{ $leadOutcome['title'] ?? 'Outcome' }}</span>
                                    <span class="text-base font-black text-cyan-400">
                                        {{ number_format((float) ($leadOutcome['percentage'] ?? 0), 1, ',', '.') }}%
                                    </span>
                                </div>
                                <div class="h-3 overflow-hidden rounded-full bg-white/10">
                                    <div
                                        class="probability-bar-animated h-full rounded-full bg-gradient-to-r from-cyan-400 via-sky-400 to-indigo-400"
                                        style="width: {{ max(4, min(100, (float) ($leadOutcome['percentage'] ?? 0))) }}%"
                                    ></div>
                                </div>
                            </div>
                        @endif

                        {{-- Stats --}}
                        <div class="mb-5 flex items-center justify-between gap-3 border-t border-white/10 pt-4 text-xs text-slate-400">
                            <span class="inline-flex items-center gap-1">
                                <x-filament::icon icon="heroicon-o-users" class="h-4 w-4" />
                                {{ number_format($participants, 0, ',', '.') }}
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <x-filament::icon icon="heroicon-o-currency-dollar" class="h-4 w-4" />
                                {{ number_format($predict->volume ?? 0, 0, ',', '.') }}
                            </span>
                            <span class="inline-flex items-center gap-1">
                                <x-filament::icon icon="heroicon-o-calendar" class="h-4 w-4" />
                                {{ $predict->resolution_date ? \Carbon\Carbon::parse($predict->resolution_date)->format('d/m/y') : '--' }}
                            </span>
                        </div>

                        {{-- CTA Button --}}
                        <a href="{{ url(app()->getLocale().'/predicts/'.$predict->slug) }}"
                           class="btn-kinetic inline-flex w-full items-center justify-center gap-2 rounded-xl border border-cyan-400/30 bg-gradient-to-r from-cyan-500 to-sky-500 px-6 py-3 text-base font-bold text-slate-950 shadow-lg shadow-cyan-500/20 transition-all duration-300 hover:scale-105 hover:shadow-xl hover:shadow-cyan-500/30">
                            <span>Prevedi Ora</span>
                            <x-filament::icon icon="heroicon-o-arrow-right" class="h-5 w-5" />
                        </a>
                    </div>
                </div>
            @empty
                {{-- Empty state --}}
                <div class="col-span-full rounded-2xl border border-dashed border-white/10 bg-slate-800/30 p-12 text-center">
                    <x-filament::icon icon="heroicon-o-chart-bar" class="mx-auto mb-4 h-16 w-16 text-slate-600" />
                    <h3 class="mb-2 text-xl font-bold text-white">Nessun mercato disponibile</h3>
                    <p class="text-slate-400">Torna presto per nuovi prediction market!</p>
                </div>
            @endforelse
        </div>

        {{-- View All CTA --}}
        <div class="mt-12 text-center">
            <a href="{{ url(app()->getLocale().'/predicts') }}"
               class="inline-flex items-center gap-2 rounded-full border border-white/20 bg-white/10 px-8 py-4 text-base font-semibold text-white backdrop-blur transition-all duration-300 hover:bg-white/20">
                <span>Vedi Tutti i Mercati</span>
                <x-filament::icon icon="heroicon-o-arrow-right" class="h-5 w-5" />
            </a>
        </div>
    </div>
</section>
