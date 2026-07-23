@props([
    'title' => '',
    'subtitle' => '',
    'style' => 'pills',
    'show_descriptions' => false,
    'categories' => [],
])

@php
    $colorMap = [
        'blue' => 'bg-[#1E5A96] text-white',
        'green' => 'bg-[#2D8659] text-white',
        'orange' => 'bg-[#E67E22] text-white',
        'teal' => 'bg-teal-600 text-white',
        'purple' => 'bg-purple-600 text-white',
        'indigo' => 'bg-indigo-600 text-white',
        'red' => 'bg-red-600 text-white',
    ];
@endphp

<section class="bg-white pb-8">
    <div class="container mx-auto px-4">
        <div class="flex flex-wrap items-center gap-3">
            <button class="px-5 py-2.5 rounded-lg text-sm font-semibold bg-[#1E5A96] text-white shadow-sm transition-all">
                Tutti
            </button>
            @foreach($categories as $category)
                <button class="px-5 py-2.5 rounded-lg text-sm font-medium text-gray-700 bg-gray-100 hover:bg-gray-200 border border-gray-200 transition-all">
                    {{ $category['name'] ?? '' }}
                </button>
            @endforeach
        </div>
    </div>
</section>
