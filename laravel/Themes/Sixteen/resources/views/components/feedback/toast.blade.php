{{--
/**
 * Toast Component
 * 
 * Bootstrap Italia compliant toast notification component with support for:
 * - Multiple variants (success, error, warning, info)
 * - Auto-dismiss with configurable delay
 * - Manual dismiss controls
 * - Progress bar
 * - Icon support
 * - Action buttons
 * - Accessibility features
 * 
 * @param string $id - Unique identifier
 * @param string $title - Toast title
 * @param string $message - Toast message
 * @param string $variant - Toast variant (success, error, warning, info)
 * @param bool $autohide - Auto-hide after delay
 * @param int $delay - Auto-hide delay in milliseconds
 * @param bool $showProgress - Show progress bar
 * @param string $icon - Icon name
 * @param array $actions - Action buttons
 * @param string $class - Additional CSS classes
 * 
 * @example
 * <x-toast 
 *     id="success-toast"
 *     title="Operazione completata"
 *     message="I dati sono stati salvati con successo"
 *     variant="success"
 *     autohide
 *     :delay="5000"
 *     show-progress
 * />
 */
--}}

@props([
    'id' => 'toast-' . Str::random(8),
    'title' => '',
    'message' => '',
    'variant' => 'info',
    'autohide' => true,
    'delay' => 5000,
    'showProgress' => false,
    'icon' => null,
    'actions' => [],
    'class' => ''
])

@php
    $baseClasses = 'toast';
    
    // Variant classes
    $variantClasses = match($variant) {
        'success' => 'toast-success',
        'error' => 'toast-error',
        'warning' => 'toast-warning',
        'info' => 'toast-info',
        default => 'toast-info'
    };
    
    // Icon mapping
    $iconName = $icon ?? match($variant) {
        'success' => 'check-circle',
        'error' => 'x-circle',
        'warning' => 'exclamation-triangle',
        'info' => 'information-circle',
        default => 'information-circle'
    };
    
    $classes = trim($baseClasses . ' ' . $variantClasses . ' ' . $class);
@endphp

<div 
    {{ $attributes->merge(['class' => $classes]) }}
    x-data="toast('{{ $id }}')"
    x-init="init()"
    x-show="isVisible"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0 transform translate-x-full"
    x-transition:enter-end="opacity-100 transform translate-x-0"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100 transform translate-x-0"
    x-transition:leave-end="opacity-0 transform translate-x-full"
    role="alert"
    aria-live="polite"
    aria-atomic="true"
    data-bs-autohide="{{ $autohide ? 'true' : 'false' }}"
    data-bs-delay="{{ $delay }}"
