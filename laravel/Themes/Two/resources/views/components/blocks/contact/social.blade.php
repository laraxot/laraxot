@props([
    'title' => '',
    'description' => '',
    'platforms' => [],
])

@php
    $iconMap = [
        'facebook' => '<path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z"/>',
        'twitter' => '<path d="M22 4s-.7 2.1-2 3.4c1.6 10-9.4 17.3-18 11.6 2.2.1 4.4-.6 6-2C3 15.5.5 9.6 3 5c2.2 2.6 5.6 4.1 9 4-.9-4.2 4-6.6 7-3.8 1.1 0 3-1.2 3-1.2z"/>',
        'linkedin' => '<path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6z"/><rect width="4" height="12" x="2" y="9"/><circle cx="4" cy="4" r="2"/>',
        'youtube' => '<path d="M22.54 6.42a2.78 2.78 0 00-1.94-2C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 00-1.94 2A29 29 0 001 11.75a29 29 0 00.46 5.33A2.78 2.78 0 003.4 19.1c1.72.46 8.6.46 8.6.46s6.88 0 8.6-.46a2.78 2.78 0 001.94-2 29 29 0 00.46-5.35 29 29 0 00-.46-5.33z"/><polygon points="9.75,15.02 15.5,11.75 9.75,8.48"/>',
    ];
    $colorMap = [
        'blue' => 'bg-blue-100 text-blue-600 hover:bg-blue-200',
        'sky' => 'bg-sky-100 text-sky-600 hover:bg-sky-200',
        'red' => 'bg-red-100 text-red-600 hover:bg-red-200',
    ];
@endphp

<section class="py-12 bg-white">
    <div class="container mx-auto px-4 text-center">
        @if(!empty($title))
            <h2 class="text-2xl font-bold text-gray-900 mb-3">{{ $title }}</h2>
        @endif
        @if(!empty($description))
            <p class="text-gray-600 mb-8">{{ $description }}</p>
        @endif

        <div class="flex justify-center gap-4">
            @foreach($platforms as $platform)
                @php
                    $icon = $iconMap[$platform['icon'] ?? 'facebook'] ?? '';
                    $color = $colorMap[$platform['color'] ?? 'blue'] ?? $colorMap['blue'];
                @endphp
                <a href="{{ $platform['url'] ?? '#' }}"
                   class="w-12 h-12 rounded-full {{ $color }} flex items-center justify-center transition-colors"
                   aria-label="{{ $platform['name'] ?? '' }}"
                   target="_blank" rel="noopener">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">{!! $icon !!}</svg>
                </a>
            @endforeach
        </div>
    </div>
</section>
