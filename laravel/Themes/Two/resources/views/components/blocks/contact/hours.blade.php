@props([
    'title' => 'Orari di Lavoro',
    'schedule' => [],
])

<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-2xl mx-auto">
            @if(!empty($title))
                <h2 class="text-2xl font-bold text-gray-900 mb-8 text-center">{{ $title }}</h2>
            @endif

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                @foreach($schedule as $index => $item)
                    <div class="flex items-center justify-between px-6 py-4 {{ $index > 0 ? 'border-t border-gray-100' : '' }} {{ !empty($item['highlight']) ? 'bg-[#1E5A96]/5' : '' }}">
                        <span class="font-medium text-gray-900 {{ !empty($item['highlight']) ? 'text-[#1E5A96]' : '' }}">
                            {{ $item['day'] ?? '' }}
                        </span>
                        <span class="text-gray-600 {{ !empty($item['highlight']) ? 'font-bold text-[#2D8659]' : '' }}">
                            {{ $item['hours'] ?? '' }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
