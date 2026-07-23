{{--
/**
 * Spinner Component
 * 
 * Bootstrap Italia compliant spinner component with support for:
 * - Multiple variants (border, grow, dots, pulse)
 * - Different sizes (sm, md, lg, xl)
 * - Color variants (primary, secondary, success, warning, danger, info)
 * - Text labels
 * - Accessibility features
 * - Custom animations
 * 
 * @param string $variant - Spinner variant (border, grow, dots, pulse)
 * @param string $size - Size (sm, md, lg, xl)
 * @param string $color - Color variant (primary, secondary, success, warning, danger, info)
 * @param string $text - Text label
 * @param bool $centered - Center the spinner
 * @param string $class - Additional CSS classes
 * 
 * @example
 * <x-spinner 
 *     variant="border"
 *     size="lg"
 *     color="primary"
 *     text="Caricamento in corso..."
 *     centered
 * />
 */
--}}

@props([
    'variant' => 'border',
    'size' => 'md',
    'color' => 'primary',
    'text' => '',
    'centered' => false,
    'class' => ''
])

@php
    $baseClasses = 'spinner';
    
    // Variant classes
    $variantClasses = match($variant) {
        'grow' => 'spinner-grow',
        'dots' => 'spinner-dots',
        'pulse' => 'spinner-pulse',
        default => 'spinner-border'
    };
    
    // Size classes
    $sizeClasses = match($size) {
        'sm' => 'spinner-border-sm',
        'lg' => 'spinner-border-lg',
        'xl' => 'spinner-border-xl',
        default => ''
    };
    
    // Color classes
    $colorClasses = match($color) {
        'secondary' => 'text-secondary',
        'success' => 'text-success',
        'warning' => 'text-warning',
        'danger' => 'text-danger',
        'info' => 'text-info',
        'light' => 'text-light',
        'dark' => 'text-dark',
        default => 'text-primary'
    };
    
    // Container classes
    $containerClasses = '';
    if ($centered) {
        $containerClasses = 'd-flex justify-content-center align-items-center';
    }
    
    $classes = trim($baseClasses . ' ' . $variantClasses . ' ' . $sizeClasses . ' ' . $colorClasses . ' ' . $class);
@endphp

<div 
    {{ $attributes->merge(['class' => $containerClasses]) }}
    x-data="spinner()"
    x-init="init()"
    role="status"
    aria-label="{{ $text ?: 'Caricamento in corso' }}"
>
    @if($variant === 'border')
        <div class="{{ $classes }}" role="status">
            <span class="visually-hidden">{{ $text ?: 'Caricamento in corso' }}</span>
        </div>
    @elseif($variant === 'grow')
        <div class="{{ $classes }}" role="status">
            <span class="visually-hidden">{{ $text ?: 'Caricamento in corso' }}</span>
        </div>
    @elseif($variant === 'dots')
        <div class="spinner-dots {{ $colorClasses }}" role="status">
            <div class="spinner-dot"></div>
            <div class="spinner-dot"></div>
            <div class="spinner-dot"></div>
            <span class="visually-hidden">{{ $text ?: 'Caricamento in corso' }}</span>
        </div>
    @elseif($variant === 'pulse')
        <div class="spinner-pulse {{ $colorClasses }}" role="status">
            <div class="pulse-circle"></div>
            <div class="pulse-circle"></div>
            <div class="pulse-circle"></div>
            <span class="visually-hidden">{{ $text ?: 'Caricamento in corso' }}</span>
        </div>
    @endif
    
    @if(!empty($text))
        <span class="ms-2">{{ $text }}</span>
    @endif
    
    {{ $slot }}
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('spinner', () => ({
        isVisible: true,
        animationSpeed: 1,
        
        init() {
            // Listen for show/hide events
            this.$el.addEventListener('spinner:show', () => this.show());
            this.$el.addEventListener('spinner:hide', () => this.hide());
            this.$el.addEventListener('spinner:toggle', () => this.toggle());
            
            // Set initial visibility
            this.isVisible = !this.$el.hasAttribute('data-hidden');
        },
        
        show() {
            this.isVisible = true;
            this.$el.style.display = '';
            this.$el.setAttribute('aria-hidden', 'false');
            
            // Emit event
            this.$dispatch('spinner-shown');
        },
        
        hide() {
            this.isVisible = false;
            this.$el.style.display = 'none';
            this.$el.setAttribute('aria-hidden', 'true');
            
            // Emit event
            this.$dispatch('spinner-hidden');
        },
        
        toggle() {
            if (this.isVisible) {
                this.hide();
            } else {
                this.show();
            }
        },
        
        setSpeed(speed) {
            this.animationSpeed = speed;
            const spinner = this.$el.querySelector('.spinner-border, .spinner-grow, .spinner-dots, .spinner-pulse');
            if (spinner) {
                spinner.style.animationDuration = `${speed}s`;
            }
        }
    }));
    
    // Global spinner methods
    window.showSpinner = function(id) {
        const spinner = document.getElementById(id);
        if (spinner) {
            spinner.dispatchEvent(new CustomEvent('spinner:show'));
        }
    };
    
    window.hideSpinner = function(id) {
        const spinner = document.getElementById(id);
        if (spinner) {
            spinner.dispatchEvent(new CustomEvent('spinner:hide'));
        }
    };
    
    window.toggleSpinner = function(id) {
        const spinner = document.getElementById(id);
        if (spinner) {
            spinner.dispatchEvent(new CustomEvent('spinner:toggle'));
        }
    };
    
    // Loading overlay
    window.showLoadingOverlay = function(containerId = 'loading-overlay') {
        let overlay = document.getElementById(containerId);
        if (!overlay) {
            overlay = document.createElement('div');
            overlay.id = containerId;
            overlay.className = 'loading-overlay position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center';
            overlay.style.backgroundColor = 'rgba(255, 255, 255, 0.8)';
            overlay.style.zIndex = '9999';
            overlay.innerHTML = `
                <div class="text-center">
                    <div class="spinner-border text-primary mb-3" role="status">
                        <span class="visually-hidden">Caricamento in corso</span>
                    </div>
                    <div class="text-muted">Caricamento in corso...</div>
                </div>
            `;
            document.body.appendChild(overlay);
        }
        overlay.style.display = 'flex';
    };
    
    window.hideLoadingOverlay = function(containerId = 'loading-overlay') {
        const overlay = document.getElementById(containerId);
        if (overlay) {
            overlay.style.display = 'none';
        }
    };
});
</script>
@endpush

