{{-- 
/**
 * Progress Indicators Component - Bootstrap Italia Compliant
 * 
 * Progress indicators show the status and advancement of an operation
 * Includes Donuts (circular), Progress Bars (linear), and Spinners (activity)
 * 
 * @param string $type Type of progress indicator: 'donut', 'bar', 'spinner'
 * @param string $id Unique ID for the progress indicator
 * @param float $value Progress value (0.0 to 1.0 for donut/bar, ignored for spinner)
 * @param string $size Size variant: 'sm', 'md', 'lg', 'xl' (for spinner)
 * @param bool $active Whether the indicator is active/animated
 * @param string $label Accessible label for screen readers
 * @param string $color Color variant for progress bar: 'primary', 'success', 'warning', 'danger'
 * @param bool $striped Whether to use striped pattern (progress bar)
 * @param bool $indeterminate Whether progress is indeterminate (progress bar)
 * @param bool $showLabel Whether to show percentage label (progress bar)
 * @param string $buttonText Text for progress button variant
 * @param bool $double Whether to use double spinner variant
 */
--}}

@props([
    'type' => 'spinner', // 'donut', 'bar', 'spinner'
    'id' => 'progress-' . uniqid(),
    'value' => 0.0,
    'size' => 'md', // 'sm', 'md', 'lg', 'xl'
    'active' => true,
    'label' => 'Caricamento in corso...',
    'color' => 'primary', // 'primary', 'success', 'warning', 'danger'
    'striped' => false,
    'indeterminate' => false,
    'showLabel' => false,
    'buttonText' => '',
    'double' => false
])

@php
    $valuePercent = round($value * 100);
    $progressBarClasses = collect(['progress-bar']);
    
    // Color classes for progress bar
    switch ($color) {
        case 'success':
            $progressBarClasses->push('bg-success');
            break;
        case 'warning':
            $progressBarClasses->push('bg-warning');
            break;
        case 'danger':
            $progressBarClasses->push('bg-danger');
            break;
        default:
            // primary is default, no additional class needed
            break;
    }
    
    // Striped and animated classes
    if ($striped) {
        $progressBarClasses->push('progress-bar-striped');
        if ($active) {
            $progressBarClasses->push('progress-bar-animated');
        }
    }
    
    // Spinner size classes
    $spinnerClasses = collect(['progress-spinner']);
    if ($active) {
        $spinnerClasses->push('progress-spinner-active');
    }
    if ($double) {
        $spinnerClasses->push('progress-spinner-double');
    }
    
    switch ($size) {
        case 'sm':
            $spinnerClasses->push('size-sm');
            break;
        case 'lg':
            $spinnerClasses->push('size-lg');
            break;
        case 'xl':
            $spinnerClasses->push('size-xl');
            break;
        default:
            // md is default, no additional class needed
            break;
    }
@endphp

@if($type === 'donut')
    {{-- Donut Progress Indicator --}}
    <div class="progress-donut-wrapper">
        <div 
            class="progress-donut" 
            id="{{ $id }}"
            data-bs-progress-donut
            @if($value > 0) data-bs-value="{{ $value }}" @endif
            {{ $attributes }}
        ></div>
        <span class="visually-hidden">{{ $label }} {{ $valuePercent }}%</span>
    </div>

@elseif($type === 'bar')
    {{-- Progress Bar --}}
    @if($buttonText)
        {{-- Progress Button Variant --}}
        <button class="btn btn-primary" type="button" disabled>
            {{ $buttonText }}
            <div class="progress ms-2" style="width: 5rem; height: 1rem;">
                <div 
                    class="{{ $progressBarClasses->implode(' ') }}" 
                    role="progressbar"
                    @if($indeterminate)
                        style="width: 100%"
                        aria-valuenow="100"
                        aria-valuemin="0"
                        aria-valuemax="100"
                    @else
                        style="width: {{ $valuePercent }}%"
                        aria-valuenow="{{ $valuePercent }}"
                        aria-valuemin="0"
                        aria-valuemax="100"
                    @endif
                    aria-label="{{ $label }}"
                ></div>
            </div>
        </button>
    @else
        {{-- Standard Progress Bar --}}
        <div class="progress" {{ $attributes }}>
            <div 
                class="{{ $progressBarClasses->implode(' ') }}" 
                role="progressbar"
                @if($indeterminate)
                    style="width: 100%"
                    aria-valuenow="100"
                    aria-valuemin="0"
                    aria-valuemax="100"
                @else
                    style="width: {{ $valuePercent }}%"
                    aria-valuenow="{{ $valuePercent }}"
                    aria-valuemin="0"
                    aria-valuemax="100"
                @endif
                aria-label="{{ $label }}"
            >
                @if($showLabel && !$indeterminate)
                    {{ $valuePercent }}%
                @endif
            </div>
        </div>
    @endif

@else
    {{-- Spinner (default) --}}
    <div class="{{ $spinnerClasses->implode(' ') }}" {{ $attributes }}>
        <span class="visually-hidden">{{ $label }}</span>
    </div>
@endif

