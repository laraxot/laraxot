@props([
    'title' => '',
    'subtitle' => '',
    'breadcrumb_label' => '',
    'image' => null,
])

<section class="pt-24 pb-12 bg-gradient-to-r from-[#1E5A96] to-[#164575] text-white">
    <div class="container mx-auto px-4">
        {{-- Breadcrumb --}}
        <nav class="flex items-center space-x-2 text-sm py-4" aria-label="Breadcrumb">
            <a class="flex items-center text-gray-300 hover:text-white transition-colors" href="/{{ app()->getLocale() }}">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" /><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                </svg>
            </a>
            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="m9 18 6-6-6-6" /></svg>
            <span class="text-white font-medium">{{ $breadcrumb_label ?: $title }}</span>
        </nav>

        <div class="mt-8">
            @if($title)
                <h1 class="text-5xl md:text-6xl font-bold mb-6">{{ $title }}</h1>
            @endif
            @if($subtitle)
                <p class="text-xl text-gray-100 max-w-3xl">{{ $subtitle }}</p>
            @endif
        </div>
    </div>
</section>
