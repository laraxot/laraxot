@props(['data' => []])

{{-- 
    Details Block
    Usage: <x-blocks.details.details :data="$detailsData" />
    
    Data structure:
    - title: string
    - items: array of ['label' => string, 'value' => string, 'icon' => string (optional)]
    - layout: 'vertical' | 'horizontal' | 'grouped'
--}}

@php
    $layout = $data['layout'] ?? 'vertical';
    $title = $data['title'] ?? '';
    $items = $data['items'] ?? [];
@endphp

<div class="details-block {{ $layout === 'horizontal' ? 'details-horizontal' : ($layout === 'grouped' ? 'details-grouped' : 'details-vertical') }}">
    @if($title)
    <h3 class="details-title text-xl font-semibold mb-4 text-gray-900">
        {{ $title }}
    </h3>
    @endif
    
    @if($layout === 'horizontal')
        {{-- Horizontal Details Layout --}}
        <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($items as $item)
            <div class="detail-item flex flex-col">
                @if(isset($item['icon']))
                <dt class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-1">
                    <x-filament::icon :icon="$item['icon']" class="icon-sm icon-primary" />
                    {{ $item['label'] }}
                </dt>
                @else
                <dt class="text-sm font-medium text-gray-700 mb-1">
                    {{ $item['label'] }}
                </dt>
                @endif
                <dd class="text-base text-gray-900">
                    {{ $item['value'] }}
                </dd>
            </div>
            @endforeach
        </dl>
        
    @elseif($layout === 'grouped')
        {{-- Grouped Details Layout --}}
        <div class="space-y-6">
            @foreach($items as $group)
            <div class="detail-group">
                @if(isset($group['title']))
                <h4 class="detail-group-title text-sm font-semibold text-gray-700 mb-3 uppercase tracking-wide">
                    {{ $group['title'] }}
                </h4>
                @endif
                <dl class="space-y-2">
                    @foreach($group['items'] ?? [] as $item)
                    <div class="detail-item flex justify-between py-2 border-b border-gray-200">
                        @if(isset($item['icon']))
                        <dt class="flex items-center gap-2 text-sm font-medium text-gray-700">
                            <x-filament::icon icon="$item['icon']" class="icon-sm icon-primary" />
                            {{ $item['label'] }}
                        </dt>
                        @else
                        <dt class="text-sm font-medium text-gray-700">
                            {{ $item['label'] }}
                        </dt>
                        @endif
                        <dd class="text-base text-gray-900">
                            {{ $item['value'] }}
                        </dd>
                    </div>
                    @endforeach
                </dl>
            </div>
            @endforeach
        </div>
        
    @else
        {{-- Vertical Details Layout (Default) --}}
        <dl class="space-y-4">
            @foreach($items as $item)
            <div class="detail-item">
                @if(isset($item['icon']))
                <dt class="flex items-center gap-2 text-sm font-medium text-gray-700 mb-1">
                    <x-filament::icon :icon="$item['icon']" class="icon-sm icon-primary" />
                    {{ $item['label'] }}
                </dt>
                @else
                <dt class="text-sm font-medium text-gray-700 mb-1">
                    {{ $item['label'] }}
                </dt>
                @endif
                <dd class="text-base text-gray-900">
                    {{ $item['value'] }}
                </dd>
            </div>
            @endforeach
        </dl>
    @endif
</div>
