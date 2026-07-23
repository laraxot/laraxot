{{-- Quick Links Sidebar - Theme Two --}}
<div class="bg-white rounded shadow p-4 mb-4">
    @if(isset($title))
        <h3 class="font-semibold text-gray-900 mb-3 pb-2 border-b">{{ $title }}</h3>
    @endif

    @if(isset($links) && is_array($links))
        <ul class="space-y-2">
            @foreach($links as $link)
                <li>
                    <a href="{{ $link['url'] ?? '#' }}" 
                       class="flex items-center p-2 rounded hover:bg-gray-50 transition group">
                        @if(isset($link['icon']))
                            <x-dynamic-component :component="$link['icon']" class="w-4 h-4 text-gray-500 mr-2 group-hover:text-blue-600" />
                        @endif
                        <span class="text-gray-700 group-hover:text-blue-600">{{ $link['label'] ?? 'Link' }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    @endif
</div>
