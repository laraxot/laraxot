@props([
    'type' => 'info',
    'title' => null,
    'dismissible' => true,
    'icon' => null,
    'timeout' => null,
    'position' => 'fixed'
])

@php
    $types = [
        'info' => [
            'bg' => 'bg-blue-50',
            'border' => 'border-blue-200',
            'text' => 'text-blue-800',
            'icon' => 'heroicon-o-information-circle',
            'iconColor' => 'text-blue-400'
        ],
        'success' => [
            'bg' => 'bg-italia-green-50',
            'border' => 'border-italia-green-200',
            'text' => 'text-italia-green-800',
            'icon' => 'heroicon-o-check-circle',
            'iconColor' => 'text-italia-green-400'
        ],
        'warning' => [
            'bg' => 'bg-italia-yellow-50',
            'border' => 'border-italia-yellow-200',
            'text' => 'text-italia-yellow-800',
            'icon' => 'heroicon-o-exclamation-triangle',
            'iconColor' => 'text-italia-yellow-400'
        ],
        'danger' => [
            'bg' => 'bg-italia-red-50',
            'border' => 'border-italia-red-200',
            'text' => 'text-italia-red-800',
            'icon' => 'heroicon-o-x-circle',
            'iconColor' => 'text-italia-red-400'
        ],
        'primary' => [
            'bg' => 'bg-italia-blue-50',
            'border' => 'border-italia-blue-200',
            'text' => 'text-italia-blue-800',
            'icon' => 'heroicon-o-bell',
            'iconColor' => 'text-italia-blue-400'
        ]
    ];

    $typeClasses = $types[$type] ?? $types['info'];
    $customIcon = $icon ?? $typeClasses['icon'];
    $positionClasses = $position === 'fixed' ? 'fixed top-4 right-4 z-50' : 'relative';
@endphp

<div 
    {{ $attributes->merge([
        'class' => "notification {$positionClasses} max-w-sm w-full rounded-lg border p-4 shadow-lg {$typeClasses['bg']} {$typeClasses['border']}"
    ]) }}
    x-data="{ show: true }"
    x-show="show"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-x-full"
    x-transition:enter-end="opacity-100 transform translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-x-0"
    x-transition:leave-end="opacity-0 transform translate-x-full"
    role="alert"
    @if($timeout)
        x-init="setTimeout(() => show = false, {{ $timeout }})"
    @endif
>
    <div class="flex items-start">
        <div class="flex-shrink-0">
            <x-dynamic-component 
                :component="$customIcon" 
                class="h-5 w-5 {{ $typeClasses['iconColor'] }}" 
            />
        </div>
        
        <div class="ml-3 flex-1">
            @if($title)
                <h3 class="text-sm font-medium {{ $typeClasses['text'] }}">
                    {{ $title }}
                </h3>
            @endif
            
            <div class="mt-1 text-sm {{ $typeClasses['text'] }}">
                {{ $slot }}
            </div>
        </div>
        
        @if($dismissible)
            <div class="ml-4 flex flex-shrink-0">
                <button
                    type="button"
                    class="inline-flex rounded-md {{ $typeClasses['bg'] }} {{ $typeClasses['text'] }} hover:opacity-75 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
                    @click="show = false"
                >
                    <span class="sr-only">{{ __('pub_theme::notifications.close') }}</span>
                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        @endif
    </div>
</div>