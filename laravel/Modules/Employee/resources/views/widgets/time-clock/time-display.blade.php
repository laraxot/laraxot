<?php

declare(strict_types=1);

?>
<div class="timeclock-display text-center p-6">
    <div class="time-section mb-4">
        <div class="current-time text-4xl font-bold text-gray-900 dark:text-white mb-2">
            {{ $time }}
        </div>
        <div class="current-date text-sm text-gray-600 dark:text-gray-400 mb-4">
            {{ $date }}
        </div>
        
        @if($activeSession)
            <div class="active-session flex items-center justify-center gap-2 text-sm">
                <div class="flex items-center gap-1">
                    <div class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></div>
                    <span class="text-green-600 dark:text-green-400 font-medium">
                        {{ __('employee::widgets.timeclock.session_active') }}
                    </span>
                </div>
                <span class="text-gray-500">{{ $sessionTime }}</span>
            </div>
        @else
            <div class="inactive-session text-sm text-gray-500">
                {{ __('employee::widgets.timeclock.no_active_session') }}
            </div>
        @endif
    </div>
</div>

<style>
.timeclock-widget .fi-section-content {
    padding: 0 !important;
}

.timeclock-display {
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-radius: 12px;
    border: 1px solid #e2e8f0;
}

.dark .timeclock-display {
    background: linear-gradient(135deg, #1e293b 0%, #334155 100%);
    border-color: #475569;
}

.current-time {
    font-family: 'SF Mono', 'Monaco', 'Inconsolata', 'Roboto Mono', monospace;
    letter-spacing: -0.02em;
}

@media (max-width: 768px) {
    .current-time {
        font-size: 2.5rem;
    }
}
</style>
