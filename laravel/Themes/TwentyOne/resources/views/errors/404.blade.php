<?php
use function Laravel\Folio\{name};

name('404');
?>
<x-layouts.app
    title="Pagina Non Trovata - Predict"
    meta-description="La pagina che stai cercando non esiste. Torna alla home di Predict per scoprire la nostra piattaforma di prediction market."
>
    <div class="min-h-screen bg-gradient-to-br from-slate-950 via-emerald-950 to-slate-900 flex items-center justify-center px-4">
        <div class="max-w-2xl mx-auto text-center">
            <!-- Animated 404 -->
            <div class="relative mb-8">
                <div class="text-8xl md:text-9xl font-black text-emerald-400 mb-4 animate-pulse">
                    4<span class="inline-block animate-bounce delay-100">0</span><span class="inline-block animate-bounce delay-200">4</span>
                </div>
                <div class="absolute inset-0 text-8xl md:text-9xl font-black text-emerald-400/20 blur-sm">
                    404
                </div>
            </div>

            <!-- Error Message -->
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-6">
                Pagina Non Trovata
            </h1>

            <p class="text-xl text-slate-300 mb-8 max-w-lg mx-auto">
                Oops! La pagina che stai cercando sembra essere scomparsa nel cyberspazio.
                Forse è stata teletrasportata altrove o semplicemente non esiste.
            </p>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ url('/') }}"
                   class="cyber-button magnetic-hover px-8 py-4 rounded-2xl text-white font-bold text-lg group">
                    <span class="relative z-10">Torna alla Home</span>
                </a>

                <button onclick="history.back()"
                        class="px-8 py-4 rounded-2xl border-2 border-white/30 text-white font-bold text-lg hover:border-white/60 hover:bg-white/10 backdrop-blur-xl transition-all duration-300">
                    Torna Indietro
                </button>
            </div>

            <!-- Additional Help -->
            <div class="mt-12 text-slate-400">
                <p class="mb-4">Potresti trovare quello che cerchi:</p>
                <div class="flex flex-wrap justify-center gap-4 text-sm">
                    <a href="{{ url('/categories') }}" class="text-emerald-400 hover:text-emerald-300 underline">Esplora Categorie</a>
                    <a href="{{ url('/search') }}" class="text-emerald-400 hover:text-emerald-300 underline">Cerca Predizioni</a>
                    <a href="{{ url('/learn') }}" class="text-emerald-400 hover:text-emerald-300 underline">Come Funziona</a>
                    <a href="{{ url('/contact') }}" class="text-emerald-400 hover:text-emerald-300 underline">Contattaci</a>
                </div>
            </div>

            <!-- Fun Animation -->
            <div class="mt-16 relative">
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="w-32 h-32 border-4 border-emerald-400/20 rounded-full animate-spin">
                        <div class="w-32 h-32 border-4 border-transparent border-t-emerald-400 rounded-full animate-spin absolute inset-0" style="animation-duration: 1.5s;"></div>
                    </div>
                </div>
                <div class="text-center">
                    <div class="text-6xl mb-4">🔮</div>
                    <p class="text-emerald-400 font-medium">Stiamo predicendo il tuo prossimo click...</p>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app>
