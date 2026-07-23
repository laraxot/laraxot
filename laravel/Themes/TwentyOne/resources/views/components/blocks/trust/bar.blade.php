{{--
  Trust bar stile Kalshi: 3 link credibilità sotto hero.
  Accessibile: nav con aria-label, link con min 44x44px.
  Scroll reveal: entra in viewport con fade-in-up (rispetta prefers-reduced-motion).
--}}
@php
    $tCredits     = 'Credits Virtuali';
    $tTransparent = 'Trasparenza Totale';
    $tGovernance  = 'Governance Community';
    $tAria        = 'Valori e trasparenza della piattaforma';
    $locale = app()->getLocale();
@endphp
<nav class="border-b border-white/10 bg-slate-900/95 backdrop-blur-xl animate-fade-in-up" aria-label="{{ $tAria }}">
    <div class="px-4 py-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex flex-wrap items-center justify-center gap-6 sm:gap-10">
            <a href="{{ url('/' . $locale . '/pages/credits') }}"
               class="inline-flex items-center gap-2 min-h-[44px] min-w-[44px] text-sm font-medium text-slate-300 hover:text-cyan-400 focus:outline-none focus:ring-2 focus:ring-cyan-500 focus:ring-offset-2 focus:ring-offset-slate-900 rounded-lg px-2 py-2 transition-colors"
               aria-label="{{ $tCredits }}">
                <x-filament::icon icon="heroicon-o-banknotes" class="w-5 h-5 text-cyan-400" aria-hidden="true" />
                <span>{{ $tCredits }}</span>
            </a>
            <a href="{{ url('/' . $locale . '/pages/trasparenza') }}"
               class="inline-flex items-center gap-2 min-h-[44px] min-w-[44px] text-sm font-medium text-slate-300 hover:text-emerald-400 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 focus:ring-offset-slate-900 rounded-lg px-2 py-2 transition-colors"
               aria-label="{{ $tTransparent }}">
                <x-filament::icon icon="heroicon-o-eye" class="w-5 h-5 text-emerald-400" aria-hidden="true" />
                <span>{{ $tTransparent }}</span>
            </a>
            <a href="{{ url('/' . $locale . '/pages/governance') }}"
               class="inline-flex items-center gap-2 min-h-[44px] min-w-[44px] text-sm font-medium text-slate-300 hover:text-violet-400 focus:outline-none focus:ring-2 focus:ring-violet-500 focus:ring-offset-2 focus:ring-offset-slate-900 rounded-lg px-2 py-2 transition-colors"
               aria-label="{{ $tGovernance }}">
                <x-filament::icon icon="heroicon-o-shield-check" class="w-5 h-5 text-violet-400" aria-hidden="true" />
                <span>{{ $tGovernance }}</span>
            </a>
        </div>
    </div>
</nav>
