<?php

declare(strict_types=1);

?>
{{-- TodayPresenceWidget View - Real-time Presence Tracking --}}
<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            {{ __('employee::widgets.today_presence.title') }}
        </x-slot>

        <x-slot name="description">
            {{ __('employee::widgets.today_presence.description') }}
        </x-slot>
        <div class="grid grid-cols-2 gap-4 mb-6">
            {{-- Present Count --}}
            <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 rounded-lg p-4 text-center border border-green-200 dark:border-green-800">
                <div class="text-3xl font-bold text-green-700 dark:text-green-300">{{ $presenceData['total_present'] }}</div>
                <div class="text-sm font-medium text-green-600 dark:text-green-400">@lang('employee::widgets.today_presence.present')</div>
            </div>
            
            {{-- Absent Count --}}
            <div class="bg-gradient-to-r from-red-50 to-pink-50 dark:from-red-900/20 dark:to-pink-900/20 rounded-lg p-4 text-center border border-red-200 dark:border-red-800">
                <div class="text-3xl font-bold text-red-700 dark:text-red-300">{{ $presenceData['total_absent'] }}</div>
                <div class="text-sm font-medium text-red-600 dark:text-red-400">@lang('employee::widgets.today_presence.absent')</div>
            </div>
        </div>

        {{-- Present Employees --}}
        <div class="mb-8">
            <h3 class="text-lg font-medium text-gray-900 mb-4">
                {{ __('employee::widgets.today_presence.present_employees') }}
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($presenceData['present'] as $employee)
                    @php
                        $workTypeConfig = $this->getWorkTypeConfig($employee['work_type']);
                    @endphp
                    
                    <div class="flex items-center space-x-3 p-3 bg-white border border-gray-200 rounded-lg hover:shadow-sm transition-shadow">
                        {{-- Employee Avatar --}}
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 rounded-full {{ $this->getAvatarColor($employee['initials']) }} flex items-center justify-center">
                                <span class="text-sm font-medium text-white">
                                    {{ $employee['initials'] }}
                                </span>
                            </div>
                        </div>

                        {{-- Employee Info --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900 truncate">
                                        {{ $employee['name'] }}
                                    </p>
                                    <p class="text-xs text-gray-500">
                                        {{ $employee['department'] }}
                                    </p>
                                </div>
                                
                                {{-- Work Type Icon --}}
                                <div class="flex-shrink-0 ml-2">
                                    <div class="p-1 rounded {{ $workTypeConfig['bg'] }}">
                                        <x-dynamic-component :component="$workTypeConfig['icon']" class="w-4 h-4 {{ $workTypeConfig['color'] }}" />
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500">
                                <span class="flex items-center">
                                    <x-heroicon-o-clock class="w-3 h-3 mr-1" />
                                    {{ $employee['check_in_time'] }}
                                </span>
                                <span class="flex items-center">
                                    <x-heroicon-o-map-pin class="w-3 h-3 mr-1" />
                                    {{ $employee['location'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Absent Employees --}}
        @if(count($presenceData['absent']) > 0)
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">
                    {{ __('employee::widgets.today_presence.absent_employees') }}
                </h3>
                
                <div class="space-y-3">
                    @foreach($presenceData['absent'] as $employee)
                        @php
                            $absenceConfig = $this->getAbsenceTypeConfig($employee['absence_type']);
                        @endphp
                        
                        <div class="flex items-center space-x-3 p-3 bg-gray-50 border border-gray-200 rounded-lg">
                            {{-- Employee Avatar --}}
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-full {{ $this->getAvatarColor($employee['initials']) }} flex items-center justify-center opacity-60">
                                    <span class="text-sm font-medium text-white">
                                        {{ $employee['initials'] }}
                                    </span>
                                </div>
                            </div>

                            {{-- Employee Info --}}
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="text-sm font-medium text-gray-700">
                                            {{ $employee['name'] }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ $employee['department'] }}
                                        </p>
                                    </div>
                                    
                                    {{-- Absence Type Icon --}}
                                    <div class="flex-shrink-0 ml-2">
                                        <div class="p-1 rounded {{ $absenceConfig['bg'] }}">
                                            <x-dynamic-component :component="$absenceConfig['icon']" class="w-4 h-4 {{ $absenceConfig['color'] }}" />
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mt-1 flex items-center space-x-4 text-xs text-gray-500">
                                    <span>{{ $employee['absence_reason'] }}</span>
                                    <span class="flex items-center">
                                        <x-heroicon-o-arrow-right class="w-3 h-3 mr-1" />
                                        {{ __('employee::widgets.today_presence.return_date') }}: {{ $employee['return_date'] }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Footer with View All Link --}}
        <div class="pt-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    {{ __('employee::widgets.today_presence.last_updated') }}: {{ now()->format('H:i') }}
                </div>
                <a 
                    href="#" 
                    class="text-sm text-blue-600 hover:text-blue-500 font-medium"
                >
                    {{ __('employee::widgets.today_presence.view_details') }} →
                </a>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
