{{--
    Hero Segnalazioni List Block
    Reference: https://italia.github.io/design-comuni-pagine-statiche/sito/segnalazioni-elenco.html
    Design: Tailwind CSS + DaisyUI + Flowbite-style components
    Block naming convention: Flowbite/TailwindUI blocks naming
--}}
@props([
    'title' => 'Elenco segnalazioni',
    'subtitle' => 'Negli ultimi 12 mesi sono state risolte :count segnalazioni.',
    'count' => 73,
    'cta_text' => 'Segnala disservizio',
    'cta_url' => '/it/tests/ticket-crea',
])

<section class="bg-gradient-to-r from-primary-600 to-primary-800 relative overflow-hidden">
    <div class="container mx-auto px-4 py-12 lg:py-20">
        <div class="max-w-3xl">
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-4">{{ $title }}</h1>
            <p class="text-lg md:text-xl text-white/90 mb-8">{{ str_replace(':count', $count, $subtitle) }}</p>
            @if($cta_text)
                <a href="{{ $cta_url }}"
                   class="inline-flex items-center px-6 py-3 bg-secondary-500 hover:bg-secondary-600 text-white font-semibold rounded-lg transition duration-200 focus:outline-none focus:ring-2 focus:ring-secondary-400 focus:ring-offset-2">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M10 3a1 1 0 011 1v5h5a1 1 0 110 2h-5v5a1 1 0 11-2 0v-5H4a1 1 0 110-2h5V4a1 1 0 011-1z" clip-rule="evenodd"></path>
                    </svg>
                    {{ $cta_text }}
                </a>
            @endif
        </div>
    </div>
    <div class="absolute inset-0 opacity-10 pointer-events-none">
        <svg class="absolute w-full h-full" viewBox="0 0 100 100" preserveAspectRatio="none">
            <path d="M0,50 Q25,30 50,50 T100,50 L100,100 L0,100 Z" fill="currentColor"/>
        </svg>
    </div>
</section>