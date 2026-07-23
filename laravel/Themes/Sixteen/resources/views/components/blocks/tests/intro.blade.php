@props([
    'title' => '',
    'category' => '',
    'summary' => '',
])

<section class="py-12 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($category)
            <span class="text-sm font-semibold text-blue-600 uppercase tracking-wider">{{ $category }}</span>
        @endif
        @if($title)
            <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mt-2 mb-4">{{ $title }}</h1>
        @endif
        @if($summary)
            <p class="text-xl text-gray-600">{{ $summary }}</p>
        @endif
    </div>
</section>
