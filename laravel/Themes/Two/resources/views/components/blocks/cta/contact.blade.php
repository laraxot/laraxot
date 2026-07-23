@props([
    'title' => 'Non hai trovato la risposta che cercavi?',
    'subtitle' => '',
    'primary_cta' => [],
    'secondary_cta' => [],
])

@php
    $primaryCta = $primary_cta;
    $secondaryCta = $secondary_cta;
@endphp

<section class="py-16 bg-blue-900">
    <div class="container mx-auto px-4 text-center">
        @if(!empty($title))
            <h2 class="text-3xl font-bold text-white mb-4">{{ $title }}</h2>
        @endif
        
        @if(!empty($subtitle))
            <p class="text-xl text-blue-100 mb-8 max-w-3xl mx-auto">{{ $subtitle }}</p>
        @endif
        
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            @if(!empty($primaryCta['label']))
                <a href="{{ $primaryCta['url'] ?? '#' }}" class="inline-flex items-center justify-center px-8 py-4 bg-brand-green hover:bg-green-700 text-white font-medium rounded-lg transition-colors shadow-lg hover:shadow-xl">
                    {{ $primaryCta['label'] }}
                </a>
            @endif
            
            @if(!empty($secondaryCta['label']))
                <a href="{{ $secondaryCta['url'] ?? '#' }}" class="inline-flex items-center justify-center px-8 py-4 bg-white hover:bg-gray-100 text-brand-blue font-medium rounded-lg transition-colors shadow-lg hover:shadow-xl">
                    {{ $secondaryCta['label'] }}
                </a>
            @endif
        </div>
    </div>
</section>
