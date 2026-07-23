@props(['title', 'subtitle' => null, 'image' => null, 'imageAlt' => null, 'hover' => false, 'clickable' => false, 'href' => null])

<div {{ $attributes->class(['bg-white rounded-lg shadow-sm border border-gray-200 h-full flex flex-col overflow-hidden', 'hover:shadow-md transition-shadow duration-200' => $hover]) }}>
    @if($image)
        <div class="h-48 w-full overflow-hidden">
            <img src="{{ $image }}" alt="{{ $imageAlt ?? $title }}" class="w-full h-full object-cover transform hover:scale-105 transition-transform duration-300">
        </div>
    @endif

    <div class="p-6 flex flex-col flex-grow">
        @if($subtitle)
            <p class="text-xs font-bold text-primary-600 uppercase tracking-wider mb-2">
                {{ $subtitle }}
            </p>
        @endif
        
        <h3 class="text-xl font-bold text-gray-900 mb-3 leading-tight">
            @if($href && $clickable)
                <a href="{{ $href }}" class="hover:text-primary-600 transition-colors focus:outline-none focus:underline">
                    {{ $title }}
                </a>
            @else
                {{ $title }}
            @endif
        </h3>
        
        <div class="text-gray-600 text-base mb-4 flex-grow">
            {{ $slot }}
        </div>
        
        @if($href && !$clickable) <!-- If not clickable title but has href, show button/link at bottom -->
             <div class="mt-auto pt-4">
                <a href="{{ $href }}" class="inline-flex items-center text-primary-600 font-semibold hover:text-primary-700 hover:underline">
                    Approfondisci <span aria-hidden="true">&rarr;</span>
                </a>
             </div>
        @endif
    </div>
</div>
