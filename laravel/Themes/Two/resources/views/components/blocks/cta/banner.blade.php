{{-- CTA Banner Block - Theme Two --}}
@props([
    'title' => '',
    'description' => '',
    'cta_primary' => ['label' => 'Inizia', 'url' => '#'],
    'cta_secondary' => ['label' => 'Scopri', 'url' => '#'],
    'background_gradient' => 'from-primary to-secondary',
    'text_color' => 'text-primary-content'
])

<section class="py-20 relative overflow-hidden">
    {{-- Dynamic Gradient Background --}}
    <div class="absolute inset-0 bg-gradient-to-br {{ $background_gradient }} opacity-90"></div>
    
    {{-- Overlay pattern --}}
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 24px 24px;"></div>

    <div class="max-w-5xl mx-auto px-6 relative z-10 text-center {{ $text_color }}">
        @if($title)
            <h2 class="text-4xl md:text-6xl font-black mb-8 leading-tight drop-shadow-lg">
                {{ $title }}
            </h2>
        @endif

        @if($description)
            <p class="text-xl md:text-2xl mb-12 opacity-90 max-w-3xl mx-auto leading-relaxed font-medium">
                {{ $description }}
            </p>
        @endif

        <div class="flex flex-col sm:flex-row gap-6 justify-center items-center">
            @if(isset($cta_primary['label']))
                <a href="{{ $cta_primary['url'] }}" 
                   class="btn btn-lg btn-neutral px-10 shadow-2xl hover:scale-105 transition-all duration-300">
                    {{ $cta_primary['label'] }}
                </a>
            @endif

            @if(isset($cta_secondary['label']))
                <a href="{{ $cta_secondary['url'] }}" 
                   class="btn btn-lg btn-outline border-white text-white hover:bg-white hover:text-primary px-10 transition-all duration-300">
                    {{ $cta_secondary['label'] }}
                </a>
            @endif
        </div>
    </div>
</section>

