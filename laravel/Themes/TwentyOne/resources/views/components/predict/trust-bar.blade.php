@props([
    'sources' => [
        ['name' => 'CNN', 'icon' => 'heroicon-o-tv'],
        ['name' => 'AP News', 'icon' => 'heroicon-o-newspaper'],
        ['name' => 'Reuters', 'icon' => 'heroicon-o-globe-alt'],
        ['name' => 'Bloomberg', 'icon' => 'heroicon-o-chart-bar'],
        ['name' => 'WSJ', 'icon' => 'heroicon-o-arrow-trending-up'],
    ],
])

{{-- Trust Bar - News Sources (Polymarket Style) --}}
<section class="bg-slate-900/50 border-y border-slate-800 py-6" aria-label="As featured in">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row items-center justify-between gap-4">
            <div class="text-center md:text-left">
                <p class="text-sm font-semibold text-slate-400 uppercase tracking-wider">
                    As featured on
                </p>
            </div>
            
            <ul class="flex flex-wrap items-center justify-center gap-6 md:gap-8" role="list" aria-label="News sources">
                @foreach($sources as $source)
                <li>
                    <a href="#" 
                       class="flex items-center gap-2 text-slate-500 hover:text-slate-300 transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-slate-900 rounded px-2 py-1 -mx-2 min-h-[44px]"
                       aria-label="{{ $source['name'] }}">
                        <x-filament::icon :icon="$source['icon'] ?? 'heroicon-o-newspaper'" class="h-6 w-6" aria-hidden="true" />
                        <span class="font-bold text-sm">{{ $source['name'] }}</span>
                    </a>
                </li>
                @endforeach
            </ul>
            
            <div class="text-center md:text-right">
                <p class="text-xs text-slate-500">
                    Trusted by 50K+ predictors worldwide
                </p>
            </div>
        </div>
    </div>
</section>
