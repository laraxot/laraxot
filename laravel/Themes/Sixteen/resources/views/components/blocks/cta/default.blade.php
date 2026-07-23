@props([
    'title' => '',
    'description' => '',
    'button_text' => '',
    'button_url' => '#',
    'button_color' => 'bg-blue-600 hover:bg-blue-700 text-white',
])

@if($title)
<section class="py-8 bg-gray-50">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
        <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ $title }}</h3>
        @if($description)
            <p class="text-gray-600 mb-6">{{ $description }}</p>
        @endif
        @if($button_text)
            <a href="{{ $button_url }}" class="inline-block {{ $button_color }} px-6 py-3 rounded-lg font-semibold transition-colors">
                {{ $button_text }}
            </a>
        @endif
    </div>
</section>
@endif
