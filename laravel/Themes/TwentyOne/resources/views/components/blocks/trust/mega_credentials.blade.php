@props([
    'title' => '🛡️ SICUREZZA GARANTITA',
    'stats' => []
])

@php
    $defaultStats = [
        ['number' => '2.4M', 'label' => 'Crediti distribuiti questo mese', 'icon' => '💰'],
        ['number' => '47,392', 'label' => 'Utenti attivi', 'icon' => '👥'],
        ['number' => '99.2%', 'label' => 'Uptime garantito', 'icon' => '🛡️'],
        ['number' => '4.9/5', 'label' => 'Rating utenti', 'icon' => '⭐']
    ];
    $displayStats = !empty($stats) ? $stats : $defaultStats;
@endphp

<div class="bg-gradient-to-br from-gray-800/60 to-gray-900/80 border border-gray-600/40 rounded-3xl p-6">
    <div class="text-center mb-8">
        <h2 class="text-2xl font-black text-white mb-4">
            <span class="bg-gradient-to-r from-green-400 to-blue-400 bg-clip-text text-transparent">{{ $title }}</span>
        </h2>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        @foreach($displayStats as $stat)
        <div class="text-center">
            <div class="text-3xl mb-2">{{ $stat['icon'] }}</div>
            <div class="text-2xl font-black text-white mb-1">{{ $stat['number'] }}</div>
            <div class="text-gray-400 text-sm">{{ $stat['label'] }}</div>
        </div>
        @endforeach
    </div>

    <!-- Certifications -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        @foreach(['SSL Certificate', 'GDPR Compliant', 'ISO 27001', 'Regulatory Approved'] as $cert)
        <div class="bg-gray-700/30 border border-gray-600/30 rounded-lg p-3 text-center">
            <div class="text-green-400 text-sm font-bold">✓ {{ $cert }}</div>
        </div>
        @endforeach
    </div>

    <!-- Payment Methods -->
    <div class="text-center">
        <div class="text-gray-400 text-sm mb-3">Metodi di pagamento sicuri:</div>
        <div class="flex justify-center items-center space-x-4">
            @foreach(['💳', '🏦', '₿', '💰'] as $payment)
            <div class="w-12 h-8 bg-gray-700/50 rounded border border-gray-600/30 flex items-center justify-center text-lg">
                {{ $payment }}
            </div>
            @endforeach
        </div>
    </div>
</div>
