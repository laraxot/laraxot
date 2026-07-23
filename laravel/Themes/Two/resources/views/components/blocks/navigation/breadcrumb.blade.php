@props([
    'items' => [],
    'separator' => '/',
])

@if(!empty($items))
<nav class="breadcrumb" aria-label="Breadcrumb">
    <ol class="flex items-center space-x-2 text-sm">
        @foreach($items as $index => $item)
            @if($index > 0)
                <li class="text-gray-400" aria-hidden="true">
                    {{ $separator }}
                </li>
            @endif
            
            @if($index === count($items) - 1)
                <li class="text-gray-900 font-medium" aria-current="page">
                    {{ $item['label'] }}
                </li>
            @else
                <li>
                    <a href="{{ $item['url'] }}" class="text-gray-600 hover:text-primary-600 transition-colors">
                        {{ $item['label'] }}
                    </a>
                </li>
            @endif
        @endforeach
    </ol>
</nav>
@endif

<style>
.breadcrumb {
    padding: 1rem 0;
}
</style>