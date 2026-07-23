@props([
    'value' => 0,
    'max' => 100,
    'variant' => 'primary',
    'height' => 'h-2',
    'showValue' => false,
    'valuePosition' => 'inside',
    'striped' => false,
    'animated' => false,
    'label' => null
])

@php
    $variants = [
        'primary' => [
            'bg' => 'bg-italia-blue-600',
            'text' => 'text-italia-blue-100'
        ],
        'secondary' => [
            'bg' => 'bg-gray-600',
            'text' => 'text-gray-100'
        ],
        'success' => [
            'bg' => 'bg-italia-green-600',
            'text' => 'text-italia-green-100'
        ],
        'warning' => [
            'bg' => 'bg-italia-yellow-600',
            'text' => 'text-italia-yellow-100'
        ],
        'danger' => [
            'bg' => 'bg-italia-red-600',
            'text' => 'text-italia-red-100'
        ],
        'info' => [
            'bg' => 'bg-blue-600',
            'text' => 'text-blue-100'
        ]
    ];

    $variantClasses = $variants[$variant] ?? $variants['primary'];
    $percentage = min(max($value, 0), $max);
    $width = ($percentage / $max) * 100;
    $stripedClass = $striped ? 'bg-stripes' : '';
    $animatedClass = $animated ? 'animate-pulse' : '';
    
    $valueText = $showValue ? "{$percentage}%" : '';
@endphp

<div class="progress-container">
    @if($label)
        <div class="flex justify-between items-center mb-2">
            <span class="text-sm font-medium text-gray-700">{{ $label }}</span>
            @if($showValue && $valuePosition === 'outside')
                <span class="text-sm text-gray-500">{{ $valueText }}</span>
            @endif
        </div>
    @endif
    
    <div class="w-full bg-gray-200 rounded-full overflow-hidden {{ $height }}">
        <div 
            class="progress-bar {{ $variantClasses['bg'] }} {{ $stripedClass }} {{ $animatedClass }} {{ $height }} rounded-full transition-all duration-300 ease-out"
            role="progressbar"
            style="width: {{ $width }}%"
            aria-valuenow="{{ $percentage }}"
            aria-valuemin="0"
            aria-valuemax="{{ $max }}"
        >
            @if($showValue && $valuePosition === 'inside')
                <span class="text-xs font-bold {{ $variantClasses['text'] }} whitespace-nowrap">
                    {{ $valueText }}
                </span>
            @endif
        </div>
    </div>
    
    @if(!$label && $showValue && $valuePosition === 'outside')
        <div class="mt-1 text-right">
            <span class="text-xs text-gray-500">{{ $valueText }}</span>
        </div>
    @endif
</div>

@if($striped)
    <style>
        .bg-stripes {
            background-image: linear-gradient(
                45deg,
                rgba(255, 255, 255, 0.15) 25%,
                transparent 25%,
                transparent 50%,
                rgba(255, 255, 255, 0.15) 50%,
                rgba(255, 255, 255, 0.15) 75%,
                transparent 75%,
                transparent
            );
            background-size: 1rem 1rem;
        }
    </style>
@endif