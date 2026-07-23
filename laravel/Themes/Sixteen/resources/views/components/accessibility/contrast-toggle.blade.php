{{--
/**
 * Contrast Toggle Component
 * 
 * Componente per il controllo del contrasto dell'interfaccia.
 * Conforme alle linee guida WCAG 2.1 AA e AGID.
 * 
 * @package Themes\Sixteen
 * @author Marco Xot <marco.sottana@gmail.com>
 * @version 1.0.0
 */
--}}

@props([
    'label' => 'Alto contrasto',
    'icon' => 'heroicon-o-eye',
    'size' => 'sm',
    'variant' => 'outline'
])

@php
    $sizeClasses = [
        'xs' => 'px-2 py-1 text-xs',
        'sm' => 'px-3 py-1.5 text-sm',
        'md' => 'px-4 py-2 text-base',
        'lg' => 'px-6 py-3 text-lg'
    ];
    
    $variantClasses = [
        'outline' => 'border border-gray-300 bg-white text-gray-700 hover:bg-gray-50',
        'solid' => 'bg-gray-800 text-white hover:bg-gray-900',
        'ghost' => 'text-gray-700 hover:bg-gray-100'
    ];
    
    $sizeClass = $sizeClasses[$size] ?? $sizeClasses['sm'];
    $variantClass = $variantClasses[$variant] ?? $variantClasses['outline'];
@endphp

<button 
    type="button"
    id="contrast-toggle"
    class="inline-flex items-center gap-2 rounded-md font-medium transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 {{ $sizeClass }} {{ $variantClass }}"
    aria-label="{{ $label }}"
    aria-pressed="false"
    data-contrast-toggle
    title="Attiva/disattiva l'alto contrasto per migliorare la leggibilità"
>
    @if($icon)
        <x-dynamic-component :component="$icon" class="h-4 w-4" />
    @endif
    
    <span class="contrast-toggle-text">{{ $label }}</span>
    
    <span class="sr-only" aria-live="polite" id="contrast-status">
        Contrasto normale attivo
    </span>
</button>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const contrastToggle = document.getElementById('contrast-toggle');
    const contrastStatus = document.getElementById('contrast-status');
    const body = document.body;
    
    // Controlla se l'alto contrasto è già attivo
    const isHighContrast = localStorage.getItem('high-contrast') === 'true';
    
    if (isHighContrast) {
        body.classList.add('high-contrast');
        contrastToggle.setAttribute('aria-pressed', 'true');
        contrastToggle.querySelector('.contrast-toggle-text').textContent = 'Contrasto normale';
        contrastStatus.textContent = 'Alto contrasto attivo';
    }
    
    contrastToggle.addEventListener('click', function() {
        const isCurrentlyHighContrast = body.classList.contains('high-contrast');
        
        if (isCurrentlyHighContrast) {
            // Disattiva alto contrasto
            body.classList.remove('high-contrast');
            this.setAttribute('aria-pressed', 'false');
            this.querySelector('.contrast-toggle-text').textContent = '{{ $label }}';
            contrastStatus.textContent = 'Contrasto normale attivo';
            localStorage.setItem('high-contrast', 'false');
        } else {
            // Attiva alto contrasto
            body.classList.add('high-contrast');
            this.setAttribute('aria-pressed', 'true');
            this.querySelector('.contrast-toggle-text').textContent = 'Contrasto normale';
            contrastStatus.textContent = 'Alto contrasto attivo';
            localStorage.setItem('high-contrast', 'true');
        }
    });
    
    // Supporto per navigazione da tastiera
    contrastToggle.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.key === ' ') {
            e.preventDefault();
            this.click();
        }
    });
});
</script>
@endpush

@push('styles')
<style>
/* Stili per alto contrasto */
.high-contrast {
    --bg-primary: #000000 !important;
    --bg-secondary: #1a1a1a !important;
    --text-primary: #ffffff !important;
    --text-secondary: #cccccc !important;
    --border-primary: #ffffff !important;
    --border-secondary: #666666 !important;
    --accent-primary: #ffff00 !important;
    --accent-secondary: #00ffff !important;
}

.high-contrast * {
    background-color: var(--bg-primary) !important;
    color: var(--text-primary) !important;
    border-color: var(--border-primary) !important;
}

.high-contrast a {
    color: var(--accent-primary) !important;
    text-decoration: underline !important;
}

.high-contrast a:hover,
.high-contrast a:focus {
    background-color: var(--accent-primary) !important;
    color: var(--bg-primary) !important;
}

.high-contrast button {
    background-color: var(--bg-secondary) !important;
    color: var(--text-primary) !important;
    border: 2px solid var(--border-primary) !important;
}

.high-contrast button:hover,
.high-contrast button:focus {
    background-color: var(--accent-primary) !important;
    color: var(--bg-primary) !important;
}

.high-contrast input,
.high-contrast textarea,
.high-contrast select {
    background-color: var(--bg-primary) !important;
    color: var(--text-primary) !important;
    border: 2px solid var(--border-primary) !important;
}

.high-contrast .card,
.high-contrast .panel,
.high-contrast .modal {
    background-color: var(--bg-secondary) !important;
    border: 2px solid var(--border-primary) !important;
}

/* Focus visibile migliorato per alto contrasto */
.high-contrast *:focus {
    outline: 3px solid var(--accent-secondary) !important;
    outline-offset: 2px !important;
}

/* Nascondi elementi decorativi in alto contrasto */
.high-contrast .decoration,
.high-contrast .ornament {
    display: none !important;
}
</style>
@endpush