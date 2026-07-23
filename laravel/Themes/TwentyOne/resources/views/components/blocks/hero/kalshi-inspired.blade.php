@props([
    'title' => 'Predict the Future with Confidence',
    'subtitle' => 'Trade on real events with our advanced prediction markets',
    'cta_text' => 'Start Predicting',
    'cta_link' => '#',
    'secondary_cta_text' => 'View Markets',
    'secondary_cta_link' => '#',
    'show_stats' => true,
    'show_categories' => true
])

<section class="relative overflow-hidden bg-gradient-to-br from-slate-900 via-blue-900 to-slate-900 dark:from-slate-950 dark:via-blue-950 dark:to-slate-950">
    {{-- Background Pattern --}}
    <div class="absolute inset-0 opacity-10">
        <div class="absolute inset-0 bg-grid-pattern"></div>
    </div>
    
    {{-- Floating Elements --}}
    <div class="absolute top-20 left-10 w-20 h-20 bg-blue-500/20 rounded-full blur-xl animate-pulse"></div>
    <div class="absolute top-40 right-20 w-32 h-32 bg-purple-500/20 rounded-full blur-xl animate-pulse delay-1000"></div>
    <div class="absolute bottom-20 left-1/4 w-24 h-24 bg-green-500/20 rounded-full blur-xl animate-pulse delay-2000"></div>

    <div class="relative z-10 container mx-auto px-4 py-20 lg:py-32">
        <div class="max-w-6xl mx-auto">
            {{-- Main Hero Content --}}
            <div class="text-center mb-16">
                <h1 class="text-5xl md:text-7xl lg:text-8xl font-bold mb-8 text-white tracking-tight leading-tight">
                    <span class="bg-gradient-to-r from-blue-400 via-purple-400 to-blue-400 bg-clip-text text-transparent animate-gradient-x">
                        {{ $title }}
                    </span>
                </h1>
                
                <p class="text-xl md:text-2xl lg:text-3xl mb-12 text-slate-300 max-w-4xl mx-auto leading-relaxed font-light">
                    {{ $subtitle }}
                </p>

                {{-- CTA Buttons --}}
                <div class="flex flex-col sm:flex-row gap-6 justify-center items-center mb-16">
                    <a href="{{ $cta_link }}" 
                       class="group relative overflow-hidden bg-gradient-to-r from-blue-600 to-purple-600 hover:from-blue-500 hover:to-purple-500 text-white px-10 py-4 rounded-xl font-semibold text-lg transition-all duration-300 shadow-2xl hover:shadow-blue-500/25 hover:scale-105">
                        <span class="relative z-10">{{ $cta_text }}</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-400 to-purple-400 opacity-0 group-hover:opacity-20 transition-opacity"></div>
                    </a>
                    
                    @if($secondary_cta_text)
                    <a href="{{ $secondary_cta_link }}" 
                       class="group border-2 border-slate-300 hover:border-white text-slate-300 hover:text-white px-10 py-4 rounded-xl font-semibold text-lg transition-all duration-300 hover:bg-white/5">
                        {{ $secondary_cta_text }}
                        <span class="inline-block ml-2 group-hover:translate-x-1 transition-transform">→</span>
                    </a>
                    @endif
                </div>
            </div>

            {{-- Statistics Row --}}
            @if($show_stats)
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-16">
                <div class="text-center p-6 rounded-2xl bg-white/5 backdrop-blur border border-white/10 hover:bg-white/10 transition-all duration-300">
                    <div class="text-3xl lg:text-4xl font-bold text-white mb-2">250+</div>
                    <div class="text-slate-400 font-medium">Active Markets</div>
                </div>
                <div class="text-center p-6 rounded-2xl bg-white/5 backdrop-blur border border-white/10 hover:bg-white/10 transition-all duration-300">
                    <div class="text-3xl lg:text-4xl font-bold text-white mb-2">50K+</div>
                    <div class="text-slate-400 font-medium">Total Predictions</div>
                </div>
                <div class="text-center p-6 rounded-2xl bg-white/5 backdrop-blur border border-white/10 hover:bg-white/10 transition-all duration-300">
                    <div class="text-3xl lg:text-4xl font-bold text-white mb-2">89%</div>
                    <div class="text-slate-400 font-medium">Accuracy Rate</div>
                </div>
                <div class="text-center p-6 rounded-2xl bg-white/5 backdrop-blur border border-white/10 hover:bg-white/10 transition-all duration-300">
                    <div class="text-3xl lg:text-4xl font-bold text-white mb-2">5K+</div>
                    <div class="text-slate-400 font-medium">Active Traders</div>
                </div>
            </div>
            @endif

            {{-- Category Preview --}}
            @if($show_categories)
            <div class="bg-white/5 backdrop-blur rounded-3xl border border-white/10 p-8">
                <h3 class="text-2xl font-semibold text-white text-center mb-8">Popular Categories</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                    <a href="#" class="group flex flex-col items-center p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 transition-all duration-300 hover:scale-105">
                        <div class="text-3xl mb-3 group-hover:scale-110 transition-transform">🗳️</div>
                        <div class="text-white font-medium text-center mb-1">Politics</div>
                        <div class="text-slate-400 text-sm">45 markets</div>
                    </a>
                    <a href="#" class="group flex flex-col items-center p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 transition-all duration-300 hover:scale-105">
                        <div class="text-3xl mb-3 group-hover:scale-110 transition-transform">⚽</div>
                        <div class="text-white font-medium text-center mb-1">Sports</div>
                        <div class="text-slate-400 text-sm">67 markets</div>
                    </a>
                    <a href="#" class="group flex flex-col items-center p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 transition-all duration-300 hover:scale-105">
                        <div class="text-3xl mb-3 group-hover:scale-110 transition-transform">📈</div>
                        <div class="text-white font-medium text-center mb-1">Economics</div>
                        <div class="text-slate-400 text-sm">34 markets</div>
                    </a>
                    <a href="#" class="group flex flex-col items-center p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 transition-all duration-300 hover:scale-105">
                        <div class="text-3xl mb-3 group-hover:scale-110 transition-transform">💻</div>
                        <div class="text-white font-medium text-center mb-1">Technology</div>
                        <div class="text-slate-400 text-sm">28 markets</div>
                    </a>
                    <a href="#" class="group flex flex-col items-center p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 transition-all duration-300 hover:scale-105">
                        <div class="text-3xl mb-3 group-hover:scale-110 transition-transform">🎬</div>
                        <div class="text-white font-medium text-center mb-1">Entertainment</div>
                        <div class="text-slate-400 text-sm">23 markets</div>
                    </a>
                    <a href="#" class="group flex flex-col items-center p-4 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 hover:border-white/20 transition-all duration-300 hover:scale-105">
                        <div class="text-3xl mb-3 group-hover:scale-110 transition-transform">₿</div>
                        <div class="text-white font-medium text-center mb-1">Crypto</div>
                        <div class="text-slate-400 text-sm">19 markets</div>
                    </a>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Bottom Fade --}}
    <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-white dark:from-slate-950 to-transparent"></div>
</section>

<style>
.bg-grid-pattern {
    background-image: 
        linear-gradient(rgba(59, 130, 246, 0.1) 1px, transparent 1px),
        linear-gradient(90deg, rgba(59, 130, 246, 0.1) 1px, transparent 1px);
    background-size: 50px 50px;
}

@keyframes gradient-x {
    0%, 100% {
        background-position: 0% 50%;
    }
    50% {
        background-position: 100% 50%;
    }
}

.animate-gradient-x {
    background-size: 400% 400%;
    animation: gradient-x 3s ease infinite;
}
</style>