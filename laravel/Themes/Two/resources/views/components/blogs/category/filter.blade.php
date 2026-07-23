@php
declare(strict_types=1);

@props([
    'categories' => [
        ['id' => 'all', 'name' => 'Tutti', 'slug' => 'all', 'count' => 120, 'icon' => ''],
        ['id' => 'radioprotezione', 'name' => 'Radioprotezione', 'slug' => 'radioprotezione', 'count' => 45, 'icon' => 'shield-check'],
        ['id' => 'normativa', 'name' => 'Normativa', 'slug' => 'normativa', 'count' => 30, 'icon' => 'scale-balanced'],
        ['id' => 'elettromedicali', 'name' => 'Elettromedicali', 'slug' => 'elettromedicali', 'count' => 25, 'icon' => 'microscope'],
        ['id' => 'guide', 'name' => 'Guide Pratiche', 'slug' => 'guide', 'count' => 35, 'icon' => 'book-open'],
        ['id' => 'veterinaria', 'name' => 'Veterinaria', 'slug' => 'veterinaria', 'count' => 20, 'icon' => 'paw'],
        ['id' => 'novita', 'name' => 'Novità', 'slug' => 'novita', 'count' => 15, 'icon' => 'lightning']
    ],
    'active_category' => 'all'
])
?>

{{-- Category Filter Pills --}}
<section class="py-8 bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-wrap gap-3 justify-center">
            @foreach($categories as $category)
                <a href="{{ $category['id'] === 'all' ? '/it/blog' : '/it/blog?category=' . $category['slug'] }}" 
                   class="inline-flex items-center px-5 py-3 rounded-full font-medium transition-all duration-300 transform hover:scale-105 hover:-translate-y-1
                          {{ $active_category === $category['id'] 
                              ? 'bg-gradient-to-r from-blue-600 to-blue-700 text-white shadow-lg shadow-blue-500/30' 
                              : 'bg-gray-100 text-gray-700 hover:bg-gray-200 hover:text-gray-900' }}">
                    @if($category['icon'])
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            @if($category['icon'] === 'shield-check')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            @elseif($category['icon'] === 'scale-balanced')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                            @elseif($category['icon'] === 'microscope')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                            @elseif($category['icon'] === 'book-open')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            @elseif($category['icon'] === 'paw')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            @elseif($category['icon'] === 'lightning')
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            @endif
                        </svg>
                    @endif
                    
                    <span>{{ $category['name'] }}</span>
                    
                    <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ $active_category === $category['id'] ? 'bg-white/20' : 'bg-gray-300 text-gray-700' }}">
                        {{ $category['count'] }}
                    </span>
                </a>
            @endforeach
        </div>
        
        {{-- Category Description --}}
        <div class="mt-6 text-center text-gray-600 text-sm max-w-3xl mx-auto">
            <p id="category-description">
                Esplora tutti gli articoli del nostro blog per rimanere aggiornato sulle novità del settore.
            </p>
        </div>
    </div>
</section>

<script>
    // Update category description based on active category
    const categoryDescriptions = {
        'all': 'Esplora tutti gli articoli del nostro blog per rimanere aggiornato sulle novità del settore.',
        'radioprotezione': 'Guide e approfondimenti sulla radioprotezione, sicurezza delle radiazioni e normative vigenti.',
        'normativa': 'Aggiornamenti normativi, decreti e linee guida per i servizi di radioprotezione.',
        'elettromedicali': 'Informazioni sugli elettromedicali, controllo qualità e certificazioni.',
        'guide': 'Guide pratiche e tutorial per migliorare la gestione dei servizi radiologici.',
        'veterinaria': 'Specificità della radioprotezione veterinaria e best practice.',
        'novita': 'Ultime notizie, eventi e innovazioni nel campo della radioprotezione.'
    };
    
    // Simple interactivity for category selection
    document.querySelectorAll('a[href*="category="]').forEach(link => {
        link.addEventListener('click', function() {
            const urlParams = new URLSearchParams(this.href.split('?')[1]);
            const category = urlParams.get('category') || 'all';
            const description = categoryDescriptions[category] || categoryDescriptions['all'];
            document.getElementById('category-description').textContent = description;
        });
    });
</script>