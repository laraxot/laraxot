{{--
    Map Legend - Type indicators
    TailwindCSS
--}}

@php
    $items = $filters['items'] ?? [];
@endphp

@if (count($items) > 0)
<div class="mt-4 bg-white rounded-lg shadow-sm border border-gray-200 p-4">
    <h4 class="text-sm font-semibold text-gray-900 mb-3">
        {{ __('fixcity::ticket.map.legend.title') }}
    </h4>
    <div class="flex flex-wrap gap-3">
        @foreach ($items as $item)
            <div class="flex items-center gap-2">
                <span class="w-3 h-3 rounded-full" style="background-color: {{ $item['color'] ?? '#007A52' }}"></span>
                <span class="text-sm text-gray-700">{{ $item['label'] }}</span>
            </div>
        @endforeach
    </div>
</div>
@endif
