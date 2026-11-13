{{--
/**
 * Status Badge Component
 * 
 * Displays ticket status with appropriate styling
 * Works with Modules\Fixcity\Enums\TicketStatusEnum
 * 
 * @param TicketStatusEnum $status The ticket status enum instance
 */
--}}

@props([
    'status'
])

@php
    use Modules\Fixcity\Enums\TicketStatusEnum;
    
    // Ensure status is an enum instance
    if (is_string($status)) {
        $status = TicketStatusEnum::from($status);
    }
    
    // TicketStatusEnum non ha getColorClass() (esiste solo su TicketPriorityEnum): il colore Filament
    // (gray/warning/info/orange/danger/success/secondary) e' gia' una stringa e il match qui sotto lo mappa.
    $colorClass = (string) $status->getColor();
    $label = $status->getLabel();
    
    // Map badge-* classes to bg-* classes for Bootstrap Italia
    $variant = match(true) {
        str_contains($colorClass, 'success') => 'success',
        str_contains($colorClass, 'danger') => 'danger',
        str_contains($colorClass, 'warning') => 'warning',
        str_contains($colorClass, 'info') => 'info',
        default => 'secondary',
    };
@endphp

<x-pub_theme::badge :variant="$variant" {{ $attributes }}>
    {{ $label }}
</x-pub_theme::badge>

