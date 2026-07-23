{{--
/**
 * Priority Badge Component
 * 
 * Displays ticket priority with appropriate styling
 * Works with Modules\Fixcity\Enums\TicketPriorityEnum
 * 
 * @param TicketPriorityEnum $priority The ticket priority enum instance
 */
--}}

@props([
    'priority'
])

@php
    use Modules\Fixcity\Enums\TicketPriorityEnum;
    
    // Ensure priority is an enum instance
    if (is_string($priority)) {
        $priority = TicketPriorityEnum::from($priority);
    }
    
    // Get color class and label from enum
    $colorClass = $priority->getColorClass();
    $label = $priority->getLabel();
    
    // Map badge-* classes to bg-* classes for Bootstrap Italia
    $variant = match(true) {
        str_contains($colorClass, 'danger') => 'danger',
        str_contains($colorClass, 'warning') => 'warning',
        str_contains($colorClass, 'info') => 'info',
        default => 'secondary',
    };
@endphp

<x-pub_theme::badge :variant="$variant" {{ $attributes }}>
    {{ $label }}
</x-pub_theme::badge>

