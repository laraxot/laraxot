@props(['data' => []])

{{-- 
    Timeline Block
    Usage: <x-blocks.timeline.timeline :data="$timelineData" />
    
    Data structure:
    - title: string (optional)
    - items: array of ['date' => string, 'title' => string, 'description' => string, 'icon' => string (optional)]
    - layout: 'vertical' | 'horizontal' (default: 'vertical')
--}}

@php
    $layout = $data['layout'] ?? 'vertical';
    $title = $data['title'] ?? '';
    $items = $data['items'] ?? [];
@endphp

<div class="timeline-block timeline-{{ $layout }}">
    @if($title)
    <h3 class="timeline-title text-2xl font-bold text-gray-900 mb-6">
        {{ $title }}
    </h3>
    @endif
    
    @if($layout === 'horizontal')
    {{-- Horizontal Timeline --}}
    <div class="timeline-wrapper horizontal overflow-x-auto pb-8">
        <div class="timeline-items flex gap-8 min-w-max">
            @foreach($items as $index => $item)
            <div class="timeline-item flex-1 min-w-[250px]">
                {{-- Timeline Dot --}}
                <div class="timeline-dot w-4 h-4 rounded-full bg-primary mx-auto mb-4 relative">
                    <div class="absolute inset-0 bg-primary rounded-full animate-ping"></div>
                </div>
                
                {{-- Date --}}
                @if(isset($item['date']))
                <span class="timeline-date text-sm font-semibold text-primary block mb-2">
                    {{ $item['date'] }}
                </span>
                @endif
                
                {{-- Icon (if provided) --}}
                @if(isset($item['icon']))
                <div class="timeline-icon mb-2">
                    <x-filament::icon icon="$item['icon']" class="icon-md icon-primary" />
                </div>
                @endif
                
                {{-- Title --}}
                @if(isset($item['title']))
                <h4 class="timeline-title text-lg font-semibold text-gray-900 mb-2">
                    {{ $item['title'] }}
                </h4>
                @endif
                
                {{-- Description --}}
                @if(isset($item['description']))
                <p class="timeline-description text-gray-600 text-sm">
                    {{ $item['description'] }}
                </p>
                @endif
                
                {{-- Connector Line (except last item) --}}
                @if(!$loop->last)
                <div class="timeline-connector hidden md:block absolute top-2 left-full w-full h-0.5 bg-gray-300 -z-10"></div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
    @else
    {{-- Vertical Timeline --}}
    <div class="timeline-wrapper vertical">
        <div class="timeline-line absolute left-2 md:left-1/2 top-0 bottom-0 w-0.5 bg-gray-300"></div>
        
        <div class="timeline-items space-y-8 relative">
            @foreach($items as $index => $item)
            <div class="timeline-item flex {{ $loop->even ? 'md:flex-row-reverse' : '' }}">
                {{-- Content --}}
                <div class="timeline-content flex-1 {{ $loop->even ? 'md:text-right' : '' }}">
                    {{-- Date --}}
                    @if(isset($item['date']))
                    <span class="timeline-date text-sm font-semibold text-primary block mb-2">
                        {{ $item['date'] }}
                    </span>
                    @endif
                    
                    {{-- Icon (if provided) --}}
                    @if(isset($item['icon']))
                    <div class="timeline-icon mb-2 {{ $loop->even ? 'md:flex md:justify-end' : '' }}">
                        <x-filament::icon 
                            :icon="$item['icon']" 
                            class="icon-md icon-primary" 
                            aria-hidden="true" 
                        />
                    </div>
                    @endif
                    
                    {{-- Title --}}
                    @if(isset($item['title']))
                    <h4 class="timeline-title text-lg font-semibold text-gray-900 mb-2">
                        {{ $item['title'] }}
                    </h4>
                    @endif
                    
                    {{-- Description --}}
                    @if(isset($item['description']))
                    <p class="timeline-description text-gray-600 text-sm">
                        {{ $item['description'] }}
                    </p>
                    @endif
                </div>
                
                {{-- Timeline Dot --}}
                <div class="timeline-dot w-4 h-4 rounded-full bg-primary absolute left-0 md:left-1/2 transform md:-translate-x-1/2 mt-1.5 z-10">
                    <div class="w-12 h-12 rounded-full bg-primary/20 absolute -inset-4 animate-ping"></div>
                </div>
                
                {{-- Spacer for alternating layout --}}
                <div class="flex-1 hidden md:block"></div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
