{{--
/**
 * Offcanvas Component
 * 
 * Bootstrap Italia compliant offcanvas component with support for:
 * - Multiple positions (start, end, top, bottom)
 * - Backdrop and keyboard controls
 * - Custom sizing
 * - Animation effects
 * - Accessibility features
 * - Focus management
 * 
 * @param string $id - Unique identifier
 * @param string $title - Offcanvas title
 * @param string $position - Position (start, end, top, bottom)
 * @param bool $backdrop - Show backdrop
 * @param bool $keyboard - Enable keyboard controls
 * @param bool $scrollable - Enable scrolling
 * @param string $size - Size (sm, md, lg, xl)
 * @param string $class - Additional CSS classes
 * 
 * @example
 * <x-offcanvas 
 *     id="sidebar"
 *     title="Menu laterale"
 *     position="start"
 *     backdrop
 *     keyboard
 * />
 */
--}}

@props([
    'id' => 'offcanvas-' . Str::random(8),
    'title' => '',
    'position' => 'start',
    'backdrop' => true,
    'keyboard' => true,
    'scrollable' => false,
    'size' => 'md',
    'class' => ''
])

@php
    $baseClasses = 'offcanvas';
    
    // Position classes
    $positionClasses = match($position) {
        'end' => 'offcanvas-end',
        'top' => 'offcanvas-top',
        'bottom' => 'offcanvas-bottom',
        default => 'offcanvas-start'
    };
    
    // Size classes
    $sizeClasses = match($size) {
        'sm' => 'offcanvas-sm',
        'lg' => 'offcanvas-lg',
        'xl' => 'offcanvas-xl',
        default => ''
    };
    
    $classes = trim($baseClasses . ' ' . $positionClasses . ' ' . $sizeClasses . ' ' . $class);
@endphp

<div 
    {{ $attributes->merge(['class' => $classes]) }}
    x-data="offcanvas('{{ $id }}')"
    x-init="init()"
    x-show="isOpen"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform -translate-x-full"
    x-transition:enter-end="opacity-100 transform translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-x-0"
    x-transition:leave-end="opacity-0 transform -translate-x-full"
    tabindex="-1"
    role="dialog"
    aria-labelledby="{{ $id }}-title"
    aria-hidden="true"
    data-bs-backdrop="{{ $backdrop ? 'true' : 'false' }}"
    data-bs-keyboard="{{ $keyboard ? 'true' : 'false' }}"
    data-bs-scroll="{{ $scrollable ? 'true' : 'false' }}"
>
    {{-- Header --}}
    <div class="offcanvas-header">
        @if(!empty($title))
            <h5 class="offcanvas-title" id="{{ $id }}-title">
                {{ $title }}
            </h5>
        @endif
        
        <button 
            type="button" 
            class="btn-close" 
            aria-label="Chiudi"
            x-on:click="close()"
        >
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    
    {{-- Body --}}
    <div class="offcanvas-body {{ $scrollable ? 'overflow-auto' : '' }}">
        {{ $slot }}
    </div>
</div>

{{-- Backdrop --}}
@if($backdrop)
    <div 
        x-show="isOpen && showBackdrop"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="offcanvas-backdrop"
        x-on:click="close()"
        aria-hidden="true"
    ></div>