@push('styles')
<style>
/* Base spinner styles */
.spinner-border {
    display: inline-block;
    width: 2rem;
    height: 2rem;
    vertical-align: -0.125em;
    border: 0.25em solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    animation: spinner-border 0.75s linear infinite;
}

.spinner-border-sm {
    width: 1rem;
    height: 1rem;
    border-width: 0.125em;
}

.spinner-border-lg {
    width: 3rem;
    height: 3rem;
    border-width: 0.375em;
}

.spinner-border-xl {
    width: 4rem;
    height: 4rem;
    border-width: 0.5em;
}

.spinner-grow {
    display: inline-block;
    width: 2rem;
    height: 2rem;
    vertical-align: -0.125em;
    background-color: currentColor;
    border-radius: 50%;
    opacity: 0;
    animation: spinner-grow 0.75s linear infinite;
}

.spinner-grow-sm {
    width: 1rem;
    height: 1rem;
}

.spinner-grow-lg {
    width: 3rem;
    height: 3rem;
}

.spinner-grow-xl {
    width: 4rem;
    height: 4rem;
}

/* Dots spinner */
.spinner-dots {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.spinner-dot {
    width: 0.5rem;
    height: 0.5rem;
    background-color: currentColor;
    border-radius: 50%;
    animation: spinner-dots 1.4s ease-in-out infinite both;
}

.spinner-dot:nth-child(1) {
    animation-delay: -0.32s;
}

.spinner-dot:nth-child(2) {
    animation-delay: -0.16s;
}

.spinner-dot:nth-child(3) {
    animation-delay: 0s;
}

/* Pulse spinner */
.spinner-pulse {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
}

.pulse-circle {
    width: 0.5rem;
    height: 0.5rem;
    background-color: currentColor;
    border-radius: 50%;
    animation: spinner-pulse 1.4s ease-in-out infinite both;
}

.pulse-circle:nth-child(1) {
    animation-delay: -0.32s;
}

.pulse-circle:nth-child(2) {
    animation-delay: -0.16s;
}

.pulse-circle:nth-child(3) {
    animation-delay: 0s;
}

/* Animations */
@keyframes spinner-border {
    to {
        transform: rotate(360deg);
    }
}

@keyframes spinner-grow {
    0% {
        transform: scale(0);
    }
    50% {
        opacity: 1;
        transform: none;
    }
}

@keyframes spinner-dots {
    0%, 80%, 100% {
        transform: scale(0);
    }
    40% {
        transform: scale(1);
    }
}

@keyframes spinner-pulse {
    0%, 80%, 100% {
        opacity: 0.3;
        transform: scale(0.8);
    }
    40% {
        opacity: 1;
        transform: scale(1);
    }
}

/* Loading overlay */
.loading-overlay {
    backdrop-filter: blur(2px);
    -webkit-backdrop-filter: blur(2px);
}

/* Color variants */
.text-primary {
    color: #0d6efd !important;
}

.text-secondary {
    color: #6c757d !important;
}

.text-success {
    color: #198754 !important;
}

.text-warning {
    color: #ffc107 !important;
}

.text-danger {
    color: #dc3545 !important;
}

.text-info {
    color: #0dcaf0 !important;
}

.text-light {
    color: #f8f9fa !important;
}

.text-dark {
    color: #212529 !important;
}

/* Responsive adjustments */
@media (max-width: 576px) {
    .spinner-border-lg,
    .spinner-grow-lg {
        width: 2rem;
        height: 2rem;
    }
    
    .spinner-border-xl,
    .spinner-grow-xl {
        width: 3rem;
        height: 3rem;
    }
}

/* Accessibility */
.visually-hidden {
    position: absolute !important;
    width: 1px !important;
    height: 1px !important;
    padding: 0 !important;
    margin: -1px !important;
    overflow: hidden !important;
    clip: rect(0, 0, 0, 0) !important;
    white-space: nowrap !important;
    border: 0 !important;
}
</style>
@endpush
