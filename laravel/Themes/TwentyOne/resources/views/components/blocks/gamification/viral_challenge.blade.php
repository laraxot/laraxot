@props([
    'title' => '🔥 SFIDA VIRALE DEL GIORNO',
    'subtitle' => 'Completa la sfida e vinci premi esclusivi!',
    'challenge' => null
])

@php
    // Sfida dinamica del giorno
    $defaultChallenge = [
        'name' => 'RADDOPPIA O NIENTE',
        'description' => 'Fai 3 previsioni consecutive GIUSTE e RADDOPPIA i tuoi punti!',
        'reward' => '10.000 PUNTI BONUS',
        'participants' => 2847,
        'time_left' => '18:42:15',
        'progress' => 67,
        'icon' => '🎯',
        'difficulty' => 'EPICA',
        'tags' => ['trending', 'limited', 'bonus_x2']
    ];

    // $currentChallenge = $challenge ?? $defaultChallenge;
    $currentChallenge = array_merge($defaultChallenge, $challenge ?? []);


    // Achievements recenti
    $recentWinners = [
        ['user' => 'Alex_Pro', 'reward' => '25K punti', 'time' => '3 min fa'],
        ['user' => 'Maria_Gold', 'reward' => '15K punti', 'time' => '7 min fa'],
        ['user' => 'Luca_King', 'reward' => '30K punti', 'time' => '12 min fa'],
        ['user' => 'Sara_Beast', 'reward' => '20K punti', 'time' => '18 min fa'],
    ];
    
    $progressBar = $currentChallenge['progress_bar'] ?? null;
    $progressPercent = 0;
    
    if ($progressBar && isset($progressBar['current'], $progressBar['total']) {
        $progressPercent = round(($progressBar['current'] / $progressBar['total']) * 100);
    }
@endphp

<div class="relative bg-gradient-to-br from-purple-900/40 via-gray-900/60 to-blue-900/40 border border-purple-500/40 rounded-3xl p-6 overflow-hidden">
    <!-- Background Effects -->
    <div class="absolute inset-0 bg-gradient-to-r from-purple-600/10 via-pink-600/10 to-blue-600/10"></div>
    <div class="absolute top-0 right-0 w-40 h-40 bg-gradient-to-bl from-pink-500/20 to-transparent rounded-full blur-2xl animate-pulse"></div>
    <div class="absolute bottom-0 left-0 w-32 h-32 bg-gradient-to-tr from-purple-500/20 to-transparent rounded-full blur-xl"></div>

    <!-- Animated particles -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        @for($i = 0; $i < 6; $i++)
        <div class="absolute w-1 h-1 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full animate-bounce"
             style="
                top: {{ rand(10, 90) }}%;
                left: {{ rand(10, 90) }}%;
                animation-delay: {{ $i * 0.5 }}s;
                animation-duration: {{ 2 + ($i * 0.3) }}s;
             "></div>
        @endfor
    </div>

    <div class="relative z-10">
        <!-- Header with pulsing effects -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center space-x-2 bg-gradient-to-r from-pink-500/20 to-purple-500/20 border border-pink-500/30 rounded-full px-4 py-2 mb-3">
                <div class="w-2 h-2 bg-pink-400 rounded-full animate-ping"></div>
                <span class="text-pink-300 text-xs font-bold uppercase tracking-wider">SFIDA LIVE</span>
                <div class="w-2 h-2 bg-purple-400 rounded-full animate-ping" style="animation-delay: 0.5s;"></div>
            </div>

            <h2 class="text-3xl font-black text-white mb-2">
                <span class="bg-gradient-to-r from-pink-400 via-purple-400 to-blue-400 bg-clip-text text-transparent animate-pulse">
                    {{ $title }}
                </span>
            </h2>
            <p class="text-gray-300 text-sm">{{ $subtitle }}</p>
        </div>

        <!-- Challenge Card -->
        <div class="bg-gray-800/60 border border-purple-500/30 rounded-2xl p-6 mb-6 transform hover:scale-105 transition-all duration-300">
            <!-- Challenge Header -->
            <div class="flex items-center justify-between mb-4">
                <div class="flex items-center space-x-3">
                    <div class="text-4xl animate-bounce">{{ $currentChallenge['icon'] }}</div>
                    <div>
                        <h3 class="text-xl font-black text-white">{{ $currentChallenge['name'] }}</h3>
                        <div class="flex items-center space-x-2">
                            <span class="px-2 py-1 bg-gradient-to-r from-orange-500 to-red-500 text-white text-xs font-bold rounded-full uppercase">
                                {{ $currentChallenge['difficulty'] }}
                            </span>
                            @foreach($currentChallenge['tags'] as $tag)
                            <span class="px-2 py-1 bg-purple-500/20 text-purple-300 text-xs font-bold rounded-full">
                                #{{ $tag }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Countdown Timer -->
                <div class="text-center">
                    <div class="text-red-400 font-black text-lg countdown" data-time="{{ $currentChallenge['time_left'] }}">
                        {{ $currentChallenge['time_left'] }}
                    </div>
                    <div class="text-gray-400 text-xs">TEMPO RIMASTO</div>
                </div>
            </div>

            <!-- Challenge Description -->
            <p class="text-gray-300 text-sm mb-4 leading-relaxed">
                {{ $currentChallenge['description'] }}
            </p>

            <!-- Reward Section -->
            <div class="bg-gradient-to-r from-yellow-500/20 to-orange-500/20 border border-yellow-500/30 rounded-xl p-4 mb-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-white">
                                <path fill-rule="evenodd" d="M5.166 2.621v.858c-1.035.148-2.059.33-3.071.543a.75.75 0 00-.584.859 6.753 6.753 0 006.138 5.6 6.73 6.73 0 002.743 1.346A6.707 6.707 0 019.279 15H8.54c-1.036 0-1.875.84-1.875 1.875V19.5h-.75a2.25 2.25 0 00-2.25 2.25c0 .414.336.75.75.75h15a.75.75 0 00.75-.75 2.25 2.25 0 00-2.25-2.25H15v-2.625c0-1.036-.84-1.875-1.875-1.875h-.739a6.706 6.706 0 01-1.112-3.173 6.73 6.73 0 002.743-1.347 6.753 6.753 0 006.139-5.6.75.75 0 00-.585-.858 47.077 47.077 0 00-3.07-.543V2.62a.75.75 0 00-.658-.744 49.22 49.22 0 00-6.093-.377c-2.063 0-4.096.128-6.093.377a.75.75 0 00-.657.744zm0 2.629c0 1.196.312 2.32.857 3.294A5.266 5.266 0 013.16 5.337a45.6 45.6 0 012.006-.343v.256zm13.5 0v-.256c.674.1 1.343.214 2.006.343a5.265 5.265 0 01-2.863 3.207A6.72 6.72 0 0018.666 5.25z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div>
                            <div class="text-yellow-400 font-black text-lg">{{ $currentChallenge['prize'] }}</div>
                            <div class="text-gray-400 text-xs">PREMIO GARANTITO</div>
                        </div>
                    </div>
                    <div class="text-right">
                        <div class="text-white font-bold text-sm">{{ number_format($currentChallenge['participants']) }}</div>
                        <div class="text-gray-400 text-xs">Partecipanti</div>
                    </div>
                </div>
            </div>
            @if($progressBar)
            <!-- Progress Bar -->
            <div class="mb-4">
                <div class="flex justify-between text-xs text-gray-400 mb-2">
                    <span>Progresso Globale</span>
                    <span>{{round($currentChallenge['progress_bar']['current'] /$currentChallenge['progress_bar']['total'] * 100) }}%</span>
                </div>
                <div class="w-full bg-gray-700 rounded-full h-3 overflow-hidden">
                    <div class="bg-gradient-to-r from-purple-500 to-pink-500 h-3 rounded-full transition-all duration-1000 relative"
                         style="width: {{ $currentChallenge['progress'] }}%">
                        <div class="absolute inset-0 bg-white/20 animate-pulse"></div>
                    </div>
                </div>
            </div>
            @endif
            <!-- Action Button -->
            <button class="w-full bg-gradient-to-r from-pink-600 via-purple-600 to-blue-600 hover:from-pink-700 hover:via-purple-700 hover:to-blue-700 text-white font-black py-4 rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-2xl relative overflow-hidden group">
                <div class="absolute inset-0 bg-gradient-to-r from-white/0 via-white/20 to-white/0 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000"></div>
                <div class="flex items-center justify-center space-x-2 relative">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                        <path fill-rule="evenodd" d="M12.963 2.286a.75.75 0 00-1.071-.136 9.742 9.742 0 00-3.539 6.177A7.547 7.547 0 016.648 6.61a.75.75 0 00-1.152-.082A9 9 0 1015.68 4.534a7.46 7.46 0 01-2.717-2.248zM15.75 14.25a3.75 3.75 0 11-7.313-1.172c.628.465 1.35.81 2.133 1a5.99 5.99 0 011.925-3.545 3.75 3.75 0 013.255 3.717z" clip-rule="evenodd" />
                    </svg>
                    <span>ACCETTA SFIDA</span>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 animate-bounce">
                        <path fill-rule="evenodd" d="M16.72 7.72a.75.75 0 011.06 0l3.75 3.75a.75.75 0 010 1.06l-3.75 3.75a.75.75 0 11-1.06-1.06L19.19 12l-2.47-2.47a.75.75 0 010-1.06zM1.25 12a.75.75 0 01.75-.75h16.5a.75.75 0 010 1.5H2a.75.75 0 01-.75-.75z" clip-rule="evenodd" />
                    </svg>
                </div>
            </button>
        </div>

        <!-- Recent Winners Section -->
        <div class="bg-gray-800/40 border border-gray-600/30 rounded-xl p-4">
            <h4 class="text-white font-bold text-sm mb-3 flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-yellow-400 mr-2">
                    <path fill-rule="evenodd" d="M5.166 2.621v.858c-1.035.148-2.059.33-3.071.543a.75.75 0 00-.584.859 6.753 6.753 0 006.138 5.6 6.73 6.73 0 002.743 1.346A6.707 6.707 0 019.279 15H8.54c-1.036 0-1.875.84-1.875 1.875V19.5h-.75a2.25 2.25 0 00-2.25 2.25c0 .414.336.75.75.75h15a.75.75 0 00.75-.75 2.25 2.25 0 00-2.25-2.25H15v-2.625c0-1.036-.84-1.875-1.875-1.875h-.739a6.706 6.706 0 01-1.112-3.173 6.73 6.73 0 002.743-1.347 6.753 6.753 0 006.139-5.6.75.75 0 00-.585-.858 47.077 47.077 0 00-3.07-.543V2.62a.75.75 0 00-.658-.744 49.22 49.22 0 00-6.093-.377c-2.063 0-4.096.128-6.093.377a.75.75 0 00-.657.744zm0 2.629c0 1.196.312 2.32.857 3.294A5.266 5.266 0 013.16 5.337a45.6 45.6 0 012.006-.343v.256zm13.5 0v-.256c.674.1 1.343.214 2.006.343a5.265 5.265 0 01-2.863 3.207A6.72 6.72 0 0018.666 5.25z" clip-rule="evenodd" />
                </svg>
                ULTIMI VINCITORI
            </h4>

            <div class="space-y-2 max-h-32 overflow-y-auto custom-scrollbar">
                @foreach($recentWinners as $winner)
                <div class="flex items-center justify-between py-2 px-3 bg-gray-700/30 rounded-lg hover:bg-gray-700/50 transition-all">
                    <div class="flex items-center space-x-2">
                        <div class="w-6 h-6 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-full flex items-center justify-center text-xs font-bold text-white">
                            🏆
                        </div>
                        <span class="text-white text-sm font-medium">{{ $winner['user'] }}</span>
                    </div>
                    <div class="text-right">
                        <div class="text-yellow-400 text-sm font-bold">{{ $winner['reward'] }}</div>
                        <div class="text-gray-400 text-xs">{{ $winner['time'] }}</div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<style>
.custom-scrollbar::-webkit-scrollbar {
    width: 4px;
}

.custom-scrollbar::-webkit-scrollbar-track {
    background: rgba(75, 85, 99, 0.3);
    border-radius: 2px;
}

.custom-scrollbar::-webkit-scrollbar-thumb {
    background: rgba(139, 92, 246, 0.5);
    border-radius: 2px;
}

.custom-scrollbar::-webkit-scrollbar-thumb:hover {
    background: rgba(139, 92, 246, 0.7);
}

.countdown {
    font-family: 'Courier New', monospace;
    text-shadow: 0 0 10px rgba(239, 68, 68, 0.5);
}

@keyframes glow {
    0%, 100% { text-shadow: 0 0 5px rgba(139, 92, 246, 0.5); }
    50% { text-shadow: 0 0 20px rgba(139, 92, 246, 0.8), 0 0 30px rgba(139, 92, 246, 0.6); }
}

.animate-glow {
    animation: glow 2s ease-in-out infinite alternate;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Countdown timer functionality
    function updateCountdown() {
        const countdownElement = document.querySelector('.countdown');
        if (countdownElement) {
            const timeString = countdownElement.getAttribute('data-time');
            const [hours, minutes, seconds] = timeString.split(':').map(Number);

            let totalSeconds = hours * 3600 + minutes * 60 + seconds;

            setInterval(() => {
                totalSeconds--;
                if (totalSeconds < 0) totalSeconds = 0;

                const h = Math.floor(totalSeconds / 3600);
                const m = Math.floor((totalSeconds % 3600) / 60);
                const s = totalSeconds % 60;

                countdownElement.textContent =
                    `${h.toString().padStart(2, '0')}:${m.toString().padStart(2, '0')}:${s.toString().padStart(2, '0')}`;

                // Add urgency effect when time is low
                if (totalSeconds < 3600) { // Less than 1 hour
                    countdownElement.classList.add('animate-pulse', 'text-red-500');
                }
            }, 1000);
        }
    }

    updateCountdown();

    // Add sparkle effects to reward section
    const rewardSection = document.querySelector('.bg-gradient-to-r.from-yellow-500\\/20');
    if (rewardSection) {
        setInterval(() => {
            // Create temporary sparkle
            const sparkle = document.createElement('div');
            sparkle.innerHTML = '✨';
            sparkle.style.position = 'absolute';
            sparkle.style.top = Math.random() * 100 + '%';
            sparkle.style.left = Math.random() * 100 + '%';
            sparkle.style.fontSize = '12px';
            sparkle.style.pointerEvents = 'none';
            sparkle.style.animation = 'sparkle 1s ease-out forwards';

            rewardSection.style.position = 'relative';
            rewardSection.appendChild(sparkle);

            setTimeout(() => sparkle.remove(), 1000);
        }, 2000);
    }
});

// Add sparkle animation
const style = document.createElement('style');
style.textContent = `
    @keyframes sparkle {
        0% { opacity: 0; transform: scale(0) rotate(0deg); }
        50% { opacity: 1; transform: scale(1) rotate(180deg); }
        100% { opacity: 0; transform: scale(0) rotate(360deg); }
    }
`;
document.head.appendChild(style);
</script>
