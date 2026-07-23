{{--
/**
 * Thumbnav Component - Bootstrap Italia Compliant
 * 
 * Horizontal thumbnail navigation for galleries, carousels, and image sets
 * Displays small preview thumbnails that can be clicked to navigate
 * 
 * @param string $id Unique ID for the thumbnav
 * @param array $items Array of thumbnail items with images and labels
 * @param string $orientation Orientation: 'horizontal' or 'vertical'
 * @param string $size Thumbnail size: 'sm', 'md', 'lg'
 * @param bool $rounded Whether to use rounded thumbnails
 * @param string $theme Theme variant: 'light' or 'dark'
 * @param bool $showLabels Whether to show labels under thumbnails
 */
--}}

@props([
    'id' => 'thumbnav-' . uniqid(),
    'items' => [],
    'orientation' => 'horizontal',
    'size' => 'md',
    'rounded' => true,
    'theme' => 'light',
    'showLabels' => true,
])

@php
    $orientationClass = $orientation === 'vertical' ? 'flex-col space-y-2' : 'flex-row space-x-2';
    $sizeClasses = [
        'sm' => 'w-12 h-12',
        'md' => 'w-16 h-16', 
        'lg' => 'w-20 h-20'
    ];
    $themeClass = $theme === 'dark' ? 'bg-gray-800' : 'bg-gray-100';
    $roundedClass = $rounded ? 'rounded' : '';
@endphp

<nav 
    id="{{ $id }}"
    class="thumbnav {{ $themeClass }} p-3 rounded-lg"
    x-data="{
        activeItem: null,
        
        setActive(itemId) {
            this.activeItem = itemId;
            this.$dispatch('thumbnav-changed', { itemId: itemId });
        },
        
        isActive(itemId) {
            return this.activeItem === itemId;
        }
    }"
>
    <div class="flex {{ $orientationClass }} items-center justify-center">
        @foreach($items as $item)
            <button
                type="button"
                class="thumbnav-item transition-all duration-200 {{ $sizeClasses[$size] }} {{ $roundedClass }} overflow-hidden border-2"
                :class="{
                    'border-blue-500 scale-105 shadow-md': isActive('{{ $item['id'] }}'),
                    'border-gray-300 hover:border-gray-400': !isActive('{{ $item['id'] }}') && theme === 'light',
                    'border-gray-600 hover:border-gray-500': !isActive('{{ $item['id'] }}') && theme === 'dark'
                }"
                @click="setActive('{{ $item['id'] }}')"
                aria-label="Vai a {{ $item['label'] }}"
            >
                @if(isset($item['image']))
                    <img 
                        src="{{ $item['image'] }}" 
                        alt="{{ $item['label'] }}"
                        class="w-full h-full object-cover"
                        loading="lazy"
                    >
                @elseif(isset($item['icon']))
                    <div class="w-full h-full flex items-center justify-center">
                        <x-dynamic-component :component="$item['icon']" class="w-6 h-6" />
                    </div>
                @else
                    <div class="w-full h-full flex items-center justify-center bg-gray-200">
                        <span class="text-xs text-gray-600">{{ substr($item['label'], 0, 2) }}</span>
                    </div>
                @endif
            </button>
            
            @if($showLabels)
                <span class="text-xs text-center mt-1" x-show="isActive('{{ $item['id'] }}')">
                    {{ $item['label'] }}
                </span>
            @endif
        @endforeach
    </div>
</nav>

{{-- JavaScript for external control --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const thumbnav = document.getElementById('{{ $id }}');
    
    // Listen for external activation events
    thumbnav.addEventListener('thumbnav-activate', function(e) {
        const { itemId } = e.detail;
        Alpine.evaluate(thumbnav, `setActive('${itemId}')`);
    });
    
    // Emit change events for parent components
    thumbnav.addEventListener('thumbnav-changed', function(e) {
        window.dispatchEvent(new CustomEvent('thumbnav-{{ $id }}-changed', {
            detail: e.detail
        }));
    });
});
</script>