@props([
    'title' => 'Esplora per Tag',
    'subtitle' => '',
    'style' => 'cloud',
    'max_tags' => 20,
    'show_count' => true,
    'tags' => [],
])

@php
    $tagColors = [
        'blue' => 'bg-blue-50 text-blue-700 border-blue-200 hover:bg-blue-100',
        'green' => 'bg-green-50 text-green-700 border-green-200 hover:bg-green-100',
        'orange' => 'bg-orange-50 text-orange-700 border-orange-200 hover:bg-orange-100',
        'purple' => 'bg-purple-50 text-purple-700 border-purple-200 hover:bg-purple-100',
        'teal' => 'bg-teal-50 text-teal-700 border-teal-200 hover:bg-teal-100',
        'indigo' => 'bg-indigo-50 text-indigo-700 border-indigo-200 hover:bg-indigo-100',
        'red' => 'bg-red-50 text-red-700 border-red-200 hover:bg-red-100',
        'yellow' => 'bg-yellow-50 text-yellow-700 border-yellow-200 hover:bg-yellow-100',
        'pink' => 'bg-pink-50 text-pink-700 border-pink-200 hover:bg-pink-100',
        'gray' => 'bg-gray-50 text-gray-700 border-gray-200 hover:bg-gray-100',
        'cyan' => 'bg-cyan-50 text-cyan-700 border-cyan-200 hover:bg-cyan-100',
        'emerald' => 'bg-emerald-50 text-emerald-700 border-emerald-200 hover:bg-emerald-100',
        'lime' => 'bg-lime-50 text-lime-700 border-lime-200 hover:bg-lime-100',
        'amber' => 'bg-amber-50 text-amber-700 border-amber-200 hover:bg-amber-100',
        'rose' => 'bg-rose-50 text-rose-700 border-rose-200 hover:bg-rose-100',
    ];
@endphp

<section class="py-12 bg-white">
    <div class="container mx-auto px-4">
        @if(!empty($title))
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $title }}</h2>
                @if(!empty($subtitle))
                    <p class="text-gray-600">{{ $subtitle }}</p>
                @endif
            </div>
        @endif

        <div class="flex flex-wrap gap-3">
            @foreach(array_slice($tags, 0, $max_tags) as $tag)
                @php
                    $colorClass = $tagColors[$tag['color'] ?? 'gray'] ?? $tagColors['gray'];
                @endphp
                <a href="#" class="inline-flex items-center px-4 py-2 rounded-full text-sm font-medium border transition-colors {{ $colorClass }}">
                    {{ $tag['name'] ?? '' }}
                    @if($show_count && isset($tag['count']))
                        <span class="ml-2 text-xs opacity-70">({{ $tag['count'] }})</span>
                    @endif
                </a>
            @endforeach
        </div>
    </div>
</section>
