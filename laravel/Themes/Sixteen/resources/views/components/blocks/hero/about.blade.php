@props([
    'title' => 'Chi Siamo',
    'subtitle' => '',
    'stats' => [],
    'primary_cta' => null,
    'secondary_cta' => null,
    'image' => '/themes/Two/Main_files/images/hero-bg.jpg',
])

@php
    $primaryCta = $primary_cta;
    $secondaryCta = $secondary_cta;
@endphp

<section class="relative min-h-[70vh] flex items-center justify-center overflow-hidden">
    {{-- Background --}}
    <div class="absolute inset-0 z-0">
        <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" 
             style="background-image: url('{{ $image }}');">
        </div>
        <div class="absolute inset-0 bg-gradient-to-r from-brand-blue/95 via-brand-blue/80 to-brand-blue/60"></div>
    </div>
    
    {{-- Content --}}
    <div class="container mx-auto px-4 relative z-10 py-20">
        <div class="max-w-4xl">
            {{-- Title --}}
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-6 leading-tight">
                {{ $title }}
            </h1>
            
            {{-- Subtitle --}}
            @if($subtitle)
                <p class="text-xl md:text-2xl text-gray-200 mb-8 leading-relaxed max-w-2xl">
                    {{ $subtitle }}
                </p>
            @endif
            
            {{-- CTAs --}}
            @if($primaryCta || $secondaryCta)
                <div class="flex flex-col sm:flex-row gap-4 mb-12">
                    @if($primaryCta)
                        <a href="{{ $primaryCta['url'] ?? '#' }}" 
                           class="inline-flex items-center justify-center bg-brand-green hover:bg-brand-green/90 text-white text-lg px-8 py-4 rounded-lg font-semibold shadow-xl transition-all group">
                            <x-filament::icon icon="heroicon-o-users" class="w-5 h-5 mr-2" />
                            {{ $primaryCta['label'] ?? 'Scopri di più' }}
                        </a>
                    @endif
                    @if($secondaryCta)
                        <a href="{{ $secondaryCta['url'] ?? '#' }}" 
                           class="inline-flex items-center justify-center bg-white/10 text-white hover:bg-white hover:text-brand-blue border-2 border-white text-lg px-8 py-4 rounded-lg backdrop-blur-sm transition-all">
                            <x-filament::icon icon="heroicon-o-phone" class="w-5 h-5 mr-2" />
                            {{ $secondaryCta['label'] ?? 'Contattaci' }}
                        </a>
                    @endif
                </div>
            @endif
            
            {{-- Stats --}}
            @if(count($stats) > 0)
                <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-white">
                    @foreach($stats as $stat)
                        <div class="border-l-4 border-brand-orange pl-4">
                            <div class="text-3xl font-bold">{{ $stat['value'] ?? '0' }}</div>
                            <div class="text-sm text-gray-300">{{ $stat['label'] ?? '' }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
</section>
