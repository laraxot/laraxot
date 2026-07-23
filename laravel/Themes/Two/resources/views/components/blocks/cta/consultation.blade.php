@props([
    'title' => '',
    'subtitle' => '',
    'primary_cta_label' => 'Prenota una Consulenza',
    'primary_cta_url' => '/contatti',
    'secondary_cta_label' => '',
    'secondary_cta_url' => '#',
    'bg_color' => 'gradient',
    'show_testimonials' => false,
])

@php
    $bgClass = match($bg_color) {
        'gradient' => 'bg-gradient-to-br from-[#1E5A96] via-[#164575] to-[#0f2b46]',
        'brand-blue' => 'bg-[#1E5A96]',
        'brand-green' => 'bg-[#2D8659]',
        default => 'bg-gradient-to-br from-[#1E5A96] to-[#164575]',
    };
@endphp

<section class="py-20 {{ $bgClass }} text-white">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <div class="w-16 h-16 mx-auto mb-6 bg-white/10 rounded-2xl flex items-center justify-center">
            <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
            </svg>
        </div>

        @if($title)
            <h2 class="text-3xl lg:text-4xl font-bold mb-4">{{ $title }}</h2>
        @endif

        @if($subtitle)
            <p class="text-lg text-white/80 mb-10 max-w-2xl mx-auto">{{ $subtitle }}</p>
        @endif

        <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
            @if(!empty($primary_cta_label))
                <a href="{{ $primary_cta_url }}"
                   class="inline-flex items-center px-8 py-4 bg-[#E67E22] hover:bg-[#d35400] text-white font-semibold rounded-xl shadow-lg transition-all duration-300 hover:shadow-xl">
                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                    </svg>
                    {{ $primary_cta_label }}
                </a>
            @endif

            @if(!empty($secondary_cta_label))
                <a href="{{ $secondary_cta_url }}"
                   class="inline-flex items-center px-8 py-4 bg-white/10 hover:bg-white/20 text-white font-semibold rounded-xl border border-white/30 transition-all duration-300">
                    {{ $secondary_cta_label }}
                </a>
            @endif
        </div>
    </div>
</section>
