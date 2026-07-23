@props([
    'title' => 'STREAM PROFITTI LIVE',
    'subtitle' => 'Guarda i guadagni degli altri trader in tempo reale!',
    'profits' => []
])

@php
    // Dati di esempio per profitti in tempo reale
    $defaultProfits = [
        ['user' => 'Marco_87', 'amount' => '+€2,847', 'market' => 'Bitcoin 100K', 'time' => '2 sec fa', 'color' => '#10B981'],
        ['user' => 'Sofia_Trader', 'amount' => '+€1,234', 'market' => 'Elezioni USA', 'time' => '5 sec fa', 'color' => '#8B5CF6'],
        ['user' => 'Luca_Pro', 'amount' => '+€856', 'market' => 'Tesla Stock', 'time' => '8 sec fa', 'color' => '#F59E0B'],
        ['user' => 'Emma_Gold', 'amount' => '+€3,492', 'market' => 'ChatGPT-5', 'time' => '12 sec fa', 'color' => '#EF4444'],
        ['user' => 'Davide_King', 'amount' => '+€678', 'market' => 'iPhone 16', 'time' => '15 sec fa', 'color' => '#06B6D4'],
        ['user' => 'Anna_Smart', 'amount' => '+€1,897', 'market' => 'Metaverso', 'time' => '18 sec fa', 'color' => '#84CC16'],
        ['user' => 'Paolo_99', 'amount' => '+€567', 'market' => 'Dogecoin', 'time' => '22 sec fa', 'color' => '#F97316'],
    ];

    $displayProfits = !empty($profits) ? $profits : $defaultProfits;
@endphp

<div class="relative bg-gray-900/50 border border-purple-500/30 rounded-2xl p-6 overflow-hidden">
    <!-- Background Effects -->
    <div class="absolute inset-0 bg-gradient-to-r from-purple-900/20 via-transparent to-green-900/20"></div>
    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-bl from-green-400/10 to-transparent rounded-full blur-xl"></div>
    <div class="absolute bottom-0 left-0 w-40 h-40 bg-gradient-to-tr from-purple-400/10 to-transparent rounded-full blur-xl"></div>

    <div class="relative z-10">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-2xl font-black text-white mb-1">
                    <span class="bg-gradient-to-r from-green-400 to-purple-400 bg-clip-text text-transparent">
                        {{ $title }}
                    </span>
                    <div class="inline-flex items-center ml-2">
                        <div class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></div>
                        <span class="text-green-400 text-sm font-bold ml-1">LIVE</span>
                    </div>
                </h3>
                <p class="text-gray-300 text-sm">{{ $subtitle }}</p>
            </div>

            <!-- Live Stats -->
            <div class="text-right">
                <div class="text-green-400 font-black text-xl">€147,892</div>
                <div class="text-gray-400 text-xs">Profitti oggi</div>
            </div>
        </div>

        <!-- Profits Stream -->
        <div class="space-y-3 max-h-80 overflow-y-auto custom-scrollbar">
            @foreach($displayProfits as $index => $profit)
            <div class="profit-item flex items-center justify-between bg-gray-800/40 border border-gray-700/50 rounded-xl p-4 hover:bg-gray-800/60 transition-all duration-300 transform hover:scale-[1.02]"
                 style="animation: slideIn 0.5s ease-out {{ $index * 0.1 }}s both;">

                <!-- User Info -->
                <div class="flex items-center space-x-3">
                    <!-- Avatar -->
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white font-bold text-sm">
                        {{ substr($profit['user'], 0, 1) }}
                    </div>

                    <!-- User Details -->
                    <div>
                        <div class="text-white font-bold text-sm">{{ $profit['user'] }}</div>
                        <div class="text-gray-400 text-xs">{{ $profit['market'] }}</div>
                    </div>
                </div>

                <!-- Profit Amount -->
                <div class="text-right">
                    <div class="font-black text-lg" style="color: {{ $profit['color'] }}">
                        {{ $profit['amount'] }}
                    </div>
                    <div class="text-gray-400 text-xs">{{ $profit['time'] }}</div>
                </div>

                <!-- Sparkle Effect -->
                <div class="absolute top-1 right-1 opacity-60">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4 text-yellow-400 animate-pulse">
                        <path fill-rule="evenodd" d="M9 4.5a.75.75 0 01.721.544l.813 2.846a3.75 3.75 0 002.576 2.576l2.846.813a.75.75 0 010 1.442l-2.846.813a3.75 3.75 0 00-2.576 2.576l-.813 2.846a.75.75 0 01-1.442 0l-.813-2.846a3.75 3.75 0 00-2.576-2.576l-2.846-.813a.75.75 0 010-1.442l2.846-.813A3.75 3.75 0 007.466 7.89l.813-2.846A.75.75 0 019 4.5zM18 1.5a.75.75 0 01.728.568l.258 1.036c.236.94.97 1.674 1.91 1.91l1.036.258a.75.75 0 010 1.456l-1.036.258c-.94.236-1.674.97-1.91 1.91l-.258 1.036a.75.75 0 01-1.456 0l-.258-1.036a2.625 2.625 0 00-1.91-1.91l-1.036-.258a.75.75 0 010-1.456l1.036-.258a2.625 2.625 0 001.91-1.91l.258-1.036A.75.75 0 0118 1.5zM16.5 15a.75.75 0 01.712.513l.394 1.183c.15.447.5.799.948.948l1.183.395a.75.75 0 010 1.422l-1.183.395c-.447.15-.799.5-.948.948l-.395 1.183a.75.75 0 01-1.422 0l-.395-1.183a1.5 1.5 0 00-.948-.948l-1.183-.395a.75.75 0 010-1.422l1.183-.395c.447-.15.799-.5.948-.948l.395-1.183A.75.75 0 0116.5 15z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Bottom CTA -->
        <div class="mt-6 text-center">
            <button class="px-6 py-3 bg-gradient-to-r from-green-500 to-purple-600 hover:from-green-600 hover:to-purple-700 text-white font-bold rounded-xl transition-all duration-300 transform hover:scale-105 shadow-lg hover:shadow-xl flex items-center justify-center space-x-2 mx-auto">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5">
                    <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25zM12.75 9a.75.75 0 00-1.5 0v2.25H9a.75.75 0 000 1.5h2.25V15a.75.75 0 001.5 0v-2.25H15a.75.75 0 000-1.5h-2.25V9z" clip-rule="evenodd" />
                </svg>
                <span>INIZIA A GUADAGNARE ORA</span>
            </button>
            <p class="text-gray-400 text-xs mt-2">Unisciti a chi sta già guadagnando!</p>
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

@keyframes slideIn {
    from {
        opacity: 0;
        transform: translateX(-20px);
    }
    to {
        opacity: 1;
        transform: translateX(0);
    }
}

.profit-item {
    position: relative;
    overflow: hidden;
}

.profit-item::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
    transition: left 0.5s;
}

.profit-item:hover::before {
    left: 100%;
}
</style>

<script>
// Simula aggiornamenti in tempo reale
document.addEventListener('DOMContentLoaded', function() {
    const profitItems = document.querySelectorAll('.profit-item');

    // Aggiungi effetto di "nuovo profitto"
    setInterval(() => {
        const randomItem = profitItems[Math.floor(Math.random() * profitItems.length)];
        randomItem.style.animation = 'none';
        setTimeout(() => {
            randomItem.style.animation = 'slideIn 0.5s ease-out';
        }, 10);
    }, 3000);
});
</script>