@props([
    'title' => 'Cerca',
    'subtitle' => '',
    'placeholder' => 'Cerca...',
    'show_advanced' => false,
    'show_suggestions' => false,
])

<section class="py-12 bg-white border-b border-gray-100">
    <div class="container mx-auto px-4 text-center">
        @if($title)
            <h2 class="text-2xl md:text-3xl font-bold mb-4 text-gray-900">{{ $title }}</h2>
        @endif
        @if($subtitle)
            <p class="text-gray-600 mb-8 max-w-2xl mx-auto">{{ $subtitle }}</p>
        @endif

        <div class="max-w-2xl mx-auto relative group">
            <input type="text"
                   placeholder="{{ $placeholder }}"
                   class="w-full pl-12 pr-4 py-4 rounded-full border border-gray-200 focus:border-blue-500 focus:ring-4 focus:ring-blue-100 transition-all shadow-sm text-lg outline-none group-hover:border-blue-300"
            >
            <svg class="absolute left-5 top-5 w-6 h-6 text-gray-400 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
        </div>
        
        @if($show_suggestions)
             <div class="mt-4 text-sm text-gray-500">
                <span class="font-medium">Popolari:</span> 
                <a href="#" class="hover:text-blue-600 transition-colors">Radioprotezione</a>, 
                <a href="#" class="hover:text-blue-600 transition-colors">Normativa 2026</a>, 
                <a href="#" class="hover:text-blue-600 transition-colors">Sicurezza</a>
             </div>
        @endif
    </div>
</section>
