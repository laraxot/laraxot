<?php

declare(strict_types=1);

?>
<div class="space-y-6" wire:poll.30s="refreshStats">
    <!-- Dashboard Header -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex items-center justify-between">
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Work Hours Dashboard</h2>
                <p class="text-gray-600">{{ $employee?->full_name ?? 'Employee' }} - Overview</p>
            </div>
            <div class="flex items-center space-x-4">
                <select wire:model="selectedPeriod" class="px-3 py-2 border border-gray-300 rounded-md text-sm focus:ring-blue-500 focus:border-blue-500">
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Today Hours -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Today</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $this->formatHours($todayHours) }}</p>
                </div>
            </div>
        </div>

        <!-- Week Hours -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">This Week</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $this->formatHours($weekHours) }}</p>
                </div>
            </div>
        </div>

        <!-- Average Hours -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Avg/Day</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $this->getAverageHoursPerDay() }}</p>
                </div>
            </div>
        </div>

        <!-- Work Days -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                        <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500">Work Days</p>
                    <p class="text-2xl font-semibold text-gray-900">{{ $this->getTotalWorkDays() }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Progress Bar -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex items-center justify-between mb-2">
            <h3 class="text-lg font-semibold text-gray-900">Weekly Progress</h3>
            <span class="text-sm text-gray-500">{{ $this->getProgressPercentage() }}% of 40h target</span>
        </div>
        <div class="w-full bg-gray-200 rounded-full h-3">
            <div class="h-3 rounded-full transition-all duration-300" 
                 style="width: {{ $this->getProgressPercentage() }}%; background-color: {{ $this->getProgressColor() === 'success' ? '#22c55e' : ($this->getProgressColor() === 'warning' ? '#f59e0b' : '#ef4444') }}"></div>
        </div>
        <div class="flex justify-between text-xs text-gray-500 mt-1">
            <span>0h</span>
            <span>{{ $this->formatHours($weekHours) }}</span>
            <span>40h</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Weekly Breakdown -->
        @if($selectedPeriod === 'week')
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Daily Breakdown</h3>
                <div class="space-y-3">
                    @foreach($weeklyStats as $day)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <span class="text-sm font-medium text-gray-900 w-8">{{ $day['day'] }}</span>
                                <span class="text-sm text-gray-600">{{ \Carbon\Carbon::parse($day['date'])->format('d/m') }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-semibold {{ $day['hours'] > 0 ? 'text-green-600' : 'text-gray-400' }}">
                                    {{ $day['formatted_hours'] }}
                                </span>
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="h-2 bg-blue-500 rounded-full" style="width: {{ min(100, ($day['hours'] / 8) * 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm border p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Weekly Breakdown</h3>
                <div class="space-y-3">
                    @foreach($monthlyStats as $week)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <span class="text-sm font-medium text-gray-900">Week {{ $week['week'] }}</span>
                                <span class="text-sm text-gray-600">{{ $week['start_date'] }} - {{ $week['end_date'] }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="text-sm font-semibold {{ $week['hours'] > 0 ? 'text-green-600' : 'text-gray-400' }}">
                                    {{ $week['formatted_hours'] }}
                                </span>
                                <div class="w-16 bg-gray-200 rounded-full h-2">
                                    <div class="h-2 bg-blue-500 rounded-full" style="width: {{ min(100, ($week['hours'] / 40) * 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Recent Entries -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Recent Entries</h3>
            @if(empty($recentEntries))
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>No recent entries</p>
                </div>
            @else
                <div class="space-y-3 max-h-80 overflow-y-auto">
                    @foreach($recentEntries as $entry)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                      style="background-color: {{ $entry['type_color'] === 'success' ? '#dcfce7' : ($entry['type_color'] === 'danger' ? '#fecaca' : ($entry['type_color'] === 'warning' ? '#fed7aa' : '#dbeafe')) }}; 
                                             color: {{ $entry['type_color'] === 'success' ? '#166534' : ($entry['type_color'] === 'danger' ? '#991b1b' : ($entry['type_color'] === 'warning' ? '#9a3412' : '#1e40af')) }}">
                                    {{ $entry['type_label'] }}
                                </span>
                                <div class="text-sm">
                                    <div class="font-medium text-gray-900">{{ $entry['date'] }}</div>
                                    <div class="text-gray-600">{{ $entry['time'] }}</div>
                                </div>
                            </div>
                            @if($entry['notes'])
                                <span class="text-xs text-gray-500 max-w-32 truncate" title="{{ $entry['notes'] }}">{{ $entry['notes'] }}</span>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
        <div class="flex flex-wrap gap-3">
            <button 
                wire:click="refreshStats"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh Data
            </button>
            
            <a href="{{ \Modules\Employee\Filament\Resources\WorkHourResource::getUrl('timeclock') }}" 
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Time Clock
            </a>
            
            <a href="{{ \Modules\Employee\Filament\Resources\WorkHourResource::getUrl('index') }}" 
               class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                View All Entries
            </a>
        </div>
    </div>
</div>
