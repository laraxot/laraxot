@props([
    'title' => '',
    'subtitle' => '',
    'primary_cta' => [],
    'secondary_cta' => [],
])

<section class="pt-24 pb-16 bg-gradient-to-r from-[#1E5A96] to-[#164575] text-white">
    <div class="container mx-auto px-4">
        <nav class="flex items-center space-x-2 text-sm py-4" aria-label="Breadcrumb">
            <a class="flex items-center text-gray-300 hover:text-white transition-colors" href="/{{ app()->getLocale() }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" /><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                </svg>
            </a>
            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6" /></svg>
            <span class="text-white font-medium">Contatti</span>
        </nav>

        <div class="mt-8 max-w-3xl">
            @if($title)
                <h1 class="text-5xl md:text-6xl font-bold mb-6">{{ $title }}</h1>
            @endif
            @if($subtitle)
                <p class="text-xl text-gray-200 mb-8 leading-relaxed">{{ $subtitle }}</p>
            @endif

            @if(!empty($primary_cta) || !empty($secondary_cta))
                <div class="flex flex-wrap gap-4">
                    @if(!empty($primary_cta['label']))
                        <a href="{{ $primary_cta['url'] ?? '#' }}"
                           class="inline-flex items-center px-6 py-3 bg-[#2D8659] hover:bg-[#246e49] text-white font-semibold rounded-lg transition-all">
                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>
                            </svg>
                            {{ $primary_cta['label'] }}
                        </a>
                    @endif
                    @if(!empty($secondary_cta['label']))
                        <a href="{{ $secondary_cta['url'] ?? '#' }}"
                           class="inline-flex items-center px-6 py-3 border border-white/40 text-white hover:bg-white/10 font-semibold rounded-lg transition-all">
                            {{ $secondary_cta['label'] }}
                        </a>
                    @endif
                </div>
            @endif
        </div>
    </div>
</section>
