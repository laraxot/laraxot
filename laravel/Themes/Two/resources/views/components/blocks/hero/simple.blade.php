@props([
    'title' => '',
    'subtitle' => '',
    'primary_cta_label' => '',
    'primary_cta_url' => '#',
    'secondary_cta_label' => '',
    'secondary_cta_url' => '#',
    'image' => '',
    'stats' => [],
])

<section class="relative h-screen flex items-center justify-center overflow-hidden">
    {{-- Background logic --}}
    <div class="absolute inset-0 z-0 bg-gradient-to-br from-brand-blue via-brand-blue/90 to-black">
        <div class="absolute inset-0 bg-[radial-gradient(rgba(255,255,255,0.05)_1px,transparent_1px)] [background-size:24px_24px] opacity-30"></div>
        @if($image)
            <div class="absolute inset-0 bg-cover bg-center bg-no-repeat opacity-40 mix-blend-overlay"
                 style="background-image: url('{{ $image }}');"></div>
        @endif
    </div>

    {{-- Content --}}
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-4xl">
            @if($title)
                <h1 class="text-5xl md:text-6xl lg:text-7xl font-bold text-white mb-6 leading-tight">{{ $title }}</h1>
            @endif

            @if($subtitle)
                <p class="text-xl md:text-2xl text-gray-200 mb-8 leading-relaxed max-w-2xl">{{ $subtitle }}</p>
            @endif

            {{-- CTA Buttons --}}
            <div class="flex flex-col sm:flex-row gap-4">
                @if($primary_cta_label)
                    <a href="{{ $primary_cta_url }}"
                       class="inline-flex items-center justify-center font-semibold rounded-lg bg-brand-green hover:bg-brand-green/90 text-white text-lg px-8 py-4 group shadow-xl transition-all hover:-translate-y-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/></svg>
                        {{ $primary_cta_label }}
                    </a>
                @endif

                @if($secondary_cta_label)
                    <a href="{{ $secondary_cta_url }}"
                       class="inline-flex items-center justify-center font-semibold rounded-lg bg-white/10 text-white hover:bg-white hover:text-brand-blue border border-white/30 text-lg px-8 py-4 backdrop-blur-sm transition-all shadow-lg">
                        <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 w-5 h-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        {{ $secondary_cta_label }}
                    </a>
                @endif
            </div>

            {{-- Stats bar --}}
            @if(!empty($stats))
                <div class="mt-12 grid grid-cols-3 gap-6 text-white max-w-2xl">
                    @foreach($stats as $stat)
                        <div class="border-l-4 border-brand-orange pl-4">
                            <div class="text-2xl font-bold">{{ $stat['value'] ?? '' }}</div>
                            <div class="text-sm text-white/70">{{ $stat['label'] ?? '' }}</div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    {{-- Bottom gradient fade --}}
    <div class="absolute bottom-0 left-0 right-0 h-24 bg-gradient-to-t from-gray-50 to-transparent z-10"></div>
</section>