{{-- JavaScript for Donut Progress (if needed) --}}
@if($type === 'donut')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-initialize donut progress indicators
    const donutElements = document.querySelectorAll('[data-bs-progress-donut]');
    
    donutElements.forEach(function(element) {
        // Check if ProgressDonut is available (Bootstrap Italia JS)
        if (typeof window.ProgressDonut !== 'undefined') {
            const value = parseFloat(element.getAttribute('data-bs-value')) || 0;
            new window.ProgressDonut(element, {
                value: value,
                color: '#0066CC',
                backgroundColor: '#f0f0f0',
                strokeWidth: 8
            });
        }
    });
});
</script>
@endif

{{-- 
Usage Examples:

1. Basic spinner (default):
<x-feedback.progress-indicators />

2. Active spinner with different sizes:
<x-feedback.progress-indicators 
    type="spinner" 
    size="sm" 
    :active="true" 
    label="Caricamento piccolo..." />

<x-feedback.progress-indicators 
    type="spinner" 
    size="lg" 
    :active="true" />

<x-feedback.progress-indicators 
    type="spinner" 
    size="xl" 
    :active="true" />

3. Double spinner:
<x-feedback.progress-indicators 
    type="spinner" 
    :double="true" 
    :active="true" />

4. Donut progress indicator:
<x-feedback.progress-indicators 
    type="donut" 
    :value="0.75" 
    label="Avanzamento operazione" />

5. Basic progress bar:
<x-feedback.progress-indicators 
    type="bar" 
    :value="0.5" 
    :show-label="true" />

6. Colored progress bars:
<x-feedback.progress-indicators 
    type="bar" 
    :value="0.25" 
    color="success" 
    :show-label="true" />

<x-feedback.progress-indicators 
    type="bar" 
    :value="0.75" 
    color="warning" 
    :show-label="true" />

<x-feedback.progress-indicators 
    type="bar" 
    :value="0.9" 
    color="danger" 
    :show-label="true" />

7. Striped and animated progress bar:
<x-feedback.progress-indicators 
    type="bar" 
    :value="0.4" 
    :striped="true" 
    :active="true" />

8. Indeterminate progress bar:
<x-feedback.progress-indicators 
    type="bar" 
    :indeterminate="true" 
    :striped="true" 
    :active="true" 
    label="Elaborazione in corso..." />

9. Progress button:
<x-feedback.progress-indicators 
    type="bar" 
    :value="0.65" 
    button-text="Caricamento" />

10. Loading states for different contexts:

<!-- File upload progress -->
<div class="mb-3">
    <label class="form-label">Upload file</label>
    <x-feedback.progress-indicators 
        type="bar" 
        :value="0.3" 
        color="primary" 
        :show-label="true" 
        label="Upload in corso" />
</div>

<!-- Form submission -->
<x-feedback.progress-indicators 
    type="spinner" 
    size="sm" 
    :active="true" 
    label="Invio modulo in corso..." 
    class="me-2" />

<!-- Data processing -->
<div class="text-center py-4">
    <x-feedback.progress-indicators 
        type="donut" 
        :value="0.85" 
        label="Elaborazione dati" />
    <p class="mt-2">Elaborazione in corso...</p>
</div>

11. Dynamic progress with Alpine.js:
<div x-data="{ progress: 0.2 }">
    <x-feedback.progress-indicators 
        type="bar" 
        x-bind:data-value="progress" 
        :show-label="true" />
    
    <div class="mt-2">
        <button class="btn btn-sm btn-outline-primary" @click="progress = Math.min(1, progress + 0.1)">
            Incrementa
        </button>
        <button class="btn btn-sm btn-outline-secondary" @click="progress = 0">
            Reset
        </button>
    </div>
</div>

12. Multiple progress indicators:
<div class="row">
    <div class="col-md-4">
        <h6>CPU Usage</h6>
        <x-feedback.progress-indicators 
            type="bar" 
            :value="0.45" 
            color="success" 
            :show-label="true" />
    </div>
    <div class="col-md-4">
        <h6>Memory Usage</h6>
        <x-feedback.progress-indicators 
            type="bar" 
            :value="0.72" 
            color="warning" 
            :show-label="true" />
    </div>
    <div class="col-md-4">
        <h6>Disk Usage</h6>
        <x-feedback.progress-indicators 
            type="bar" 
            :value="0.89" 
            color="danger" 
            :show-label="true" />
    </div>
</div>

Accessibility Features:
- Proper ARIA attributes (role="progressbar", aria-valuenow, aria-valuemin, aria-valuemax)
- Screen reader friendly labels with visually-hidden text
- Semantic HTML structure
- Keyboard accessibility support
- High contrast compliance

Bootstrap Italia Integration:
- Uses official Bootstrap Italia classes and structure
- Follows PA design system color schemes
- Compatible with Bootstrap Italia JavaScript components
- Supports all documented variants and configurations

Performance Considerations:
- Lightweight component with minimal DOM overhead
- Efficient CSS animations
- Optional JavaScript initialization for donut indicators
- No unnecessary re-renders for static progress values
--}}
