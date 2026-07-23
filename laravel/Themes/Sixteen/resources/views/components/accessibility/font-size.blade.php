{{--
/**
 * Font Size Control Component
 * 
 * Componente per il controllo della dimensione del font.
 * Conforme alle linee guida WCAG 2.1 AA e AGID.
 * 
 * @package Themes\Sixteen
 * @author Marco Xot <marco.sottana@gmail.com>
 * @version 1.0.0
 */
--}}

@props([
    'label' => 'Dimensione testo',
    'sizes' => ['small', 'normal', 'large', 'xl'],
    'currentSize' => 'normal',
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
    
    $sizeLabels = [
        'small' => 'Piccolo',
        'normal' => 'Normale',
        'large' => 'Grande',
        'xl' => 'Molto grande'
    ];
    
    $sizeValues = [
        'small' => '0.875rem',
        'normal' => '1rem',
        'large' => '1.125rem',
        'xl' => '1.25rem'
    ];
@endphp

<div class="font-size-controls" data-font-size-controls>
    <label for="font-size-select" class="sr-only">{{ $label }}</label>
    
    <div class="flex items-center gap-2">
        <button 
            type="button"
            class="font-size-decrease inline-flex items-center justify-center w-8 h-8 rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            aria-label="Diminuisci dimensione testo"
            data-font-size-decrease
            title="Diminuisci la dimensione del testo"
        >
            <x-filament::icon icon="heroicon-o-minus" class="h-4 w-4" />
f7ac8eda (.)
        </button>
        
        <select 
            id="font-size-select"
            class="font-size-select {{ $sizeClass }} {{ $variantClass }} rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            aria-label="{{ $label }}"
            data-font-size-select
        >
            @foreach($sizes as $sizeOption)
                <option 
                    value="{{ $sizeOption }}" 
                    {{ $sizeOption === $currentSize ? 'selected' : '' }}
                    data-size="{{ $sizeValues[$sizeOption] }}"
                >
                    {{ $sizeLabels[$sizeOption] ?? ucfirst($sizeOption) }}
                </option>
            @endforeach
        </select>
        
        <button 
            type="button"
            class="font-size-increase inline-flex items-center justify-center w-8 h-8 rounded-md border border-gray-300 bg-white text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
            aria-label="Aumenta dimensione testo"
            data-font-size-increase
            title="Aumenta la dimensione del testo"
        >
            <x-filament::icon icon="heroicon-o-plus" class="h-4 w-4" />
f7ac8eda (.)
        </button>
    </div>
    
    <span class="sr-only" aria-live="polite" id="font-size-status">
        Dimensione testo: {{ $sizeLabels[$currentSize] ?? ucfirst($currentSize) }}
    </span>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fontSizeSelect = document.querySelector('[data-font-size-select]');
    const decreaseBtn = document.querySelector('[data-font-size-decrease]');
    const increaseBtn = document.querySelector('[data-font-size-increase]');
    const statusElement = document.getElementById('font-size-status');
    const body = document.body;
    
    // Controlla se c'è una dimensione salvata
    const savedFontSize = localStorage.getItem('font-size') || 'normal';
    const sizeValues = {
        'small': '0.875rem',
        'normal': '1rem',
        'large': '1.125rem',
        'xl': '1.25rem'
    };
    
    const sizeLabels = {
        'small': 'Piccolo',
        'normal': 'Normale',
        'large': 'Grande',
        'xl': 'Molto grande'
    };
    
    // Applica la dimensione salvata
    if (savedFontSize && sizeValues[savedFontSize]) {
        body.style.fontSize = sizeValues[savedFontSize];
        fontSizeSelect.value = savedFontSize;
        statusElement.textContent = `Dimensione testo: ${sizeLabels[savedFontSize]}`;
    }
    
    // Gestione cambio dimensione tramite select
    fontSizeSelect.addEventListener('change', function() {
        const selectedSize = this.value;
        const fontSize = sizeValues[selectedSize];
        
        if (fontSize) {
            body.style.fontSize = fontSize;
            localStorage.setItem('font-size', selectedSize);
            statusElement.textContent = `Dimensione testo: ${sizeLabels[selectedSize]}`;
        }
    });
    
    // Gestione pulsanti + e -
    decreaseBtn.addEventListener('click', function() {
        const currentSize = fontSizeSelect.value;
        const sizes = ['small', 'normal', 'large', 'xl'];
        const currentIndex = sizes.indexOf(currentSize);
        
        if (currentIndex > 0) {
            const newSize = sizes[currentIndex - 1];
            fontSizeSelect.value = newSize;
            fontSizeSelect.dispatchEvent(new Event('change'));
        }
    });
    
    increaseBtn.addEventListener('click', function() {
        const currentSize = fontSizeSelect.value;
        const sizes = ['small', 'normal', 'large', 'xl'];
        const currentIndex = sizes.indexOf(currentSize);
        
        if (currentIndex < sizes.length - 1) {
            const newSize = sizes[currentIndex + 1];
            fontSizeSelect.value = newSize;
            fontSizeSelect.dispatchEvent(new Event('change'));
        }
    });
    
    // Supporto per navigazione da tastiera
    [decreaseBtn, increaseBtn].forEach(btn => {
        btn.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                this.click();
            }
        });
    });
});
</script>
@endpush

@push('styles')
<style>
/* Stili per il controllo della dimensione del font */
.font-size-controls {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.font-size-controls button {
    transition: all 0.2s ease-in-out;
}

.font-size-controls button:hover {
    transform: scale(1.05);
}

.font-size-controls button:active {
    transform: scale(0.95);
}

.font-size-controls select {
    min-width: 120px;
}

/* Stili per diverse dimensioni del font */
.font-size-small {
    font-size: 0.875rem !important;
}

.font-size-normal {
    font-size: 1rem !important;
}

.font-size-large {
    font-size: 1.125rem !important;
}

.font-size-xl {
    font-size: 1.25rem !important;
}

/* Responsive font size controls */
@media (max-width: 640px) {
    .font-size-controls {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .font-size-controls select {
        min-width: 100px;
    }
}
</style>
@endpush