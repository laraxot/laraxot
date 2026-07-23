@props([
    'title' => 'Tags',
    'subtitle' => '',
    'tags' => [],
    'style' => 'cloud'
])

<section class="py-12 bg-white">
    <div class="container mx-auto px-4 max-w-4xl text-center">
        @if($title)
            <h3 class="text-2xl font-bold mb-2 text-gray-900">{{ $title }}</h3>
        @endif
        @if($subtitle)
            <p class="text-gray-500 mb-8">{{ $subtitle }}</p>
        @endif

        <div class="flex flex-wrap justify-center gap-2">
            @foreach($tags as $tag)
                <a href="#" class="px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-sm font-medium flex items-center gap-1 group">
                    <span class="text-gray-400 group-hover:text-gray-600">#</span>{{ $tag['name'] }}
                    @if(isset($tag['count']))
                        <span class="text-xs text-gray-400 ml-1 group-hover:text-gray-500">({{ $tag['count'] }})</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</section>
