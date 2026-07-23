{{-- Componente riutilizzabile per i badge di status dei predict --}}
@props([
    'status' => 'draft',
    'withAnimation' => false
])

@php
    $statusConfig = match($status) {
        'open' => [
            'classes' => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
            'iconColor' => 'bg-green-400',
            'label' => __('predict::common.labels.status.open.label'),
            'showAnimation' => true,
        ],
        'closed' => [
            'classes' => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
            'iconColor' => 'bg-red-400',
            'label' => __('predict::common.labels.status.closed.label'),
            'showAnimation' => false,
        ],
        default => [
            'classes' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
            'iconColor' => 'bg-yellow-400',
            'label' => ucfirst($status),
            'showAnimation' => false,
        ],
    };
@endphp

<span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $statusConfig['classes'] }}">
    <span class="w-2 h-2 {{ $statusConfig['iconColor'] }} rounded-full mr-1 {{ $withAnimation && $statusConfig['showAnimation'] ? 'animate-pulse' : '' }}"></span>
    {{ $statusConfig['label'] }}
</span> 