@props([
    'title' => '',
    'subtitle' => '',
    'resources' => [],
])

<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">{{ $subtitle }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
            @foreach ($resources as $i => $resource)
            @php
                $isBlue = $i % 2 == 0;
                $gradientFrom = $isBlue ? 'from-blue-50' : 'from-green-50';
                $gradientTo = $isBlue ? 'to-blue-100' : 'to-green-100';
                $borderColor = $isBlue ? 'border-blue-200' : 'border-green-200';
                $iconBg = $isBlue ? 'bg-brand-blue' : 'bg-brand-green';
                $textColor = $isBlue ? 'text-brand-blue' : 'text-brand-green';
            @endphp
            <div class="bg-gradient-to-br {{ $gradientFrom }} {{ $gradientTo }} p-8 rounded-2xl border {{ $borderColor }} hover:shadow-xl transition-all duration-300">
                <div class="flex items-start gap-4">
                    {{-- Icon --}}
                    <div class="w-16 h-16 {{ $iconBg }} rounded-lg flex items-center justify-center flex-shrink-0 shadow-md">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>

                    <div class="flex-1">
                        <h3 class="text-xl font-bold {{ $textColor }} mb-2">{{ $resource['title'] ?? '' }}</h3>
                        <p class="text-gray-600 mb-4 text-sm leading-relaxed">{{ $resource['description'] ?? '' }}</p>
                        <a href="{{ $resource['download_url'] ?? '#' }}"
                           class="{{ $textColor }} font-semibold hover:text-brand-orange-dark transition-colors inline-flex items-center">
                            Scarica PDF
                            <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>