@endif

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('offcanvas', (id) => ({
        isOpen: false,
        showBackdrop: false,
        focusableElements: [],
        firstFocusableElement: null,
        lastFocusableElement: null,
        
        init() {
            // Get focusable elements
            this.updateFocusableElements();
            
            // Listen for open/close events
            this.$el.addEventListener('offcanvas:open', () => this.open());
            this.$el.addEventListener('offcanvas:close', () => this.close());
            
            // Global event listeners
            document.addEventListener('keydown', (e) => this.handleKeydown(e));
            
            // Handle window resize
            window.addEventListener('resize', () => {
                if (this.isOpen && window.innerWidth < 768) {
                    this.close();
                }
            });
        },
        
        open() {
            this.isOpen = true;
            this.showBackdrop = true;
            
            // Update aria-hidden
            this.$el.setAttribute('aria-hidden', 'false');
            
            // Prevent body scroll
            document.body.classList.add('offcanvas-open');
            
            // Focus management
            this.$nextTick(() => {
                this.updateFocusableElements();
                this.trapFocus();
            });
            
            // Emit event
            this.$dispatch('offcanvas-opened', { id: id });
        },
        
        close() {
            this.isOpen = false;
            this.showBackdrop = false;
            
            // Update aria-hidden
            this.$el.setAttribute('aria-hidden', 'true');
            
            // Restore body scroll
            document.body.classList.remove('offcanvas-open');
            
            // Emit event
            this.$dispatch('offcanvas-closed', { id: id });
        },
        
        toggle() {
            if (this.isOpen) {
                this.close();
            } else {
                this.open();
            }
        },
        
        updateFocusableElements() {
            const focusableSelectors = [
                'button:not([disabled])',
                'input:not([disabled])',
                'select:not([disabled])',
                'textarea:not([disabled])',
                'a[href]',
                '[tabindex]:not([tabindex="-1"])'
            ].join(', ');
            
            this.focusableElements = Array.from(
                this.$el.querySelectorAll(focusableSelectors)
            );
            
            this.firstFocusableElement = this.focusableElements[0];
            this.lastFocusableElement = this.focusableElements[this.focusableElements.length - 1];
        },
        
        trapFocus(e) {
            if (!this.isOpen) return;
            
            if (e && e.key === 'Tab') {
                if (e.shiftKey) {
                    if (document.activeElement === this.firstFocusableElement) {
                        e.preventDefault();
                        this.lastFocusableElement?.focus();
                    }
                } else {
                    if (document.activeElement === this.lastFocusableElement) {
                        e.preventDefault();
                        this.firstFocusableElement?.focus();
                    }
                }
            }
        },
        
        handleKeydown(e) {
            if (!this.isOpen) return;
            
            // Close on Escape key
            if (e.key === 'Escape') {
                this.close();
                return;
            }
            
            // Handle Tab key for focus trapping
            if (e.key === 'Tab') {
                this.trapFocus(e);
            }
        }
    }));
    
    // Global methods
    window.openOffcanvas = function(id) {
        const offcanvas = document.getElementById(id);
        if (offcanvas) {
            offcanvas.dispatchEvent(new CustomEvent('offcanvas:open'));
        }
    };
    
    window.closeOffcanvas = function(id) {
        const offcanvas = document.getElementById(id);
        if (offcanvas) {
            offcanvas.dispatchEvent(new CustomEvent('offcanvas:close'));
        }
    };
    
    window.toggleOffcanvas = function(id) {
        const offcanvas = document.getElementById(id);
        if (offcanvas) {
            const isOpen = offcanvas.getAttribute('aria-hidden') === 'false';
            if (isOpen) {
                window.closeOffcanvas(id);
            } else {
                window.openOffcanvas(id);
            }
        }
    };
});
</script>
@endpush

@push('styles')
<style>
.offcanvas-backdrop {
    position: fixed;
    top: 0;
    left: 0;
    width: 100vw;
    height: 100vh;
    background-color: rgba(0, 0, 0, 0.5);
    z-index: 1040;
}

.offcanvas-open {
    overflow: hidden;
}

.offcanvas {
    z-index: 1050;
}

.offcanvas-start {
    transform: translateX(-100%);
}

.offcanvas-end {
    transform: translateX(100%);
}

.offcanvas-top {
    transform: translateY(-100%);
}

.offcanvas-bottom {
    transform: translateY(100%);
}

/* Responsive behavior */
@media (max-width: 767.98px) {
    .offcanvas-sm {
        width: 100% !important;
    }
}

@media (max-width: 991.98px) {
    .offcanvas-md {
        width: 100% !important;
    }
}

@media (max-width: 1199.98px) {
    .offcanvas-lg {
        width: 100% !important;
    }
}
</style>
@endpush
