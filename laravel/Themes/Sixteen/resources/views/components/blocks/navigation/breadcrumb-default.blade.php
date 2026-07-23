@props(['items' => []])
@if(!empty($items))
<nav aria-label="Breadcrumb" class="mb-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <ol class="flex items-center space-x-2 text-sm text-gray-600">
            @foreach($items as $i => $item)
                @if($i > 0)
                    <x-filament::icon icon="heroicon-o-chevron-right" class="w-4 h-4" />
                @endif
                <li>
                    @if(!empty($item['url']))
                        <a href="{{ $item['url'] }}" class="hover:text-blue-600">{{ $item['label'] }}</a>
                    @else
                        <span class="text-gray-900 font-medium">{{ $item['label'] }}</span>
                    @endif
                </li>
            @endforeach
        </ol>
    </div>
</nav>
@endif
