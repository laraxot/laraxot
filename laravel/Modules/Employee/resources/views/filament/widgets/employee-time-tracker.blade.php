<?php

declare(strict_types=1);

?>
<x-filament::widget>
    <div class="grid grid-cols-3 gap-6 items-center p-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm border border-gray-200 dark:border-gray-700"
         wire:poll.10s="updateTimeData">
        
        {{-- Colonna SINISTRA: Data e Ora Dettagliata --}}
        <div class="text-center space-y-2">
            {{-- Orario principale --}}
            <div class="text-4xl font-mono font-bold text-gray-900 dark:text-gray-100 leading-none">
                {{ $currentTime }}
            </div>
            
            {{-- Giorno della settimana --}}
            <div class="text-xs uppercase tracking-wider text-gray-600 dark:text-gray-400 font-medium">
                {{ $dayOfWeek }}
            </div>
            
            {{-- Data completa --}}
            <div class="space-y-1">
                <div class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                    {{ $dayNumber }} {{ $monthName }}
                </div>
                <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ $year }}
                </div>
            </div>
        </div>

        {{-- Colonna CENTRO: Timbrature della Giornata --}}
        <div class="text-center space-y-3">
            {{-- Stato giornata --}}
            @if($todayStatus === 'active')
                <div class="text-xs font-medium text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 px-2 py-1 rounded-full">
                    🟢 Sessione Attiva
                </div>
            @elseif($todayStatus === 'completed')
                <div class="text-xs font-medium text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/20 px-2 py-1 rounded-full">
                    ✅ Giornata Completata
                </div>
            @else
                <div class="text-xs text-gray-500 dark:text-gray-400 bg-gray-50 dark:bg-gray-900/20 px-2 py-1 rounded-full">
                    ⏰ In Attesa di Timbratura
                </div>
            @endif

            {{-- Lista timbrature con badge --}}
            <div class="flex flex-wrap justify-center gap-2">
                @forelse($todayEntries as $entry)
                    <span class="inline-flex items-center px-3 py-1.5 text-sm font-medium rounded-full shadow-sm 
                              {{ $entry['type'] === 'clock_in' 
                                 ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200 border border-green-200 dark:border-green-700' 
                                 : 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 border border-red-200 dark:border-red-700' }}">
                        @if($entry['type'] === 'clock_in')
                            🟢 
                        @else
                            🔴 
                        @endif
                        {{ $entry['time'] }}
                        
                        @if($entry['status'] === 'pending')
                            <span class="ml-1 text-xs opacity-75">⏳</span>
                        @elseif($entry['status'] === 'approved')
                            <span class="ml-1 text-xs opacity-75">✅</span>
                        @endif
                    </span>
                @empty
                    <span class="text-sm text-gray-500 dark:text-gray-400 italic">
                        Nessuna timbratura oggi
                    </span>
                @endforelse
            </div>
        </div>

        {{-- Colonna DESTRA: Pulsante Azione Dinamico --}}
        <div class="text-center">
            @if($isClockedIn)
                {{-- Pulsante Clock Out --}}
                <button 
                    wire:click="clockOut"
                    class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-4 px-8 rounded-lg 
                           transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105
                           border border-red-700 dark:border-red-600"
                    wire:loading.attr="disabled"
                    wire:target="clockOut">
                    
                    <div class="flex items-center justify-center space-x-2">
                        <span wire:loading.remove wire:target="clockOut">
                            🔴
                        </span>
                        <span wire:loading wire:target="clockOut">
                            ⏳
                        </span>
                        <span>
                            Clock Out
                        </span>
                    </div>
                    
                    <div class="text-xs opacity-80 mt-1" wire:loading.remove wire:target="clockOut">
                        Termina sessione
                    </div>
                </button>
            @else
                {{-- Pulsante Clock In --}}
                <button 
                    wire:click="clockIn"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-4 px-8 rounded-lg 
                           transition-all duration-200 shadow-md hover:shadow-lg transform hover:scale-105
                           border border-green-700 dark:border-green-600"
                    wire:loading.attr="disabled"
                    wire:target="clockIn">
                    
                    <div class="flex items-center justify-center space-x-2">
                        <span wire:loading.remove wire:target="clockIn">
                            🟢
                        </span>
                        <span wire:loading wire:target="clockIn">
                            ⏳
                        </span>
                        <span>
                            Clock In
                        </span>
                    </div>
                    
                    <div class="text-xs opacity-80 mt-1" wire:loading.remove wire:target="clockIn">
                        Inizia sessione
                    </div>
                </button>
            @endif
            
            {{-- Contatore timbrature --}}
            @if(count($todayEntries) > 0)
                <div class="mt-3 text-xs text-gray-500 dark:text-gray-400">
                    {{ count($todayEntries) }} timbratura{{ count($todayEntries) !== 1 ? 'e' : '' }} oggi
                </div>
            @endif
        </div>
    </div>
</x-filament::widget>
