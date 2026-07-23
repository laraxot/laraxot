<?php

declare(strict_types=1);

?>
<x-filament-widgets::widget>
    <x-filament::section>
        <div class="space-y-6">
            <!-- Header with current time and date -->
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-white">
                        {{ $sessionData['current_time'] }}
                    </h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        {{ $currentDate }}
                    </p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Ore lavorate oggi</p>
                    <p class="text-xl font-semibold text-gray-900 dark:text-white">
                        {{ $sessionData['session_duration'] }}
                    </p>
                </div>
            </div>

            <!-- Status Card -->
            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg p-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <!-- Status Indicator -->
                        <div class="flex-shrink-0">
                            @php
                                $statusColor = match($sessionData['status']) {
                                    'clocked_in' => 'bg-green-500',
                                    'on_break' => 'bg-yellow-500',
                                    'clocked_out' => 'bg-gray-500',
                                    default => 'bg-red-500'
                                };
                            @endphp
                            <div class="w-3 h-3 {{ $statusColor }} rounded-full animate-pulse"></div>
                        </div>
                        
                        <div>
                            <p class="text-lg font-medium text-gray-900 dark:text-white">
                                {{ $sessionData['status_label'] }}
                            </p>
                            @if($sessionData['last_entry_time'] ?? null)
                                <p class="text-sm text-gray-500 dark:text-gray-400">
                                    Ultima timbratura: {{ $sessionData['last_entry_time'] }}
                                </p>
                            @endif
                        </div>
                    </div>

                    <!-- Action Button -->
                    @if($sessionData['can_clock'])
                        <div class="flex-shrink-0">
                            {{ $this->clockAction }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <x-heroicon-o-clock class="w-6 h-6 text-blue-600 dark:text-blue-400" />
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-blue-900 dark:text-blue-100">
                                Ore oggi
                            </p>
                            <p class="text-lg font-semibold text-blue-600 dark:text-blue-400">
                                {{ $sessionData['session_duration'] }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <x-heroicon-o-calendar-days class="w-6 h-6 text-green-600 dark:text-green-400" />
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-900 dark:text-green-100">
                                Settimana
                            </p>
                            <p class="text-lg font-semibold text-green-600 dark:text-green-400">
                                --:--
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-purple-50 dark:bg-purple-900/20 rounded-lg p-4">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <x-heroicon-o-chart-bar class="w-6 h-6 text-purple-600 dark:text-purple-400" />
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-purple-900 dark:text-purple-100">
                                Mese
                            </p>
                            <p class="text-lg font-semibold text-purple-600 dark:text-purple-400">
                                --:--
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex justify-between items-center pt-4 border-t border-gray-200 dark:border-gray-700">
                <div class="flex space-x-3">
                    {{ $this->viewTodayEntriesAction }}
                </div>
                
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    Aggiornato automaticamente ogni 30 secondi
                </div>
            </div>
        </div>
    </x-filament::section>

    <x-filament-actions::modals />
</x-filament-widgets::widget>
