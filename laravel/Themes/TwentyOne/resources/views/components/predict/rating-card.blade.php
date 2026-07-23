{{-- Componente riutilizzabile per le card dei rating --}}
@props([
    'rating',
    'predictId',
    'action' => 'bet'
])

@php
    $data = [
        'predict_id' => $predictId,
        'rating_id' => $rating->id,
    ];

    // Gestione dell'immagine con fallback sicuro
    $image = $rating->getFirstMedia();
    $imageUrl = null;

    if ($image == null) {
        // Genera un URL casuale per l'immagine placeholder
        $imageId = $rating->id ?? rand(1, 1000);
        $imageUrl = "https://picsum.photos/seed/{$imageId}/200/300";

        // Usa sempre l'URL diretto per evitare problemi di permessi filesystem
        $image = (object)[
            'url' => $imageUrl,
            'alt' => $rating->title ?? 'Rating Image'
        ];
    }
@endphp

<button wire:click="mountAction('{{ $action }}', @js($data))" class="block w-full">
    <div class="relative overflow-hidden rounded-lg group/rating cursor-pointer transform transition-all duration-300 hover:scale-105 hover:shadow-lg hover:z-10">
        {{-- Container quadrato per l'immagine --}}
        <div class="aspect-square bg-gray-100 dark:bg-gray-700 relative">
            {{-- Immagine principale --}}
            @if(isset($image->url))
                <img src="{{ $image->url }}" class="w-full h-full object-cover transition-transform duration-300 group-hover/rating:scale-110" alt="{{ $rating->title }}">
            @else
                {{ $image('150x150')->attributes(['class' => 'w-full h-full object-cover transition-transform duration-300 group-hover/rating:scale-110']) }}
            @endif

            {{-- Overlay gradiente per migliore leggibilità --}}
            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent transition-opacity duration-300 group-hover/rating:opacity-90"></div>

            {{-- Badge percentuale --}}
            <div class="absolute top-1 right-1 transform transition-all duration-300 group-hover/rating:scale-110 group-hover/rating:rotate-3">
                <div class="bg-white dark:bg-gray-800 text-gray-900 dark:text-white text-xs font-bold px-1.5 py-0.5 rounded shadow-lg border border-gray-200 dark:border-gray-600">
                    {{ number_format($rating->pivot->percentage * 100, 1) }}%
                </div>
            </div>

            {{-- Titolo del rating --}}
            <div class="absolute bottom-1 left-1 right-1 transform transition-all duration-300 group-hover/rating:translate-y-0 group-hover/rating:scale-105">
                <div class="bg-black/70 dark:bg-gray-900/70 backdrop-blur-sm rounded px-1.5 py-1 border border-white/20">
                    <p class="text-xs font-bold text-white leading-tight line-clamp-2">
                        {{ $rating->title }}
                    </p>
                </div>
            </div>

            {{-- Effetto hover aggiuntivo --}}
            <div class="absolute inset-0 bg-blue-500/0 group-hover/rating:bg-blue-500/10 transition-all duration-300 rounded-lg"></div>
        </div>
    </div>
</button> 
