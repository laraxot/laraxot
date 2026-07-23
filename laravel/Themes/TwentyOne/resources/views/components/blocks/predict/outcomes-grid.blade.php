@props(['predict'])

@php
    $ratings = $predict?->ratings ?? collect();
    $locale = app()->getLocale();
@endphp

@if($ratings->isNotEmpty())
<div class="w-full py-8">
    <h3 class="text-2xl font-bold text-gray-900 dark:text-white mb-6">
        {{ __('predict::list.outcomes.heading') ?: 'Scegli il tuo vincitore' }}
    </h3>
    
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        @foreach($ratings as $rating)
            @php
                $title = is_array($rating->title) 
                    ? ($rating->title[$locale] ?? $rating->title['en'] ?? 'Unknown')
                    : $rating->title;
                $color = $rating->color ?? '#6366f1';
                // Each outcome gets equal probability (12.5% for 8 outcomes)
                $probability = 12.5;
            @endphp
            <a 
                href="{{ url('/' . $locale . '/predicts/' . $predict->slug) }}"
                class="group relative overflow-hidden rounded-2xl border border-gray-200 bg-white shadow-lg transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl dark:border-gray-700 dark:bg-gray-800"
            >
                {{-- Color Header --}}
                <div class="h-24 relative" style="background: linear-gradient(135deg, {{ $color }}20, {{ $color }}40);">
                    {{-- Percentage Badge --}}
                    <div class="absolute top-3 right-3 rounded-full bg-white/95 px-3 py-1 shadow-lg backdrop-blur-sm">
                        <span class="text-xl font-bold" style="color: {{ $color }}">
                            {{ number_format($probability, 0) }}%
                        </span>
                    </div>
                </div>
                
                {{-- Label --}}
                <div class="p-4">
                    <p class="text-center text-sm font-semibold text-gray-900 dark:text-white line-clamp-2">
                        {{ $title }}
                    </p>
                </div>
                
                {{-- Hover Effect --}}
                <div class="absolute inset-0 bg-gradient-to-t from-black/5 via-transparent to-transparent opacity-0 transition-opacity duration-300 group-hover:opacity-100"></div>
            </a>
        @endforeach
    </div>
</div>
@endif