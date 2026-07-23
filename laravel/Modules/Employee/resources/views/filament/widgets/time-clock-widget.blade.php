<?php

declare(strict_types=1);

?>
<x-filament-widgets::widget>
    <div class="flex items-center gap-6 h-24 w-full" wire:poll.1s="updateData">
        
        {{-- Left Column: Time and Date --}}
        <div class="flex-1 text-center">
            <div class="text-5xl font-mono font-bold text-gray-900 dark:text-gray-100">
                {{ $currentTime }}
            </div>
            <div class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                {{ $todayDate }}
            </div>
        </div>

        {{-- Center Column: Time Entries --}}
        <div class="flex-1 text-center">
            @if($isClockedIn)
                <div class="text-sm font-medium text-green-600 dark:text-green-400 mb-2">
                    Sessione attiva
                </div>
            @else
                 <div class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">
                    Nessuna sessione attiva
                </div>
            @endif
            
            <div class="flex flex-wrap gap-1 justify-center">
                @forelse($todayEntries as $entry)
                    <x-filament::badge 
                        :color="$entry['type'] === 'clock_in' ? 'success' : 'danger'"
                        size="sm"
                        class="cursor-pointer hover:scale-105 transition-transform">
                        {{ $entry['type'] === 'clock_in' ? '→' : '←' }} {{ $entry['time'] }}
                    </x-filament::badge>
                @empty
                    <x-filament::badge 
                        color="gray" 
                        size="sm"
                        icon="heroicon-o-clock"
                        class="italic">
                        Nessuna timbratura
                    </x-filament::badge>
                @endforelse
            </div>
        </div>

        {{-- Right Column: Action Button --}}
        <div class="flex-1 text-center">
            @if($isClockedIn)
                <x-filament::button 
                    wire:click="clockOut" 
                    color="danger"
                    size="lg"
                    icon="heroicon-o-arrow-left-on-rectangle">
                    Timbra uscita
                </x-filament::button>
            @else
                <x-filament::button 
                    wire:click="clockIn" 
                    color="success"
                    size="lg"
                    icon="heroicon-o-arrow-right-on-rectangle">
                    Timbra entrata
                </x-filament::button>
            @endif
        </div>
    </div>
</x-filament-widgets::widget>
