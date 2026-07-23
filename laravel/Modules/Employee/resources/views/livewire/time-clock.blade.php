<?php

declare(strict_types=1);

?>
<div class="max-w-4xl mx-auto p-6 space-y-6" wire:poll.5s="refreshData">
    <!-- Header Section -->
    <div class="bg-white rounded-lg shadow-sm border p-6">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Time Clock</h1>
                <p class="text-gray-600">{{ $employee?->full_name ?? 'Employee' }} - {{ $currentDate }}</p>
            </div>
            <div class="text-right">
                <div class="text-3xl font-mono font-bold text-gray-900">{{ $currentTime }}</div>
                <div class="flex items-center space-x-2 mt-2">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                          style="background-color: {{ $this->getStatusColor() === 'green' ? '#dcfce7' : ($this->getStatusColor() === 'orange' ? '#fed7aa' : ($this->getStatusColor() === 'red' ? '#fecaca' : '#f3f4f6')) }}; 
                                 color: {{ $this->getStatusColor() === 'green' ? '#166534' : ($this->getStatusColor() === 'orange' ? '#9a3412' : ($this->getStatusColor() === 'red' ? '#991b1b' : '#374151')) }}">
                        {{ $this->getStatusLabel() }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Action Section -->
    <div class="bg-white rounded-lg shadow-sm border p-8">
        <div class="text-center space-y-6">
            <!-- Main Clock Button -->
            <div class="flex justify-center">
                <button 
                    wire:click="clockAction"
                    class="inline-flex items-center px-8 py-4 text-xl font-semibold rounded-lg shadow-lg transition-all duration-200 hover:shadow-xl transform hover:scale-105 min-w-[200px] min-h-[80px]"
                    style="background-color: {{ $this->getActionButtonColor() === 'success' ? '#22c55e' : ($this->getActionButtonColor() === 'danger' ? '#ef4444' : ($this->getActionButtonColor() === 'warning' ? '#f59e0b' : '#3b82f6')) }}; color: white;"
                    @if(!$employee) disabled @endif>
                    
                    <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        @if($this->getActionButtonIcon() === 'heroicon-o-play')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h1m4 0h1m-6 4h.01M19 10a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        @elseif($this->getActionButtonIcon() === 'heroicon-o-stop')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 9h6v6H9z"></path>
                        @elseif($this->getActionButtonIcon() === 'heroicon-o-pause')
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        @else
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        @endif
                    </svg>
                    {{ $this->getActionLabel($nextAction) }}
                </button>
            </div>

            <!-- Notes Input -->
            <div class="max-w-md mx-auto">
                <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Notes (Optional)</label>
                <textarea 
                    wire:model="notes" 
                    id="notes"
                    rows="2" 
                    class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Add a note for this entry..."></textarea>
            </div>

            @if($lastEntry)
                <div class="text-sm text-gray-600">
                    Last entry: {{ $this->getActionLabel($lastEntry->type) }} at {{ $this->formatTime($lastEntry->time) }}
                </div>
            @endif
        </div>
    </div>

    <!-- Today's Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Hours Summary -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Today's Summary</h3>
            <div class="space-y-3">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Hours Worked:</span>
                    <span class="font-semibold text-lg text-blue-600">{{ number_format($workedHours, 2) }}h</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Current Status:</span>
                    <span class="font-medium" style="color: {{ $this->getStatusColor() === 'green' ? '#22c55e' : ($this->getStatusColor() === 'orange' ? '#f59e0b' : ($this->getStatusColor() === 'red' ? '#ef4444' : '#6b7280')) }}">
                        {{ $this->getStatusLabel() }}
                    </span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Total Entries:</span>
                    <span class="font-medium">{{ count($todayEntries) }}</span>
                </div>
            </div>
        </div>

        <!-- Today's Entries -->
        <div class="bg-white rounded-lg shadow-sm border p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Today's Entries</h3>
            @if(empty($todayEntries))
                <div class="text-center py-8 text-gray-500">
                    <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p>No entries recorded today</p>
                </div>
            @else
                <div class="space-y-3 max-h-64 overflow-y-auto">
                    @foreach($todayEntries as $entry)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                            <div class="flex items-center space-x-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                      style="background-color: {{ $this->getEntryTypeColor($entry['type']) === 'success' ? '#dcfce7' : ($this->getEntryTypeColor($entry['type']) === 'danger' ? '#fecaca' : ($this->getEntryTypeColor($entry['type']) === 'warning' ? '#fed7aa' : '#dbeafe')) }}; 
                                             color: {{ $this->getEntryTypeColor($entry['type']) === 'success' ? '#166534' : ($this->getEntryTypeColor($entry['type']) === 'danger' ? '#991b1b' : ($this->getEntryTypeColor($entry['type']) === 'warning' ? '#9a3412' : '#1e40af')) }}">
                                    {{ $this->formatEntryType($entry['type']) }}
                                </span>
                                <span class="text-sm font-medium text-gray-900">{{ $this->formatTime($entry['time']) }}</span>
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
                wire:click="refreshData"
                class="inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </button>
            
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
