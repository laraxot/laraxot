{{--
Bootstrap Italia Tooltip Component
Interactive tooltip with Bootstrap Italia styling and accessibility features
--}}

@props([
    'text' => null,
    'position' => 'top', // top, bottom, start, end
    'trigger' => 'hover focus', // hover, focus, click, manual
    'html' => false,
    'animation' => true,
    'delay' => null,
    'title' => null
])

@php
$tooltipId = 'tooltip-' . uniqid();
$triggerElement = $slot->isEmpty() ? 'button' : 'span';
$tooltipText = $text ?? $title;
@endphp

@if($tooltipText)
    <span 
        {{ $attributes->merge(['class' => 'd-inline-block']) }}
        data-bs-toggle="tooltip"
        data-bs-placement="{{ $position }}"
        data-bs-title="{{ $tooltipText }}"
        @if($html) data-bs-html="true" @endif
        @if($trigger !== 'hover focus') data-bs-trigger="{{ $trigger }}" @endif
        @if($delay) data-bs-delay="{{ is_array($delay) ? json_encode($delay) : $delay }}" @endif
        @if(!$animation) data-bs-animation="false" @endif
    >
        @if($slot->isEmpty())
            <button type="button" class="btn btn-link p-0" style="text-decoration: none;">
                <svg class="icon icon-sm text-primary">
                    <use href="#it-info-circle"></use>
                </svg>
            </button>
        @else
            {{ $slot }}
        @endif
    </span>
@else
    {{ $slot }}
@endif

{{-- 
Usage Examples:

<!-- Simple tooltip -->
<x-tooltip text="This is a tooltip">
    <button class="btn btn-primary">Hover me</button>
</x-tooltip>

<!-- Tooltip with positioning -->
<x-tooltip text="Tooltip on the right" position="end">
    <span class="text-primary">Info</span>
</x-tooltip>

<!-- Tooltip with click trigger -->
<x-tooltip text="Click to show" trigger="click">
    <button class="btn btn-outline-primary">Click tooltip</button>
</x-tooltip>

<!-- Tooltip with delay -->
<x-tooltip text="Tooltip with delay" :delay="500">
    <svg class="icon icon-sm text-primary">
        <use href="#it-info-circle"></use>
    </svg>
</x-tooltip>

<!-- HTML tooltip -->
<x-tooltip 
    text="<strong>Bold text</strong> and <em>italic text</em>"
    :html="true"
    position="bottom"
>
    <button class="btn btn-secondary">HTML tooltip</button>
</x-tooltip>

<!-- Auto tooltip (shows info icon if no slot content) -->
<x-tooltip text="Automatic info icon tooltip" />
--}}

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize Bootstrap Italia tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush