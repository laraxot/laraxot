{{--
  Hero compatta stile Polymarket/Kalshi: value proposition + CTA.
  Accessibile: role="banner", heading h1, link CTA con aria.
  Cinematic: gradienti profondi, particles SVG, glassmorphism, animazioni fluide.
--}}
@php
    $particlesColor = 'rgba(16, 185, 129, 0.5)';
@endphp
<section class="relative overflow-hidden bg-gradient-to-br from-emerald-500 via-teal-600 to-cyan-700" role="banner" aria-labelledby="hero-heading">
    {{-- Particles SVG (enhanced visibility) --}}
    <x-ui.particles :count="80" :color="$particlesColor" size="4" :z-index="10" variant="antigravity" />
    
    {{-- Gradient overlays for cinematic depth --}}
    <div class="absolute inset-0 bg-gradient-to-t from-black/40 via-transparent to-black/20"></div>
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_var(--tw-gradient-stops))] from-emerald-300/20 via-transparent to-transparent"></div>
    
    {{-- Animated mesh gradient background --}}
    <div class="absolute inset-0 opacity-30">
        <div class="absolute top-0 -left-1/4 w-96 h-96 bg-emerald-400/30 rounded-full mix-blend-multiply filter blur-3xl animate-blob"></div>
        <div class="absolute top-0 -right-1/4 w-96 h-96 bg-cyan-400/30 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-2000"></div>
        <div class="absolute -bottom-32 left-1/3 w-96 h-96 bg-teal-400/30 rounded-full mix-blend-multiply filter blur-3xl animate-blob animation-delay-4000"></div>
    </div>
    
    {{-- Pattern geometrico sottile --}}
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=\'80\' height=\'80\' viewBox=\'0 0 80 80\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.03\'%3E%3Cpath d=\'M0 0h80v80H0V0zm40 40v40h40V40H40zM0 40h40v40H0V40z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>
    
    {{-- Content container --}}
    <div class="relative px-4 py-20 mx-auto max-w-7xl sm:px-6 lg:px-8 lg:py-28">
        <div class="text-center">
            {{-- Badge con glassmorphism --}}
            <p class="inline-flex items-center gap-2 px-5 py-2.5 mb-6 text-sm font-semibold tracking-wide text-emerald-50 rounded-full bg-white/15 backdrop-blur-md border border-white/20 shadow-lg animate-kinetic-slideDown kinetic-delay-1" id="hero-badge">
                <span class="relative flex w-2 h-2">
                    <span class="absolute inline-flex w-full h-full bg-emerald-400 rounded-full opacity-75 animate-ping"></span>
                    <span class="relative inline-flex w-2 h-2 bg-emerald-300 rounded-full"></span>
                </span>
                {{ __('predict::home.hero.badge.label') }}
            </p>
            
            {{-- Titolo principale --}}
            <h1 id="hero-heading" class="text-4xl font-black tracking-tight text-white sm:text-5xl lg:text-7xl animate-kinetic-slideUp kinetic-delay-2 drop-shadow-2xl">
                {{ __('predict::home.hero.title.label') }}
            </h1>
            
            {{-- Sottotitolo con leggibilità migliorata --}}
            <p class="max-w-3xl mx-auto mt-6 text-xl font-light text-emerald-50 sm:text-2xl animate-kinetic-slideUp kinetic-delay-3 drop-shadow-lg leading-relaxed">
                {{ __('predict::home.hero.subtitle.label') }}
            </p>
            
            {{-- CTA buttons con glassmorphism --}}
            <div class="flex flex-col justify-center gap-5 mt-10 sm:flex-row sm:gap-8 animate-kinetic-scaleIn kinetic-delay-4">
                <a href="{{ route('articles.index') }}#playmarkets"
                   class="group relative inline-flex items-center justify-center min-h-[52px] min-w-[52px] px-8 py-4 text-lg font-bold text-emerald-800 bg-white/95 backdrop-blur-sm rounded-xl shadow-2xl hover:bg-white hover:shadow-emerald-500/50 hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-emerald-600 overflow-hidden"
                   aria-label="{{ __('predict::home.hero.cta_explore.label') }}">
                    <span class="absolute inset-0 bg-gradient-to-r from-emerald-400/20 to-transparent transform -skew-x-12 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700"></span>
                    <span class="relative z-10 flex items-center gap-3">
                        {{ __('predict::home.hero.cta_explore.label') }}
                        <x-heroicon-o-arrow-right class="w-6 h-6 group-hover:translate-x-1 transition-transform" aria-hidden="true" />
                    </span>
                </a>
                
                <a href="/{{ app()->getLocale() }}/learn"
                   class="group inline-flex items-center justify-center min-h-[52px] min-w-[52px] px-8 py-4 text-lg font-bold text-white border-2 border-white/80 bg-white/10 backdrop-blur-sm rounded-xl hover:bg-white/20 hover:border-white hover:scale-105 transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-white focus:ring-offset-2 focus:ring-offset-emerald-600"
                   aria-label="{{ __('predict::home.hero.cta_learn.label') }}">
                    {{ __('predict::home.hero.cta_learn.label') }}
                </a>
            </div>
            
            {{-- Stats con glassmorphism --}}
            <div class="grid grid-cols-2 gap-6 max-w-2xl mx-auto mt-16 sm:grid-cols-4 animate-kinetic-scaleIn kinetic-delay-5">
                <div class="p-4 text-center rounded-2xl bg-white/10 backdrop-blur-md border border-white/20">
                    <p class="text-3xl font-bold text-white">100%</p>
                    <p class="mt-1 text-xs font-medium text-emerald-100 uppercase tracking-wide">Gratis</p>
                </div>
                <div class="p-4 text-center rounded-2xl bg-white/10 backdrop-blur-md border border-white/20">
                    <p class="text-3xl font-bold text-white">3+</p>
                    <p class="mt-1 text-xs font-medium text-emerald-100 uppercase tracking-wide">Opzioni</p>
                </div>
                <div class="p-4 text-center rounded-2xl bg-white/10 backdrop-blur-md border border-white/20">
                    <p class="text-3xl font-bold text-white">24/7</p>
                    <p class="mt-1 text-xs font-medium text-emerald-100 uppercase tracking-wide">Trading</p>
                </div>
                <div class="p-4 text-center rounded-2xl bg-white/10 backdrop-blur-md border border-white/20">
                    <p class="text-3xl font-bold text-white">100%</p>
                    <p class="mt-1 text-xs font-medium text-emerald-100 uppercase tracking-wide">Trasparente</p>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Scroll indicator --}}
    <div class="absolute bottom-6 left-1/2 -translate-x-1/2 animate-bounce">
        <x-heroicon-o-chevron-down class="w-8 h-8 text-white/80 drop-shadow-lg" aria-hidden="true" />
    </div>
</section>
