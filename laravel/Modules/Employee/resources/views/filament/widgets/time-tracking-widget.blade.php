<?php

declare(strict_types=1);

?>
<x-filament-widgets::widget>
    <div class="grid grid-cols-3 gap-6 items-center h-20" wire:poll.1s="updateTimeData">
        {{-- Colonna SINISTRA: Orario e Data --}}
        <div class="text-center">
            <div class="text-3xl font-mono font-bold text-gray-900 dark:text-gray-100">
                {{ $currentTime }}
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                {{ $todayDateFormatted }}
            </div>
        </div>

        {{-- Colonna CENTRO: Timbature della giornata --}}
        <div class="text-center">
            @if($todayStatus === 'active')
                <div class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2 flex items-center justify-center space-x-2">
                    <span class="inline-block w-2 h-2 bg-green-500 rounded-full"></span>
                    <span>@lang('employee::widgets.time_tracking.status.active')</span>
                </div>
            @elseif($todayStatus === 'completed')
                <div class="text-sm font-medium text-gray-900 dark:text-gray-100 mb-2 flex items-center justify-center space-x-2">
                    <span class="inline-block w-2 h-2 bg-gray-400 rounded-full"></span>
                    <span>@lang('employee::widgets.time_tracking.status.completed')</span>
                </div>
            @else
                <div class="text-sm text-gray-500 dark:text-gray-400 mb-2">@lang('employee::widgets.time_tracking.no_entries')</div>
            @endif

            {{-- Lista timbature di oggi --}}
            @forelse($todayEntries as $entry)
                <div class="text-sm text-gray-700 dark:text-gray-300">
                    <span class="inline-block w-2 h-2 {{ $entry['type'] === 'clock_in' ? 'bg-green-500' : 'bg-red-500' }} rounded-full mr-2"></span>{{ $entry['time'] }}
                </div>
            @empty
                {{-- Nessuna timbratura ancora --}}
            @endforelse
        </div>

        {{-- Colonna DESTRA: Pulsante Azione --}}
        <div class="text-center">
            @if($isClockedIn)
                <x-filament::button color="danger" size="lg" wire:click="clockOut">
                    @lang('employee::widgets.time_tracking.actions.clock_out')
                </x-filament::button>
            @else
                <x-filament::button color="success" size="lg" wire:click="clockIn">
                    @lang('employee::widgets.time_tracking.actions.clock_in')
                </x-filament::button>
            @endif
        </div>
    </div>
</x-filament-widgets::widget>
