<main id="main-content" class="relative min-h-screen bg-slate-950 text-slate-100 overflow-hidden">
    
    {{-- ============================================
         HERO SECTION — Cinematic with Particles
         ============================================ —}}
    <section class="relative py-20 md:py-32 overflow-hidden">
        
        {{-- Background Gradient —}}
        <div class="absolute inset-0 bg-gradient-to-br from-slate-950 via-slate-900 to-emerald-950/20" aria-hidden="true"></div>
        
        {{-- Cinematic Particles —}}
        <div class="absolute inset-0" aria-hidden="true">
            <x-ui.cinematic-particles count="80" />
        </div>
        
        {{-- Content Container —}}
        <div class="container relative mx-auto px-4 sm:px-6 lg:px-8 text-center z-10">
            
            {{-- Badge —}}
            <div class="inline-flex items-center gap-2 px-4 py-2 mb-8 rounded-full bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-medium animate-kinetic-float">
                <x-heroicon-o-sparkles class="w-4 h-4" />
                <span>La Prima Piattaforma Italiana di Prediction Market</span>
            </div>
            
            {{-- Headline with Gradient —}}
            <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold tracking-tight">
                <span class="bg-gradient-to-r from-emerald-400 via-cyan-400 to-emerald-400 bg-clip-text text-transparent animate-gradient">
                    Prevedi il Futuro
                </span>
                <br />
                <span class="text-white">Oggi</span>
            </h1>
            
            {{-- Subheadline —}}
            <p class="mt-6 max-w-3xl mx-auto text-lg md:text-2xl text-slate-300 leading-relaxed">
                Esplora i mercati predittivi multi-opzione, confronta le probabilità e partecipa con le tue previsioni. 
                <span class="text-emerald-400 font-semibold">Senza soldi reali, solo abilità.</span>
            </p>
            
            {{-- Social Proof Stats —}}
            <div class="grid grid-cols-3 gap-6 max-w-3xl mx-auto mt-12">
                <div class="text-center" data-kinetic-counter="{{ $stats['users'] ?? 0 }}" data-kinetic-duration="2000">
                    <div class="text-3xl md:text-4xl font-bold text-emerald-400">
                        <span class="counter-value" data-prefix="" data-suffix="+">0</span>
                    </div>
                    <div class="text-sm md:text-base text-slate-400 mt-1">Utenti Attivi</div>
                </div>
                <div class="text-center" data-kinetic-counter="{{ $stats['predictions'] ?? 0 }}" data-kinetic-duration="2000">
                    <div class="text-3xl md:text-4xl font-bold text-cyan-400">
                        <span class="counter-value" data-prefix="" data-suffix="">0</span>
                    </div>
                    <div class="text-sm md:text-base text-slate-400 mt-1">Previsioni</div>
                </div>
                <div class="text-center" data-kinetic-counter="{{ $stats['volume'] ?? 0 }}" data-kinetic-duration="2000">
                    <div class="text-3xl md:text-4xl font-bold text-purple-400">
                        <span class="counter-value" data-prefix="€" data-suffix="K">0</span>
                    </div>
                    <div class="text-sm md:text-base text-slate-400 mt-1">Volume Trading</div>
                </div>
            </div>
            
            {{-- CTA Buttons with DaisyUI —}}
            <div class="flex flex-col sm:flex-row gap-4 justify-center mt-12">
                <a href="{{ url('/' . app()->getLocale() . '/predicts') }}" 
                   class="btn btn-primary btn-lg gap-2 btn-kinetic shadow-lg shadow-emerald-500/30">
                    <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                    Esplora i Mercati
                </a>
                <a href="{{ url('/' . app()->getLocale() . '/register') }}" 
                   class="btn btn-outline btn-lg gap-2 btn-kinetic border-slate-600 hover:border-slate-400">
                    <x-heroicon-o-user-plus class="w-5 h-5" />
                    Inizia Gratis
                </a>
            </div>
            
            {{-- Trust Badges —}}
            <div class="flex flex-wrap justify-center items-center gap-6 mt-12 text-sm text-slate-400">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-shield-check class="w-5 h-5 text-emerald-400" />
                    <span>Sicuro e Verificato</span>
                </div>
                <div class="flex items-center gap-2">
                    <x-heroicon-o-bolt class="w-5 h-5 text-yellow-400" />
                    <span>Aggiornamenti in Tempo Reale</span>
                </div>
                <div class="flex items-center gap-2">
                    <x-heroicon-o-device-phone-mobile class="w-5 h-5 text-cyan-400" />
                    <span>100% Mobile Friendly</span>
                </div>
            </div>
            
            {{-- Scroll Indicator —}}
            <div class="mt-16 animate-bounce" aria-hidden="true">
                <a href="#featured-predicts" class="text-slate-400 hover:text-slate-200 transition-colors">
                    <x-heroicon-o-arrow-down class="w-6 h-6 mx-auto" />
                </a>
            </div>
        </div>
    </section>
    
    {{-- ============================================
         FEATURED PREDICTS SECTION
         ============================================ —}}
    <section id="featured-predicts" class="py-16 md:py-24 bg-gradient-to-b from-slate-950 to-slate-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Section Header —}}
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 px-3 py-1 mb-4 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-medium">
                    <x-heroicon-o-fire class="w-3 h-3" />
                    <span>Trending Markets</span>
                </div>
                <h2 class="text-3xl md:text-5xl font-bold text-white mb-4">
                    Mercati in <span class="text-emerald-400">Evidenza</span>
                </h2>
                <p class="text-slate-400 text-lg max-w-2xl mx-auto">
                    Scopri i mercati predittivi più popolari con outcome multipli (2-30+ opzioni)
                </p>
            </div>
            
            {{-- Featured Predicts Widget —}}
            @livewire(
                \Modules\Predict\Filament\Widgets\PredictTableWidget::class,
                [
                    'homepageMode' => true,
                    'minimumOutcomes' => 2,
                    'showTableControls' => false,
                    'limit' => 6,
                ],
                key('home-predict-table')
            )
            
            {{-- View All CTA —}}
            <div class="text-center mt-12">
                <a href="{{ url('/' . app()->getLocale() . '/predicts') }}" 
                   class="btn btn-outline btn-lg gap-2">
                    Vedi Tutti i Mercati
                    <x-heroicon-o-arrow-right class="w-5 h-5" />
                </a>
            </div>
        </div>
    </section>
    
    {{-- ============================================
         HOW IT WORKS SECTION
         ============================================ —}}
    <section class="py-16 md:py-24 bg-slate-900">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Come Funziona</h2>
                <p class="text-slate-400 text-lg">Inizia a prevedere in 3 semplici passi</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                {{-- Step 1 —}}
                <div class="card bg-slate-800/50 border border-slate-700 hover:border-emerald-500/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="card-body items-center text-center">
                        <div class="w-16 h-16 rounded-full bg-emerald-500/20 flex items-center justify-center mb-4">
                            <x-heroicon-o-magnifying-glass class="w-8 h-8 text-emerald-400" />
                        </div>
                        <h3 class="card-title text-xl text-white">1. Esplora</h3>
                        <p class="text-slate-400">Scopri centinaia di mercati predittivi su sport, politica, economia e intrattenimento</p>
                    </div>
                </div>
                
                {{-- Step 2 —}}
                <div class="card bg-slate-800/50 border border-slate-700 hover:border-cyan-500/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="card-body items-center text-center">
                        <div class="w-16 h-16 rounded-full bg-cyan-500/20 flex items-center justify-center mb-4">
                            <x-heroicon-o-chart-bar class="w-8 h-8 text-cyan-400" />
                        </div>
                        <h3 class="card-title text-xl text-white">2. Analizza</h3>
                        <p class="text-slate-400">Studia le probabilità, il volume di trading e le tendenze del mercato</p>
                    </div>
                </div>
                
                {{-- Step 3 —}}
                <div class="card bg-slate-800/50 border border-slate-700 hover:border-purple-500/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="card-body items-center text-center">
                        <div class="w-16 h-16 rounded-full bg-purple-500/20 flex items-center justify-center mb-4">
                            <x-heroicon-o-trophy class="w-8 h-8 text-purple-400" />
                        </div>
                        <h3 class="card-title text-xl text-white">3. Prevedi</h3>
                        <p class="text-slate-400">Fai la tua previsione e scala la leaderboard dimostrando la tua abilità</p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    {{-- ============================================
         TESTIMONIALS SECTION
         ============================================ —}}
    <section class="py-16 md:py-24 bg-gradient-to-b from-slate-900 to-slate-950">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 px-3 py-1 mb-4 rounded-full bg-purple-500/10 border border-purple-500/20 text-purple-400 text-xs font-medium">
                    <x-heroicon-o-chat-bubble-left-right class="w-3 h-3" />
                    <span>Community</span>
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">Cosa Dicono i Nostri Utenti</h2>
            </div>
            
            <div class="grid md:grid-cols-3 gap-6">
                {{-- Testimonial 1 —}}
                <div class="card bg-slate-800/50 border border-slate-700">
                    <div class="card-body">
                        <div class="flex items-center gap-1 mb-3">
                            @for($i = 0; $i < 5; $i++)
                                <x-heroicon-s-star class="w-4 h-4 text-yellow-400" />
                            @endfor
                        </div>
                        <p class="text-slate-300 mb-4">"La migliore piattaforma italiana per i prediction market. Interfaccia intuitiva e mercati sempre aggiornati."</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-cyan-400 flex items-center justify-center text-white font-bold">
                                M
                            </div>
                            <div>
                                <div class="font-semibold text-white">Marco R.</div>
                                <div class="text-xs text-slate-400">Top Trader</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Testimonial 2 —}}
                <div class="card bg-slate-800/50 border border-slate-700">
                    <div class="card-body">
                        <div class="flex items-center gap-1 mb-3">
                            @for($i = 0; $i < 5; $i++)
                                <x-heroicon-s-star class="w-4 h-4 text-yellow-400" />
                            @endfor
                        </div>
                        <p class="text-slate-300 mb-4">"Finalmente un'alternativa italiana a Polymarket. Perfetta per chi vuole mettere alla prova le proprie previsioni."</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-400 to-pink-400 flex items-center justify-center text-white font-bold">
                                L
                            </div>
                            <div>
                                <div class="font-semibold text-white">Laura B.</div>
                                <div class="text-xs text-slate-400">Analista</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Testimonial 3 —}}
                <div class="card bg-slate-800/50 border border-slate-700">
                    <div class="card-body">
                        <div class="flex items-center gap-1 mb-3">
                            @for($i = 0; $i < 5; $i++)
                                <x-heroicon-s-star class="w-4 h-4 text-yellow-400" />
                            @endfor
                        </div>
                        <p class="text-slate-300 mb-4">"Uso questa piattaforma da mesi e sono impressionato dalla qualità e dalla varietà dei mercati disponibili."</p>
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-orange-400 to-red-400 flex items-center justify-center text-white font-bold">
                                A
                            </div>
                            <div>
                                <div class="font-semibold text-white">Alessandro T.</div>
                                <div class="text-xs text-slate-400">Enthusiast</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    {{-- ============================================
         FINAL CTA SECTION
         ============================================ —}}
    <section class="py-20 md:py-24 bg-gradient-to-br from-emerald-950/50 to-slate-950">
        <div class="container mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Pronto a Iniziare?
            </h2>
            <p class="text-xl text-slate-300 max-w-2xl mx-auto mb-8">
                Unisciti alla nostra community e inizia a prevedere il futuro. 
                <span class="text-emerald-400 font-semibold">Gratis, senza rischi.</span>
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ url('/' . app()->getLocale() . '/register') }}" 
                   class="btn btn-primary btn-lg btn-kinetic shadow-lg shadow-emerald-500/30">
                    Crea Account Gratis
                </a>
                <a href="{{ url('/' . app()->getLocale() . '/predicts') }}" 
                   class="btn btn-ghost btn-lg gap-2">
                    Esplora i Mercati
                    <x-heroicon-o-arrow-right class="w-5 h-5" />
                </a>
            </div>
            <p class="text-sm text-slate-400 mt-6">
                Nessuna carta di credito richiesta • Setup in 2 minuti
            </p>
        </div>
    </section>
    
</main>
