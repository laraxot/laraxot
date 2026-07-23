@props([
    'hero_title' => $title ?? 'Trasforma le Tue Previsioni in PROFITTI REALI',
    'hero_subtitle' => $subtitle ?? 'La piattaforma #1 in Italia per trading predittivo',
    'hero_description' => $hero_description ?? 'Investi sulle tue previsioni del futuro e guadagna quando hai ragione. Bitcoin a 100K? Elezioni politiche? Eventi sportivi? Scommettiamo che sai già cosa succederà.',
    'cta_primary' => $cta_primary ?? [
        'text' => 'INIZIA A GUADAGNARE',
        'url' => '/register',
        'class' => 'bg-gradient-to-r from-orange-500 to-red-600 hover:from-orange-600 hover:to-red-700 text-white font-bold py-4 px-8 rounded-xl text-lg transform hover:scale-105 transition-all duration-300 shadow-2xl animate-pulse'
    ],
    'cta_secondary' => $cta_secondary ?? [],
    'background_video' => $background_video ?? '',
    'live_users' => $live_users ?? '1,000+ users predicting now',
    'last_big_win' => $last_big_win ?? null,
    'countdown' => $countdown ?? null
])

<section class="relative min-h-screen bg-gradient-to-br from-gray-900 via-purple-900 to-indigo-900 overflow-hidden">
    @if($background_video)
    <div class="absolute inset-0 z-0">
        <video autoplay muted loop class="w-full h-full object-cover opacity-30">
            <source src="{{ $background_video }}" type="video/mp4">
        </video>
    </div>
    @endif
    <div class="absolute inset-0 z-0">
        <div class="particles-bg">
            @for($i = 0; $i < 50; $i++)
            <div class="particle" style="left: {{ rand(0, 100) }}%; animation-delay: {{ rand(0, 5000) }}ms; animation-duration: {{ rand(3000, 8000) }}ms;"></div>
            @endfor
        </div>
    </div>
    <div class="relative z-10 container mx-auto px-4 pt-20 pb-12">
        <div class="text-center mb-6">
            <div class="inline-flex items-center bg-green-500/20 border border-green-500/30 rounded-full px-4 py-2 text-green-400 text-sm font-semibold animate-pulse">
                <div class="w-2 h-2 bg-green-400 rounded-full mr-2 animate-ping"></div>
                {{ $live_users }}
            </div>
        </div>
        <div class="text-center mb-8">
            <h1 class="text-4xl md:text-6xl lg:text-7xl font-black text-white mb-4 leading-tight">
                <span class="bg-gradient-to-r from-yellow-400 via-red-500 to-pink-500 bg-clip-text text-transparent animate-gradient">
                    {{ $hero_title }}
                </span>
            </h1>
            <p class="text-xl md:text-2xl text-gray-300 mb-8 max-w-4xl mx-auto">{{ $hero_subtitle }}</p>
        </div>
        @if($last_big_win)
        <div class="bg-gradient-to-r from-green-500/20 to-emerald-500/20 border border-green-500/30 rounded-2xl p-6 mb-8 max-w-2xl mx-auto transform hover:scale-105 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M19 5h-2V3H7v2H5c-1.1 0-2 .9-2 2v1c0 2.55 1.92 4.63 4.39 4.94.63 1.5 1.98 2.63 3.61 2.96V19H7v2h10v-2h-4v-3.1c1.63-.33 2.98-1.46 3.61-2.96C19.08 12.63 21 10.55 21 8V7c0-1.1-.9-2-2-2z"/></svg>
                    </div>
                    <div>
                        <div class="text-green-400 font-bold text-lg">{{ $last_big_win['user'] ?? 'Utente' }}</div>
                        <div class="text-gray-300 text-sm">{{ $last_big_win['time'] ?? '' }} • {{ $last_big_win['market'] ?? '' }}</div>
                    </div>
                </div>
                <div class="text-right">
                    <div class="text-3xl font-black text-green-400">{{ $last_big_win['amount'] ?? '' }}</div>
                    <div class="text-xs text-gray-400">VINCITA EPICA!</div>
                </div>
            </div>
        </div>
        @endif
        <div class="text-center space-y-4">
            <a href="{{ $cta_primary['url'] ?? '#' }}" class="{{ $cta_primary['class'] }}">{{ $cta_primary['text'] }}</a>
            @if(isset($cta_secondary['text']))
            <div>
                <a href="{{ $cta_secondary['url'] ?? '#' }}" class="inline-block text-gray-300 hover:text-white font-semibold text-lg underline underline-offset-4 hover:underline-offset-8 transition-all duration-300">{{ $cta_secondary['text'] }}</a>
            </div>
            @endif
        </div>
        <div class="grid grid-cols-3 gap-4 max-w-2xl mx-auto mt-12">
            <div class="text-center">
                <div class="text-2xl font-black text-white">€2.4M</div>
                <div class="text-xs text-gray-400">PAGATI OGGI</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-black text-white">47K+</div>
                <div class="text-xs text-gray-400">UTENTI ATTIVI</div>
            </div>
            <div class="text-center">
                <div class="text-2xl font-black text-white">94%</div>
                <div class="text-xs text-gray-400">ACCURACY TOP</div>
            </div>
        </div>
    </div>
    <div class="absolute bottom-10 right-10 space-y-4 hidden lg:block">
        <div class="bg-green-500/20 border border-green-500/30 rounded-full p-4 animate-bounce">
            <svg class="w-6 h-6 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
        </div>
        <div class="bg-blue-500/20 border border-blue-500/30 rounded-full p-4 animate-bounce" style="animation-delay: 1s">
            <svg class="w-6 h-6 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
        </div>
    </div>
</section>
@once
@push('styles')
<style>
    .particles-bg { position: absolute; width: 100%; height: 100%; overflow: hidden; }
    .particle { position: absolute; width: 4px; height: 4px; background: linear-gradient(45deg, #fbbf24, #f59e0b); border-radius: 50%; animation: float linear infinite; }
    @keyframes float { 0% { transform: translateY(100vh) translateX(0px) rotate(0deg); opacity: 1; } 100% { transform: translateY(-10vh) translateX(100px) rotate(360deg); opacity: 0; } }
    .animate-gradient { background-size: 400% 400%; animation: gradient 3s ease infinite; }
    @keyframes gradient { 0%, 100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
</style>
@endpush
@endonce