>
    <div class="toast-header">
        @if($iconName)
            <div class="toast-icon me-2">
                <x-filament::icon icon="heroicon-o-{{" $iconName }} class="w-5 h-5" />
            </div>
        @endif
        
        @if(!empty($title))
            <strong class="me-auto">{{ $title }}</strong>
        @endif
        
        @if(!empty($message))
            <small class="text-muted">{{ now()->format('H:i') }}</small>
        @endif
        
        <button 
            type="button" 
            class="btn-close ms-2" 
            aria-label="Chiudi"
            x-on:click="hide()"
        >
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    
    @if(!empty($message))
        <div class="toast-body">
            {{ $message }}
            
            @if(!empty($actions))
                <div class="toast-actions mt-2">
                    @foreach($actions as $action)
                        <button 
                            type="button" 
                            class="btn btn-sm {{ $action['variant'] ?? 'btn-outline-primary' }} me-2"
                            x-on:click="handleAction('{{ $action['action'] ?? '' }}')"
                        >
                            @if(!empty($action['icon']))
                                <x-filament::icon icon="heroicon-o-{{" $action['icon'] }} class="w-4 h-4 me-1" />
                            @endif
                            {{ $action['text'] ?? 'Azione' }}
                        </button>
                    @endforeach
                </div>
            @endif
        </div>
    @endif
    
    @if($showProgress && $autohide)
        <div class="toast-progress">
            <div 
                class="toast-progress-bar"
                x-ref="progressBar"
                x-bind:style="`width: ${progress}%`"
            ></div>
        </div>
    @endif
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('toast', (id) => ({
        isVisible: false,
        progress: 100,
        progressInterval: null,
        hideTimeout: null,
        
        init() {
            // Listen for show/hide events
            this.$el.addEventListener('toast:show', () => this.show());
            this.$el.addEventListener('toast:hide', () => this.hide());
            
            // Auto-show if not hidden
            if (!this.$el.hasAttribute('data-hidden')) {
                this.show();
            }
        },
        
        show() {
            this.isVisible = true;
            this.progress = 100;
            
            // Update aria-live
            this.$el.setAttribute('aria-live', 'assertive');
            
            // Start auto-hide if enabled
            const autohide = this.$el.getAttribute('data-bs-autohide') === 'true';
            if (autohide) {
                const delay = parseInt(this.$el.getAttribute('data-bs-delay')) || 5000;
                this.startAutoHide(delay);
            }
            
            // Emit event
            this.$dispatch('toast-shown', { id: id });
        },
        
        hide() {
            this.isVisible = false;
            this.progress = 0;
            
            // Clear timers
            this.clearTimers();
            
            // Update aria-live
            this.$el.setAttribute('aria-live', 'polite');
            
            // Emit event
            this.$dispatch('toast-hidden', { id: id });
        },
        
        startAutoHide(delay) {
            // Set hide timeout
            this.hideTimeout = setTimeout(() => {
                this.hide();
            }, delay);
            
            // Start progress bar if enabled
            const showProgress = this.$el.querySelector('.toast-progress-bar');
            if (showProgress) {
                const interval = 50; // Update every 50ms
                const totalSteps = delay / interval;
                const stepSize = 100 / totalSteps;
                
                this.progressInterval = setInterval(() => {
                    this.progress -= stepSize;
                    if (this.progress <= 0) {
                        this.progress = 0;
                        this.clearTimers();
                    }
                }, interval);
            }
        },
        
        clearTimers() {
            if (this.hideTimeout) {
                clearTimeout(this.hideTimeout);
                this.hideTimeout = null;
            }
            
            if (this.progressInterval) {
                clearInterval(this.progressInterval);
                this.progressInterval = null;
            }
        },
        
        handleAction(action) {
            // Emit action event
            this.$dispatch('toast-action', { 
                id: id, 
                action: action 
            });
            
            // Hide toast after action
            this.hide();
        }
    }));
    
    // Global toast methods
    window.showToast = function(id, options = {}) {
        const toast = document.getElementById(id);
        if (toast) {
            // Update toast content if provided
            if (options.title) {
                const titleEl = toast.querySelector('.toast-header strong');
                if (titleEl) titleEl.textContent = options.title;
            }
            
            if (options.message) {
                const messageEl = toast.querySelector('.toast-body');
                if (messageEl) messageEl.textContent = options.message;
            }
            
            // Show toast
            toast.dispatchEvent(new CustomEvent('toast:show'));
        }
    };
    
    window.hideToast = function(id) {
        const toast = document.getElementById(id);
        if (toast) {
            toast.dispatchEvent(new CustomEvent('toast:hide'));
        }
    };
    
    // Toast container management
    window.createToastContainer = function(containerId = 'toast-container') {
        let container = document.getElementById(containerId);
        if (!container) {
            container = document.createElement('div');
            container.id = containerId;
            container.className = 'toast-container position-fixed top-0 end-0 p-3';
            container.style.zIndex = '1055';
            document.body.appendChild(container);
        }
        return container;
    };
    
    // Quick toast methods
    window.toastSuccess = function(message, title = 'Successo') {
        const container = window.createToastContainer();
        const toastId = 'toast-' + Date.now();
        
        const toastHtml = `
            <div id="${toastId}" class="toast toast-success" role="alert" aria-live="assertive" aria-atomic="true">
                <div class="toast-header">
                    <div class="toast-icon me-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                    <strong class="me-auto">${title}</strong>
                    <small class="text-muted">${new Date().toLocaleTimeString()}</small>
                    <button type="button" class="btn-close ms-2" aria-label="Chiudi">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="toast-body">${message}</div>
            </div>
        `;
        
        container.insertAdjacentHTML('beforeend', toastHtml);
        window.showToast(toastId);
        
        // Auto-remove after hide
        setTimeout(() => {
            const toast = document.getElementById(toastId);
            if (toast) {
                toast.remove();
            }
        }, 6000);
    };
});
</script>
@endpush

@push('styles')
<style>
.toast-container {
    z-index: 1055;
}

.toast {
    min-width: 300px;
    max-width: 400px;
    background-color: white;
    border: 1px solid rgba(0, 0, 0, 0.1);
    border-radius: 0.375rem;
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.toast-header {
    display: flex;
    align-items: center;
    padding: 0.75rem 1rem;
    background-color: rgba(0, 0, 0, 0.03);
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    border-top-left-radius: 0.375rem;
    border-top-right-radius: 0.375rem;
}

.toast-body {
    padding: 1rem;
}

.toast-progress {
    height: 4px;
    background-color: rgba(0, 0, 0, 0.1);
    border-bottom-left-radius: 0.375rem;
    border-bottom-right-radius: 0.375rem;
    overflow: hidden;
}

.toast-progress-bar {
    height: 100%;
    background-color: #007bff;
    transition: width 0.1s linear;
}

/* Variant styles */
.toast-success .toast-icon svg {
    color: #28a745;
}

.toast-error .toast-icon svg {
    color: #dc3545;
}

.toast-warning .toast-icon svg {
    color: #ffc107;
}

.toast-info .toast-icon svg {
    color: #17a2b8;
}

.toast-success .toast-progress-bar {
    background-color: #28a745;
}

.toast-error .toast-progress-bar {
    background-color: #dc3545;
}

.toast-warning .toast-progress-bar {
    background-color: #ffc107;
}

.toast-info .toast-progress-bar {
    background-color: #17a2b8;
}

/* Responsive */
@media (max-width: 576px) {
    .toast-container {
        left: 1rem;
        right: 1rem;
    }
    
    .toast {
        min-width: auto;
        max-width: none;
    }
}
</style>
@endpush
