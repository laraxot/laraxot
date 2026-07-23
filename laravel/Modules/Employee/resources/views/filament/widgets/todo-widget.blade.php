<?php

declare(strict_types=1);

?>
{{-- TodoWidget View - HR Task Management --}}
<x-filament-widgets::widget>
    <x-filament::section>
        <x-slot name="heading">
            {{ __('employee::widgets.todo.title') }}
        </x-slot>

        <x-slot name="description">
            {{ __('employee::widgets.todo.description') }}
        </x-slot>

        <div class="space-y-3">
            @forelse($todoItems as $item)
                <div class="flex items-start space-x-3 p-4 rounded-lg border {{ $this->getPriorityColor($item['priority']) }} hover:shadow-sm transition-shadow">
                    {{-- Priority Icon --}}
                    <div class="flex-shrink-0 mt-1">
                        <x-heroicon-o-exclamation-triangle 
                            class="w-5 h-5 {{ $item['priority'] === 'high' ? 'text-red-500' : ($item['priority'] === 'medium' ? 'text-yellow-500' : 'text-green-500') }}"
                        />
                    </div>

                    {{-- Task Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <h4 class="text-sm font-medium text-gray-900">
                                    {{ $item['title'] }}
                                </h4>
                                <p class="text-sm text-gray-600 mt-1">
                                    {{ $item['description'] }}
                                </p>
                                <div class="flex items-center space-x-4 mt-2">
                                    {{-- Priority Badge --}}
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getPriorityBadgeColor($item['priority']) }}">
                                        {{ __('employee::widgets.todo.priority.' . $item['priority']) }}
                                    </span>
                                    
                                    {{-- Due Date --}}
                                    <span class="text-xs text-gray-500 flex items-center">
                                        <x-heroicon-o-clock class="w-4 h-4 mr-1" />
                                        {{ __('employee::widgets.todo.due_date') }}: {{ $item['due_date']->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>

                            {{-- Action Button --}}
                            <div class="flex-shrink-0 ml-4">
                                <a 
                                    href="{{ $item['action_url'] }}" 
                                    class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors"
                                >
                                    <x-dynamic-component :component="$item['icon']" class="w-4 h-4 mr-1" />
                                    {{ __('employee::widgets.todo.action_button') }}
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                {{-- Empty State --}}
                <div class="text-center py-12">
                    <x-heroicon-o-check-circle class="mx-auto h-12 w-12 text-green-400" />
                    <h3 class="mt-2 text-sm font-medium text-gray-900">
                        {{ __('employee::widgets.todo.empty_state.title') }}
                    </h3>
                    <p class="mt-1 text-sm text-gray-500">
                        {{ __('employee::widgets.todo.empty_state.description') }}
                    </p>
                </div>
            @endforelse
        </div>

        {{-- Footer with View All Link --}}
        @if(count($todoItems) > 0)
            <div class="mt-6 pt-4 border-t border-gray-200">
                <div class="text-center">
                    <a 
                        href="#" 
                        class="text-sm text-blue-600 hover:text-blue-500 font-medium"
                    >
                        {{ __('employee::widgets.todo.view_all') }} →
                    </a>
                </div>
            </div>
        @endif
    </x-filament::section>
</x-filament-widgets::widget>
