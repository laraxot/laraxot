<?php

declare(strict_types=1);

?>
<div class="leave-balance-display p-4">
    @foreach($balances as $key => $balance)
        <div class="balance-item mb-4 last:mb-0">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center gap-2">
                    <x-heroicon-o-{{ str_replace('heroicon-o-', '', $balance['icon']) }} class="w-4 h-4 text-{{ $balance['color'] }}-500" />
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        {{ $balance['label'] }}
                    </span>
                </div>
                <div class="text-right">
                    <span class="text-sm font-bold {{ $balance['total_minutes'] < 0 ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">
                        @if($balance['hours'] != 0 || $balance['minutes'] != 0)
                            @if($balance['hours'] < 0 || $balance['minutes'] < 0)
                                -{{ abs($balance['hours']) }}h {{ abs($balance['minutes']) }}m
                            @else
                                {{ $balance['hours'] }}h {{ $balance['minutes'] }}m
                            @endif
                        @else
                            0
                        @endif
                    </span>
                </div>
            </div>
            
            <div class="progress-bar-container">
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                    @php
                        $maxMinutes = $type === 'monthly' ? 600 : 7200; // 10h monthly, 120h annual max
                        $percentage = $balance['total_minutes'] > 0 
                            ? min(($balance['total_minutes'] / $maxMinutes) * 100, 100)
                            : 0;
                        $barColor = $balance['total_minutes'] < 0 ? 'red' : $balance['color'];
                    @endphp
                    
                    <div class="bg-{{ $barColor }}-500 h-2 rounded-full transition-all duration-300" 
                         style="width: {{ $percentage }}%">
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>

<style>
.leave-balance-widget .fi-tabs-content {
    padding-top: 1rem;
}

.balance-item {
    transition: all 0.2s ease-in-out;
}

.balance-item:hover {
    transform: translateY(-1px);
}

.progress-bar-container {
    position: relative;
}

.progress-bar-container::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: linear-gradient(90deg, transparent 0%, rgba(255,255,255,0.1) 50%, transparent 100%);
    border-radius: 9999px;
    animation: shimmer 2s infinite;
}

@keyframes shimmer {
    0% { transform: translateX(-100%); }
    100% { transform: translateX(100%); }
}

@media (max-width: 768px) {
    .leave-balance-display {
        padding: 0.75rem;
    }
    
    .balance-item {
        margin-bottom: 0.75rem;
    }
}
</style>
