@props([
    'pre_title' => 'Sicurezza e Compliance',
    'title' => '',
    'subtitle' => '',
    'points' => [],
])

@php
    $iconBgs = ['bg-red-500/20', 'bg-blue-500/20', 'bg-green-500/20', 'bg-yellow-500/20'];
    $iconTexts = ['text-red-400', 'text-blue-400', 'text-green-400', 'text-yellow-400'];
@endphp

<section class="py-20 bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 text-white scroll-mt-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <p class="text-sm font-semibold uppercase tracking-widest text-brand-green mb-3">{{ $pre_title }}</p>
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ $title }}</h2>
            <p class="text-xl text-gray-300 max-w-3xl mx-auto">{{ $subtitle }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            @foreach ($points as $i => $point)
            @php $ci = $i % 4; @endphp
            <div class="bg-white/5 p-6 rounded-xl border border-white/10 hover:bg-white/10 transition-all duration-300">
                <div class="w-12 h-12 {{ $iconBgs[$ci] }} rounded-lg flex items-center justify-center mb-5">
                    @if (str_contains($point['icon'] ?? '', 'exclamation') || str_contains($point['icon'] ?? '', 'triangle'))
                        <svg class="w-6 h-6 {{ $iconTexts[$ci] }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    @elseif (str_contains($point['icon'] ?? '', 'book'))
                        <svg class="w-6 h-6 {{ $iconTexts[$ci] }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    @elseif (str_contains($point['icon'] ?? '', 'user') || str_contains($point['icon'] ?? '', 'group'))
                        <svg class="w-6 h-6 {{ $iconTexts[$ci] }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v-.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/></svg>
                    @elseif (str_contains($point['icon'] ?? '', 'scale'))
                        <svg class="w-6 h-6 {{ $iconTexts[$ci] }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v17.25m0 0c-1.472 0-2.882.265-4.185.75M12 20.25c1.472 0 2.882.265 4.185.75M18.75 4.97A48.416 48.416 0 0012 4.5c-2.291 0-4.545.16-6.75.47m13.5 0c1.01.143 2.01.317 3 .52m-3-.52l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.988 5.988 0 01-2.031.352 5.988 5.988 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L18.75 4.971zm-16.5.52c.99-.203 1.99-.377 3-.52m0 0l2.62 10.726c.122.499-.106 1.028-.589 1.202a5.989 5.989 0 01-2.031.352 5.989 5.989 0 01-2.031-.352c-.483-.174-.711-.703-.59-1.202L5.25 4.971z"/></svg>
                    @else
                        <svg class="w-6 h-6 {{ $iconTexts[$ci] }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    @endif
                </div>

                <h4 class="text-xl font-bold mb-3">{{ $point['title'] ?? '' }}</h4>
                <p class="text-gray-300 leading-relaxed">{{ $point['description'] ?? '' }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>