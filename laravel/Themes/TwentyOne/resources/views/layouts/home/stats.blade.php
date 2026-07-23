<!-- STATS — Animated counters with scroll-triggered reveal -->
<section id="stats" class="py-20 bg-slate-900 relative overflow-hidden">

    <!-- Subtle background gradient -->
    <div class="absolute inset-0 bg-gradient-to-b from-slate-900 via-purple-900/5 to-slate-900 pointer-events-none" aria-hidden="true"></div>

    <div class="relative max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

        <!-- Section heading -->
        <div class="text-center mb-14 reveal">
            <span class="inline-block px-4 py-1.5 mb-4 text-xs font-semibold tracking-widest text-purple-400 uppercase bg-purple-400/10 rounded-full border border-purple-400/20">
                La piattaforma in numeri
            </span>
            <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">
                Risultati che parlano da soli
            </h2>
            <p class="text-slate-400 max-w-xl mx-auto text-base sm:text-lg leading-relaxed">
                Una community in crescita, predizioni affidabili e performance verificate nel tempo.
            </p>
        </div>

        <!-- Stats grid -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8 reveal-group">

            <!-- Stat 1 — Predizioni Attive -->
            <div class="reveal card-lift text-center px-6 py-8 bg-white/5 backdrop-blur-sm rounded-2xl border border-white/10 hover:border-purple-400/30 transition-colors duration-300"
                 x-data="statCounter(12500, 1400, '', '+')">
                <div class="inline-flex items-center justify-center w-12 h-12 mb-4 rounded-xl bg-purple-500/15 border border-purple-400/20" aria-hidden="true">
                    <svg class="w-6 h-6 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div class="text-3xl sm:text-4xl font-bold text-white stat-number mb-1" x-text="display" aria-live="polite"></div>
                <div class="text-sm text-slate-400 font-medium">Predizioni Attive</div>
            </div>

            <!-- Stat 2 — Utenti Registrati -->
            <div class="reveal card-lift text-center px-6 py-8 bg-white/5 backdrop-blur-sm rounded-2xl border border-white/10 hover:border-blue-400/30 transition-colors duration-300"
                 x-data="statCounter(8300, 1600, '', '+')">
                <div class="inline-flex items-center justify-center w-12 h-12 mb-4 rounded-xl bg-blue-500/15 border border-blue-400/20" aria-hidden="true">
                    <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="text-3xl sm:text-4xl font-bold text-white stat-number mb-1" x-text="display" aria-live="polite"></div>
                <div class="text-sm text-slate-400 font-medium">Utenti Registrati</div>
            </div>

            <!-- Stat 3 — Accuratezza Media -->
            <div class="reveal card-lift text-center px-6 py-8 bg-white/5 backdrop-blur-sm rounded-2xl border border-white/10 hover:border-emerald-400/30 transition-colors duration-300"
                 x-data="statCounter(84, 1200, '', '%')">
                <div class="inline-flex items-center justify-center w-12 h-12 mb-4 rounded-xl bg-emerald-500/15 border border-emerald-400/20" aria-hidden="true">
                    <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-3xl sm:text-4xl font-bold text-white stat-number mb-1" x-text="display" aria-live="polite"></div>
                <div class="text-sm text-slate-400 font-medium">Accuratezza Media</div>
            </div>

            <!-- Stat 4 — Volume Totale -->
            <div class="reveal card-lift text-center px-6 py-8 bg-white/5 backdrop-blur-sm rounded-2xl border border-white/10 hover:border-pink-400/30 transition-colors duration-300"
                 x-data="statCounter(2400000, 1800, '€')">
                <div class="inline-flex items-center justify-center w-12 h-12 mb-4 rounded-xl bg-pink-500/15 border border-pink-400/20" aria-hidden="true">
                    <svg class="w-6 h-6 text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="text-3xl sm:text-4xl font-bold text-white stat-number mb-1" x-text="display" aria-live="polite"></div>
                <div class="text-sm text-slate-400 font-medium">Volume Totale</div>
            </div>
        </div>

        <!-- Accuracy progress bars -->
        <div class="mt-14 grid sm:grid-cols-3 gap-6 reveal-group">

            <div class="reveal bg-white/5 rounded-2xl border border-white/10 p-6"
                 x-data="progressBar(84)">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm font-medium text-slate-300">Sport</span>
                    <span class="text-sm font-semibold text-emerald-400">84%</span>
                </div>
                <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                    <div class="progress-bar h-full rounded-full bg-gradient-to-r from-emerald-500 to-teal-400"
                         :style="style"></div>
                </div>
            </div>

            <div class="reveal bg-white/5 rounded-2xl border border-white/10 p-6"
                 x-data="progressBar(78)">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm font-medium text-slate-300">Finanza</span>
                    <span class="text-sm font-semibold text-blue-400">78%</span>
                </div>
                <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                    <div class="progress-bar h-full rounded-full bg-gradient-to-r from-blue-500 to-cyan-400"
                         :style="style"></div>
                </div>
            </div>

            <div class="reveal bg-white/5 rounded-2xl border border-white/10 p-6"
                 x-data="progressBar(91)">
                <div class="flex justify-between items-center mb-3">
                    <span class="text-sm font-medium text-slate-300">Politica</span>
                    <span class="text-sm font-semibold text-purple-400">91%</span>
                </div>
                <div class="h-2 bg-white/10 rounded-full overflow-hidden">
                    <div class="progress-bar h-full rounded-full bg-gradient-to-r from-purple-500 to-pink-400"
                         :style="style"></div>
                </div>
            </div>
        </div>

    </div>
</section>
