{{--
/**
 * Forward/Back Navigation Component - Bootstrap Italia Compliant
 * 
 * Previous/Next navigation for sequential content like tutorials, articles, or steps
 * Provides clear navigation between related items in a sequence
 * 
 * @param string $id Unique ID for the navigation
 * @param array $previous Previous item configuration
 * @param array $next Next item configuration  
 * @param string $orientation Orientation: 'horizontal' or 'vertical'
 * @param string $variant Style variant: 'default', 'minimal', 'prominent'
 * @param bool $showLabels Whether to show text labels
 * @param bool $showIcons Whether to show navigation icons
 */
--}}

@props([
    'id' => 'forward-back-' . uniqid(),
    'previous' => null,
    'next' => null,
    'orientation' => 'horizontal',
    'variant' => 'default',
    'showLabels' => true,
    'showIcons' => true,
])

@php
    $orientationClass = $orientation === 'vertical' ? 'flex-col space-y-4' : 'flex-row space-x-4';
    
    $variantClasses = [
        'default' => 'p-4 border rounded-lg',
        'minimal' => 'p-2',
        'prominent' => 'p-6 bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200 rounded-xl'
    ];
    
    $buttonClasses = [
        'default' => 'px-4 py-2 border rounded-md transition-colors',
        'minimal' => 'px-3 py-1 text-sm',
        'prominent' => 'px-6 py-3 border-2 font-semibold rounded-lg transition-all hover:shadow-md'
    ];
@endphp

<nav 
    id="{{ $id }}"
    class="forward-back-navigation {{ $variantClasses[$variant] }}"
    aria-label="Navigazione avanti e indietro"
>
    <div class="flex {{ $orientationClass }} items-center justify-between">
        {{-- Previous button --}}
        @if($previous)
            <a
                href="{{ $previous['url'] }}"
                class="previous-btn flex items-center space-x-2 {{ $buttonClasses[$variant] }} border-gray-300 text-gray-700 hover:border-blue-300 hover:text-blue-700 hover:bg-blue-50"
                aria-label="Vai a: {{ $previous['label'] }}"
            >
                @if($showIcons)
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                @endif
                
                <div class="flex flex-col">
                    @if($showLabels)
                        <span class="text-sm text-gray-500">Precedente</span>
                        <span class="font-medium">{{ $previous['label'] }}</span>
                    @else
                        <span class="sr-only">Vai a: {{ $previous['label'] }}</span>
                    @endif
                </div>
            </a>
        @else
            <div class="flex items-center space-x-2 {{ $buttonClasses[$variant] }} border-gray-200 text-gray-400 opacity-50 cursor-not-allowed">
                @if($showIcons)
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                @endif
                <span class="text-sm">Inizio</span>
            </div>
        @endif

        {{-- Next button --}}
        @if($next)
            <a
                href="{{ $next['url'] }}"
                class="next-btn flex items-center space-x-2 {{ $buttonClasses[$variant] }} border-blue-300 text-blue-700 hover:border-blue-500 hover:text-blue-900 hover:bg-blue-100 justify-end"
                aria-label="Vai a: {{ $next['label'] }}"
            >
                <div class="flex flex-col items-end">
                    @if($showLabels)
                        <span class="text-sm text-dc-600">Avanti</span>
                        <span class="font-medium">{{ $next['label'] }}</span>
                    @else
                        <span class="sr-only">Vai a: {{ $next['label'] }}</span>
                    @endif
                </div>
                
                @if($showIcons)
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                @endif
            </a>
        @else
            <div class="flex items-center space-x-2 {{ $buttonClasses[$variant] }} border-gray-200 text-gray-400 opacity-50 cursor-not-allowed">
                <span class="text-sm">Fine</span>
                @if($showIcons)
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                @endif
            </div>
        @endif
    </div>
</nav>

{{-- Keyboard navigation support --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const navigation = document.getElementById('{{ $id }}');
    
    // Add keyboard navigation
    document.addEventListener('keydown', function(event) {
        if (event.target.closest('input, textarea, select')) return;
        
        const previousBtn = navigation.querySelector('.previous-btn');
        const nextBtn = navigation.querySelector('.next-btn');
        
        if (event.key === 'ArrowLeft' && previousBtn && !previousBtn.disabled) {
            event.preventDefault();
            previousBtn.click();
        } else if (event.key === 'ArrowRight' && nextBtn && !nextBtn.disabled) {
            event.preventDefault();
            nextBtn.click();
        }
    });
    
    // Add focus management for better accessibility
    const focusableElements = navigation.querySelectorAll('a, button');
    focusableElements.forEach((element, index) => {
        element.addEventListener('keydown', function(event) {
            if (event.key === 'Tab') {
                event.preventDefault();
                const nextIndex = (index + 1) % focusableElements.length;
                focusableElements[nextIndex].focus();
            }
        });
    });
});
</script>