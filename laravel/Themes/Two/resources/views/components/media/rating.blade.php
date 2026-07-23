@props([
    'rating' => 0,
    'maxRating' => 5,
    'interactive' => false,
    'size' => 'md', // sm, md, lg
    'color' => 'yellow', // yellow, blue, green, red
    'showCount' => false,
    'reviewCount' => 0,
])

@php
    $sizes = [
        'sm' => 'w-4 h-4',
        'md' => 'w-5 h-5',
        'lg' => 'w-6 h-6',
    ];
    
    $colors = [
        'yellow' => 'text-yellow-400',
        'blue' => 'text-blue-400',
        'green' => 'text-green-400',
        'red' => 'text-red-400',
    ];
    
    $sizeClass = $sizes[$size] ?? $sizes['md'];
    $colorClass = $colors[$color] ?? $colors['yellow'];
@endphp

<div class="media-rating flex items-center space-x-1">
    <!-- Stars -->
    <div class="flex items-center">
        @for($i = 1; $i <= $maxRating; $i++)
            @if($i <= $rating)
                <!-- Filled Star -->
                @if($interactive)
                    <button 
                        type="button"
                        @click="$wire.setRating({{ $i }})"
                        class="focus:outline-none focus:ring-2 focus:ring-primary-500 rounded"
                    >
                        <svg 
                            class="{{ $sizeClass }} {{ $colorClass }} transition-colors" 
                            fill="currentColor" 
                            viewBox="0 0 20 20"
                        >
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </button>
                @else
                    <svg 
                        class="{{ $sizeClass }} {{ $colorClass }}" 
                        fill="currentColor" 
                        viewBox="0 0 20 20"
                    >
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endif
            @else
                <!-- Empty Star -->
                @if($interactive)
                    <button 
                        type="button"
                        @click="$wire.setRating({{ $i }})"
                        class="focus:outline-none focus:ring-2 focus:ring-primary-500 rounded"
                    >
                        <svg 
                            class="{{ $sizeClass }} text-gray-300 hover:text-gray-400 transition-colors" 
                            fill="currentColor" 
                            viewBox="0 0 20 20"
                        >
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                    </button>
                @else
                    <svg 
                        class="{{ $sizeClass }} text-gray-300" 
                        fill="currentColor" 
                        viewBox="0 0 20 20"
                    >
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                    </svg>
                @endif
            @endif
        @endfor
    </div>
    
    <!-- Review Count -->
    @if($showCount && $reviewCount > 0)
        <span class="text-sm text-gray-600">
            ({{ number_format($reviewCount) }} {{ $reviewCount === 1 ? __('recensione') : __('recensioni') }})
        </span>
    @endif
    
    <!-- Numeric Rating -->
    @if($interactive === false)
        <span class="text-sm font-medium text-gray-900">
            {{ number_format($rating, 1) }}/{{ $maxRating }}
        </span>
    @endif
</div>