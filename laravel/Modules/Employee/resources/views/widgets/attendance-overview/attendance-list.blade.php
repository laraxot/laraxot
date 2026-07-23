<?php

declare(strict_types=1);

?>
<div class="attendance-list p-4">
    @if(empty($items))
        <div class="empty-state text-center py-8">
            <div class="text-gray-400 dark:text-gray-600 mb-2">
                <x-heroicon-o-calendar-days class="w-12 h-12 mx-auto" />
            </div>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                {{ __('employee::widgets.attendance_overview.no_items', ['type' => $type]) }}
            </p>
        </div>
    @else
        <div class="space-y-3">
            @foreach($items as $item)
                <div class="attendance-item flex items-center gap-3 p-3 bg-gray-50 dark:bg-gray-800 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <div class="employee-avatar">
                        @if($item['employee']['avatar'])
                            <img src="{{ $item['employee']['avatar'] }}" 
                                 alt="{{ $item['employee']['name'] }}" 
                                 class="w-10 h-10 rounded-full object-cover">
                        @else
                            <div class="w-10 h-10 rounded-full bg-{{ $item['color'] }}-500 flex items-center justify-center text-white text-sm font-medium">
                                {{ $item['employee']['initials'] }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="font-medium text-gray-900 dark:text-white text-sm">
                                {{ $item['employee']['name'] }}
                            </span>
                            <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-{{ $item['color'] }}-100 text-{{ $item['color'] }}-800 dark:bg-{{ $item['color'] }}-900 dark:text-{{ $item['color'] }}-200">
                                {{ $item['type'] }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            {{ $item['period'] }}
                        </p>
                    </div>
                    
                    <div class="text-right">
                        <p class="text-xs text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($item['date'])->translatedFormat('D j M') }}
                        </p>
                    </div>
                </div>
            @endforeach
        </div>
        
        @if(count($items) > 5)
            <div class="mt-4 text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400">
                    {{ __('employee::widgets.attendance_overview.showing_first', ['count' => 5, 'total' => count($items)]) }}
                </p>
            </div>
        @endif
    @endif
</div>

<style>
.attendance-overview-widget .fi-tabs-content {
    max-height: 300px;
    overflow-y: auto;
}

.attendance-item {
    transition: all 0.2s ease-in-out;
}

.attendance-item:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
}

.employee-avatar {
    flex-shrink: 0;
}

@media (max-width: 768px) {
    .attendance-list {
        padding: 0.75rem;
    }
    
    .attendance-item {
        padding: 0.75rem;
    }
    
    .employee-avatar .w-10 {
        width: 2rem;
        height: 2rem;
    }
}
</style>
