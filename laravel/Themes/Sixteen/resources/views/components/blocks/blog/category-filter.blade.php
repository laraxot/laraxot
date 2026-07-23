@props([
    'title' => 'Categorie',
    'subtitle' => '',
    'categories' => [],
    'style' => 'pills'
])

<section class="py-8 bg-gray-50 border-b border-gray-200">
    <div class="container mx-auto px-4">
        @if($title)
            <div class="text-center mb-6">
                <h3 class="text-xl font-bold text-gray-900">{{ $title }}</h3>
                @if($subtitle)
                    <p class="text-sm text-gray-500 mt-1">{{ $subtitle }}</p>
                @endif
            </div>
        @endif

        <div class="flex flex-wrap justify-center gap-3">
            <a href="#" class="px-5 py-2 rounded-full bg-blue-600 text-white font-medium shadow-md hover:bg-blue-700 transition-colors">Tutti</a>
            @foreach($categories as $category)
                @php
                    $color = $category['color'] ?? 'blue';
                    $hoverClass = match($color) {
                        'blue' => 'hover:border-blue-500 hover:text-blue-500',
                        'green' => 'hover:border-green-500 hover:text-green-500',
                        'purple' => 'hover:border-purple-500 hover:text-purple-500',
                        'orange' => 'hover:border-orange-500 hover:text-orange-500',
                        'teal' => 'hover:border-teal-500 hover:text-teal-500',
                        'red' => 'hover:border-red-500 hover:text-red-500',
                        default => 'hover:border-blue-500 hover:text-blue-500',
                    };
                @endphp
                <a href="#" class="px-5 py-2 rounded-full bg-white border border-gray-200 text-gray-700 {{ $hoverClass }} transition-all font-medium shadow-sm flex items-center gap-2 group">
                    {{ $category['name'] }}
                    @if(isset($category['count']))
                        <span class="bg-gray-100 text-gray-500 text-xs px-2 py-0.5 rounded-full group-hover:bg-gray-200 transition-colors min-w-[1.5rem] text-center">{{ $category['count'] }}</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</section>
