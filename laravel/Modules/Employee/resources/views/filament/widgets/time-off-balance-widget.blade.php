<?php

declare(strict_types=1);

?>
{{-- TimeOffBalanceWidget View - Leave Balance Display --}}
<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            {{ __('employee::widgets.time_off_balance.title') }}
        </x-slot>

        <x-slot name="description">
            {{ __('employee::widgets.time_off_balance.description', ['month' => $currentMonth]) }}
        </x-slot>

        {{-- Period Toggle --}}
        <div class="mb-6">
            <div class="flex items-center space-x-4">
                <span class="text-sm font-medium text-gray-700">
                    {{ __('employee::widgets.time_off_balance.view_period') }}:
                </span>
                <div class="flex rounded-md shadow-sm">
                    <button class="px-4 py-2 text-sm font-medium text-white bg-blue-600 border border-blue-600 rounded-l-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        {{ __('employee::widgets.time_off_balance.monthly') }}
                    </button>
                    <button class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        {{ __('employee::widgets.time_off_balance.annual') }}
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @foreach($timeOffBalances as $balance)
                @php
                    $colorClasses = $this->getColorClasses($balance['color'], $balance['current_balance']);
                    $progressPercentage = $this->getProgressPercentage($balance['used'], $balance['total_allowance']);
                @endphp
                
                <div class="p-4 rounded-lg border {{ $colorClasses['bg'] }} {{ $colorClasses['border'] }}">
                    {{-- Header --}}
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center space-x-2">
                            <x-dynamic-component :component="$balance['icon']" class="w-5 h-5 {{ $colorClasses['text'] }}" />
                            <h3 class="text-sm font-medium {{ $colorClasses['text'] }}">
                                {{ $balance['label'] }}
                            </h3>
                        </div>
                    </div>

                    {{-- Current Balance --}}
                    <div class="mb-4">
                        <div class="text-2xl font-bold {{ $colorClasses['text'] }}">
                            {{ $this->formatHoursMinutes($balance['current_balance']) }}
                        </div>
                        <div class="text-xs text-gray-600 mt-1">
                            {{ __('employee::widgets.time_off_balance.current_balance') }}
                        </div>
                    </div>

                    {{-- Progress Bar --}}
                    @if($balance['total_allowance'])
                        <div class="mb-3">
                            <div class="flex justify-between text-xs text-gray-600 mb-1">
                                <span>{{ __('employee::widgets.time_off_balance.used') }}: {{ $this->formatHoursMinutes($balance['used']) }}</span>
                                <span>{{ __('employee::widgets.time_off_balance.total') }}: {{ $this->formatHoursMinutes($balance['total_allowance']) }}</span>
                            </div>
                            <div class="w-full {{ $colorClasses['progress_bg'] }} rounded-full h-2">
                                <div 
                                    class="{{ $colorClasses['progress'] }} h-2 rounded-full transition-all duration-300"
                                    style="width: {{ $progressPercentage }}%"
                                ></div>
                            </div>
                        </div>
                    @else
                        <div class="mb-3">
                            <div class="text-xs text-gray-600">
                                {{ __('employee::widgets.time_off_balance.no_limit') }}
                            </div>
                        </div>
                    @endif

                    {{-- Details --}}
                    <div class="space-y-1">
                        @if($balance['current_balance'] < 0)
                            <div class="flex items-center text-xs text-red-600">
                                <x-heroicon-o-exclamation-triangle class="w-4 h-4 mr-1" />
                                {{ __('employee::widgets.time_off_balance.negative_balance') }}
                            </div>
                        @elseif($balance['current_balance'] == 0 && $balance['total_allowance'])
                            <div class="flex items-center text-xs text-yellow-600">
                                <x-heroicon-o-exclamation-circle class="w-4 h-4 mr-1" />
                                {{ __('employee::widgets.time_off_balance.exhausted') }}
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Summary Footer --}}
        <div class="mt-6 pt-4 border-t border-gray-200">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600">
                    {{ __('employee::widgets.time_off_balance.last_updated') }}: {{ now()->format('d/m/Y H:i') }}
                </div>
                <a 
                    href="#" 
                    class="text-sm text-blue-600 hover:text-blue-500 font-medium"
                >
                    {{ __('employee::widgets.time_off_balance.view_details') }} →
                </a>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
