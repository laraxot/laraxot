<?php

declare(strict_types=1);

?>
{{-- Weekly Time Table Widget - Replica esatta dipendentincloud.it --}}
<x-filament::widget>
    <x-filament::card>
        {{-- Header con navigazione settimana (replica esatta immagine) --}}
        <div class="border-b border-gray-200 dark:border-gray-700 pb-4 mb-6">
            <div class="flex items-center justify-between">
                {{-- Dropdown settimana corrente e navigazione --}}
                <div class="flex items-center space-x-4">
                    <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                        Settimana corrente
                    </span>
                    <span class="text-gray-400">•</span>
                    
                    {{-- Navigazione frecce --}}
                    <div class="flex items-center space-x-2">
                        <x-filament::button
                            color="gray"
                            size="sm"
                            icon="heroicon-o-chevron-left"
                            wire:click="previousWeek"
                        />
                        
                        <span class="text-sm font-medium px-3 py-1 bg-gray-100 dark:bg-gray-800 rounded">
                            {{ $weekStart->format('d M Y') }} → {{ $weekEnd->format('d M Y') }}
                        </span>
                        
                        <x-filament::button
                            color="gray"
                            size="sm"
                            icon="heroicon-o-chevron-right"
                            wire:click="nextWeek"
                        />
                    </div>
                </div>

                {{-- Pulsante export --}}
                <x-filament::button
                    color="gray"
                    size="sm"
                    icon="heroicon-o-arrow-down-tray"
                    wire:click="exportData"
                >
                    Esporta
                </x-filament::button>
            </div>
        </div>

        {{-- Tabella principale (replica esatta layout immagine) --}}
        <div class="overflow-x-auto">
            <table class="w-full">
                {{-- Header tabella --}}
                <thead>
                    <tr class="border-b border-gray-200 dark:border-gray-700">
                        <th class="text-left py-3 px-4 text-sm font-medium text-gray-500 uppercase tracking-wider">
                            Dipendente
                        </th>
                        <th class="text-center py-3 px-2 text-sm font-medium text-gray-500 uppercase tracking-wider">
                            Lavorate
                        </th>
                        <th class="text-center py-3 px-2 text-sm font-medium text-gray-500 uppercase tracking-wider">
                            Aggiunte
                        </th>
                        <th class="text-center py-3 px-2 text-sm font-medium text-gray-500 uppercase tracking-wider">
                            Ridotte
                        </th>
                        <th class="text-center py-3 px-2 text-sm font-medium text-gray-500 uppercase tracking-wider">
                            Contratto
                        </th>
                        
                        {{-- Colonne giorni settimana --}}
                        @if(isset($weekData) && is_array($weekData))
                            @foreach($weekData as $day)
                                <th class="text-center py-3 px-2 text-sm font-medium text-gray-500 uppercase tracking-wider">
                                    <div>{{ $day['dayName'] }} {{ $day['date'] }}</div>
                                    <div class="text-xs font-normal text-gray-400 mt-1">
                                        Da contratto: {{ $day['isWeekend'] ? '0h' : '8h' }}
                                    </div>
                                </th>
                            @endforeach
                        @endif
                    </tr>
                </thead>

                {{-- Riga dipendente --}}
                <tbody>
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                        {{-- Info dipendente con avatar --}}
                        <td class="py-4 px-4">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    {{-- Avatar dipendente (replica immagine) --}}
                                    <div class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">
                                        {{ substr($employeeInfo['name'] ?? 'D', 0, 1) }}
                                    </div>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $employeeInfo['name'] ?? 'Dipendente' }}
                                    </div>
                                    <div class="text-xs text-gray-500 uppercase font-semibold">
                                        {{ $employeeInfo['department']['name'] ?? 'SVILUPPO' }}
                                    </div>
                                </div>
                            </div>
                        </td>

                        {{-- Summary ore (replica esatta valori immagine) --}}
                        <td class="py-4 px-2 text-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $summaryData['workedHours'] ?? '0h' }}
                            </span>
                        </td>
                        <td class="py-4 px-2 text-center">
                            <span class="text-sm {{ $summaryData['hasAdded'] ?? false ? 'text-green-600' : 'text-gray-400' }}">
                                {{ $summaryData['addedHours'] ?? 'Nessuna' }}
                            </span>
                        </td>
                        <td class="py-4 px-2 text-center">
                            <span class="text-sm {{ $summaryData['hasReduced'] ?? false ? 'text-red-600' : 'text-gray-400' }}">
                                {{ $summaryData['reducedHours'] ?? 'Nessuna' }}
                            </span>
                        </td>
                        <td class="py-4 px-2 text-center">
                            <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $summaryData['contractHours'] ?? '0h' }}
                            </span>
                        </td>

                        {{-- Celle giorni settimana con ore e indicatori --}}
                        @if(isset($weekData) && is_array($weekData))
                            @foreach($weekData as $day)
                                <td class="py-4 px-2 text-center relative">
                                    {{-- Ore totali giorno --}}
                                    <div class="text-sm font-medium {{ $day['isToday'] ? 'text-blue-600' : 'text-gray-900 dark:text-gray-100' }}">
                                        {{ $day['totalHours'] > 0 ? number_format($day['totalHours'], 0) . 'h' : '0h' }}
                                    </div>
                                    
                                    {{-- Indicatori stato (replica immagine) --}}
                                    <div class="mt-2">
                                        @if($day['status'] === 'problems')
                                            {{-- Indicatore "Problemi" arancione con X --}}
                                            <div class="inline-flex items-center justify-center w-6 h-6 rounded border-2 border-red-500 bg-red-50">
                                                <span class="text-xs text-red-600 font-bold">✕</span>
                                            </div>
                                        @elseif($day['status'] === 'in_progress')
                                            {{-- Indicatore "In corso" arancione --}}
                                            <div class="inline-flex items-center justify-center w-6 h-6 rounded border-2 border-orange-500 bg-orange-50">
                                                <span class="text-xs text-orange-600 font-bold">●</span>
                                            </div>
                                        @elseif($day['status'] === 'completed')
                                            {{-- Indicatore completato --}}
                                            <div class="inline-flex items-center justify-center w-6 h-6 rounded border-2 border-gray-300 bg-gray-50">
                                                <span class="text-xs text-gray-500">●</span>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            @endforeach
                        @endif
                    </tr>
                </tbody>
            </table>
        </div>

        {{-- Timeline visualization (replica esatta della parte inferiore dell'immagine) --}}
        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
            <div class="relative">
                {{-- Griglia timeline --}}
                <div class="grid grid-cols-8 gap-0 relative" style="min-height: 400px;">
                    {{-- Colonna fasce orarie (sinistra) --}}
                    <div class="col-span-1 relative">
                        {{-- Fasce orarie 06:00-20:00 --}}
                        @if(isset($timelineData['timeSlots']))
                            @foreach($timelineData['timeSlots'] as $time => $label)
                                <div class="absolute left-0 text-xs text-gray-500 font-mono" 
                                     style="top: {{ $this->getTimePosition($time) }}%;">
                                    {{ $time }}
                                </div>
                            @endforeach
                        @endif
                    </div>

                    {{-- Colonne giorni settimana (7 colonne) --}}
                    @if(isset($weekData) && is_array($weekData))
                        @foreach($weekData as $dateKey => $day)
                            <div class="col-span-1 relative border-l border-gray-200 dark:border-gray-700" style="min-height: 400px;">
                                {{-- Sfondo griglia ore --}}
                                @for($hour = 6; $hour <= 20; $hour += 2)
                                    <div class="absolute w-full border-b border-gray-100 dark:border-gray-800" 
                                         style="top: {{ (($hour - 6) / 14) * 100 }}%; height: {{ (2 / 14) * 100 }}%;">
                                    </div>
                                @endfor

                                {{-- Blocchi sessioni di lavoro --}}
                                @if(isset($day['sessions']) && is_array($day['sessions']))
                                    @foreach($day['sessions'] as $session)
                                        @if(isset($session['position']))
                                            <div class="absolute left-1 right-1 rounded shadow-sm border-l-4 {{ $this->getSessionColorClass($session['color']) }}"
                                                 style="top: {{ $session['position']['top'] }}%; height: {{ $session['position']['height'] }}%;">
                                                {{-- Orari sessione --}}
                                                <div class="p-1 text-xs font-mono">
                                                    <div class="text-gray-700">{{ $session['start'] }}</div>
                                                    @if($session['end'])
                                                        <div class="text-gray-700">{{ $session['end'] }}</div>
                                                    @endif
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif

                                {{-- Linee orari di lavoro standard (08:00-18:00) --}}
                                <div class="absolute w-full border-t-2 border-blue-300" style="top: {{ $this->getTimePosition('08:00') }}%;"></div>
                                <div class="absolute w-full border-t-2 border-blue-300" style="top: {{ $this->getTimePosition('18:00') }}%;"></div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
        </div>

        {{-- Status bar (se necessario) --}}
        @if($showToleranceThreshold)
            <div class="mt-4 p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                <div class="text-sm text-yellow-800 dark:text-yellow-200">
                    <strong>Soglie di tolleranza attive:</strong> Entrata ±15min, Uscita ±15min
                </div>
            </div>
        @endif
    </x-filament::card>
</x-filament::widget>

{{-- CSS personalizzato per timeline --}}
<style>
.timeline-session-green {
    background: linear-gradient(135deg, #10B981 0%, #059669 100%);
    border-left-color: #047857;
}

.timeline-session-orange {
    background: linear-gradient(135deg, #F59E0B 0%, #D97706 100%);
    border-left-color: #B45309;
}

.timeline-session-red {
    background: linear-gradient(135deg, #EF4444 0%, #DC2626 100%);
    border-left-color: #B91C1C;
}

.timeline-session-active {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}
</style>

@script
<script>
    // Helper per calcolare posizioni timeline
    window.getTimePosition = function(time) {
        const [hours, minutes] = time.split(':').map(Number);
        const totalMinutes = (hours * 60) + minutes;
        const baseMinutes = 6 * 60; // 06:00
        const maxMinutes = 20 * 60; // 20:00
        
        return ((totalMinutes - baseMinutes) / (maxMinutes - baseMinutes)) * 100;
    };
</script>
@endscript
