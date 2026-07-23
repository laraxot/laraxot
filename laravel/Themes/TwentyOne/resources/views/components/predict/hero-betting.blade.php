@props([
    'title' => __('predict::home.hero.title.label'),
    'subtitle' => __('predict::home.hero.subtitle.label'),
    'badge' => __('predict::home.hero.badge.label'),
    'stats' => [
        ['value' => '500', 'label' => __('predict::home.hero.stats.free_credits.label')],
        ['value' => '10+', 'label' => __('predict::home.hero.stats.languages.label')],
        ['value' => '24/7', 'label' => __('predict::home.hero.stats.support.label')],
        ['value' => '100%', 'label' => __('predict::home.hero.stats.transparent.label')],
    ],
    'primaryCta' => [
        'text' => __('predict::home.hero.cta_explore.label'),
        'url' => \Mcamara\LaravelLocalization\Facades\LaravelLocalization::getLocalizedURL(app()->getLocale(), '/predicts') ?? url('/'.app()->getLocale().'/predicts'),
        'icon' => 'heroicon-m-compass',
    ],
    'secondaryCta' => [
        'text' => __('predict::home.hero.cta_learn.label'),
        'url' => '#how-it-works',
        'icon' => 'heroicon-m-play-circle',
    ],
])

{{-- Hero Section - Betting Template Style (Betwins-inspired) --}}
<section class="gradient-hero text-white relative overflow-hidden">
    
    {{-- Background Pattern --}}
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0" style="background-image: radial-gradient(circle at 2px 2px, rgba(255,255,255,0.15) 1px, transparent 0); background-size: 32px 32px;"></div>
    </div>
    
    {{-- Gradient Orbs --}}
    <div class="absolute top-20 left-10 w-72 h-72 bg-indigo-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob"></div>
    <div class="absolute top-40 right-10 w-72 h-72 bg-violet-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-20 w-72 h-72 bg-amber-500 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-4000"></div>

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 lg:py-32">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            
            {{-- Left Content --}}
            <div class="space-y-8">
                
                {{-- Badge --}}
                @if($badge)
                <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full glassmorphism text-sm font-semibold">
                    <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                    {{ $badge }}
                </div>
                @endif

                {{-- Headline --}}
                <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-extrabold tracking-tight leading-tight">
                    <span class="block">{{ Str::before($title, "\n") }}</span>
                    <span class="block bg-gradient-to-r from-indigo-400 via-violet-400 to-amber-400 bg-clip-text text-transparent">
                        {{ Str::after($title, "\n") ?: '' }}
                    </span>
                </h1>

                {{-- Subtitle --}}
                @if($subtitle)
                <p class="text-lg sm:text-xl text-indigo-200 max-w-2xl leading-relaxed">
                    {{ $subtitle }}
                </p>
                @endif

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row gap-4">
                    @if($primaryCta)
                    <a href="{{ $primaryCta['url'] }}" 
                       class="inline-flex items-center justify-center gap-2 px-8 py-4 
                              bg-gradient-to-r from-indigo-500 to-violet-500 
                              hover:from-indigo-600 hover:to-violet-600 
                              text-white font-bold rounded-xl 
                              shadow-lg shadow-indigo-500/30 
                              hover:shadow-xl hover:shadow-indigo-500/50 
                              hover:-translate-y-0.5 
                              focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:ring-offset-2 focus:ring-offset-slate-900
                              transition-all duration-200">
                        @if(isset($primaryCta['icon']))
                            <x-dynamic-component :component="$primaryCta['icon']" class="w-5 h-5" />
                        @endif
                        {{ $primaryCta['text'] }}
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                        </svg>
                    </a>
                    @endif

                    @if($secondaryCta)
                    <a href="{{ $secondaryCta['url'] }}" 
                       class="inline-flex items-center justify-center gap-2 px-8 py-4 
                              glassmorphism 
                              hover:bg-white/20 
                              text-white font-bold rounded-xl 
                              border-2 border-white/30 
                              hover:border-white/50 
                              focus:outline-none focus:ring-2 focus:ring-white/50 focus:ring-offset-2 focus:ring-offset-slate-900
                              transition-all duration-200">
                        @if(isset($secondaryCta['icon']))
                            <x-dynamic-component :component="$secondaryCta['icon']" class="w-5 h-5" />
                        @endif
                        {{ $secondaryCta['text'] }}
                    </a>
                    @endif
                </div>

                {{-- Trust Badges --}}
                <div class="flex flex-wrap items-center gap-4 pt-4 text-sm text-indigo-300">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>WCAG 2.2 AA</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        <span>SEO Optimized</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-violet-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M3 3a1 1 0 000 2v8a2 2 0 002 2h2.586l-1.293 1.293a1 1 0 101.414 1.414L10 15.414l2.293 2.293a1 1 0 001.414-1.414L12.414 15H15a2 2 0 002-2V5a1 1 0 100-2H3zm11.707 5.293a1 1 0 00-1.414-1.414L9 11.172 7.707 9.879a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span>Multi-Language</span>
                    </div>
                </div>
            </div>

            {{-- Right Content - Stats/Illustration --}}
            <div class="hidden lg:block">
                <div class="grid grid-cols-2 gap-4">
                    @foreach($stats as $index => $stat)
                    <div class="glassmorphism rounded-2xl p-6 text-center card-hover">
                        <div class="text-4xl lg:text-5xl font-extrabold bg-gradient-to-r from-indigo-400 to-violet-400 bg-clip-text text-transparent mb-2">
                            {{ $stat['value'] }}
                        </div>
                        <div class="text-sm lg:text-base text-indigo-300 font-medium">
                            {{ $stat['label'] }}
                        </div>
                    </div>
                    @endforeach
                </div>

                {{-- Credits Bottle Cap Illustration --}}
                <div class="mt-8 text-center">
                    <div class="inline-flex items-center gap-3 px-6 py-3 glassmorphism rounded-full">
                        <svg class="w-8 h-8 text-credits animate-spin-slow" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.31-8.86c-1.77-.45-2.34-.94-2.34-1.67 0-.84.79-1.43 2.1-1.43 1.38 0 1.9.66 1.94 1.64h1.71c-.05-1.34-.87-2.57-2.49-2.97V5H10.9v1.69c-1.51.32-2.72 1.3-2.72 3.05 0 1.79 1.49 2.69 3.66 3.21 1.95.46 2.34 1.15 2.34 1.87 0 .53-.39 1.39-2.1 1.39-1.6 0-2.23-.72-2.32-1.64H8.04c.1 1.7 1.36 2.66 2.86 2.97V19h2.34v-1.67c1.52-.29 2.72-1.16 2.73-3.15.01-2.08-1.71-2.91-3.66-3.32z"/>
                        </svg>
                        <span class="text-lg font-bold text-credits">Credits, NOT Euro</span>
                    </div>
                    <p class="mt-2 text-xs text-indigo-400">
                        {{ __('predict::home.hero.virtual_currency_disclaimer.label') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

@push('styles')
<style>
    @keyframes blob {
        0%, 100% { transform: translate(0, 0) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
    .animation-delay-4000 {
        animation-delay: 4s;
    }
    .animate-spin-slow {
        animation: spin 3s linear infinite;
    }
    @keyframes spin {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>
@endpush
