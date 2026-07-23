<?php

declare(strict_types=1);

?>
{{-- UpcomingScheduleWidget View - 7-Day Schedule Overview --}}
<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            {{ __('employee::widgets.upcoming_schedule.title') }}
        </x-slot>

        <x-slot name="description">
            {{ __('employee::widgets.upcoming_schedule.description') }}
        </x-slot>

        {{-- Filter Tabs --}}
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8">
                    <button class="border-blue-500 text-blue-600 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        {{ __('employee::widgets.upcoming_schedule.tabs.all') }}
                    </button>
                    <button class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        {{ __('employee::widgets.upcoming_schedule.tabs.absences') }}
                    </button>
                    <button class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        {{ __('employee::widgets.upcoming_schedule.tabs.smart_working') }}
                    </button>
                    <button class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm">
                        {{ __('employee::widgets.upcoming_schedule.tabs.transfers') }}
                    </button>
                </nav>
            </div>
        </div>

        <div class="space-y-4">
            @forelse($upcomingEvents as $event)
                @php
                    $typeConfig = $this->getEventTypeConfig($event['event_type']);
                @endphp
                
                <div class="flex items-center space-x-4 p-4 rounded-lg border {{ $typeConfig['color'] }} hover:shadow-sm transition-shadow">
                    {{-- Employee Avatar --}}
                    <div class="flex-shrink-0">
                        <div class="w-10 h-10 rounded-full {{ $this->getAvatarColor($event['employee_initials']) }} flex items-center justify-center">
                            <span class="text-sm font-medium text-white">
                                {{ $event['employee_initials'] }}
                            </span>
                        </div>
                    </div>

                    {{-- Event Details --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center space-x-2">
                                    <h4 class="text-sm font-medium text-gray-900">
                                        {{ $event['employee_name'] }}
                                    </h4>
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getStatusBadgeColor($event['status']) }}">
                                        {{ __('employee::widgets.upcoming_schedule.status.' . $event['status']) }}
                                    </span>
                                </div>
                                
                                <div class="flex items-center space-x-2 mt-1">
                                    <x-dynamic-component :component="$typeConfig['icon']" class="w-4 h-4 text-gray-500" />
                                    <span class="text-sm text-gray-900 font-medium">
                                        {{ $event['event_title'] }}
                                    </span>
                                </div>

                                <div class="flex items-center space-x-4 mt-2 text-xs text-gray-500">
                                    {{-- Date Range --}}
                                    <span class="flex items-center">
                                        <x-heroicon-o-calendar class="w-4 h-4 mr-1" />
                                        @if($event['start_date']->isSameDay($event['end_date']))
                                            {{ $event['start_date']->format('d/m/Y') }}
                                        @else
                                            {{ $event['start_date']->format('d/m') }} - {{ $event['end_date']->format('d/m/Y') }}
                                        @endif
                                    </span>

                                    {{-- Location --}}
                                    @if($event['location'])
                                        <span class="flex items-center">
                                            <x-heroicon-o-map-pin class="w-4 h-4 mr-1" />
                                            {{ $event['location'] }}
                                        </span>
                                    @endif

                                    {{-- Duration --}}
                                    <span class="flex items-center">
                                        <x-heroicon-o-clock class="w-4 h-4 mr-1" />
                                        {{ $event['start_date']->diffInDays($event['end_date']) + 1 }} 
                                        {{ __('employee::widgets.upcoming_schedule.days') }}
                                    </span>
                                </div>

                                {{-- Notes --}}
                                @if($event['notes'])
                                    <p class="text-xs text-gray-600 mt-2">
                                        {{ $event['notes'] }}
                                    </p>
                                @endif
                            </div>

                            {{-- Event Type Badge --}}
                            <div class="flex-shrink-0 ml-4">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $typeConfig['badge_color'] }}">
                                    {{ __('employee::widgets.upcoming_schedule.types.' . $event['event_type']) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Empty State --}}
                <div class="text-center py-12">
                    <x-heroicon-o-calendar-days class="mx-auto h-12 w-12 text-gray-400" />
                    <h3 class="mt-2 text-sm font-medium text-gray-900">
                        {{ __('employee::widgets.upcoming_schedule.empty_state.title') }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('employee::widgets.upcoming_schedule.empty_state.description') }}
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Footer with View All Link --}}
        @if(count($upcomingEvents) > 0)
            <div class="mt-6 pt-4 border-t border-gray-200">
                <div class="text-center">
                    <a 
                        href="#" 
                        class="text-sm text-blue-600 hover:text-blue-500 font-medium"
                    >
                        {{ __('employee::widgets.upcoming_schedule.view_all') }} →
                    </a>
                </div>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
