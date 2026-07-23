@props(['data' => []])

{{-- 
    Featured News Component - Design Comuni Style
    Usage: <x-blocks.featured-news :data="$newsData" />
--}}

@php
    $title = $data['title'] ?? 'CONTENUTI IN EVIDENZA';
    $items = $data['items'] ?? [];
@endphp

<section class="featured-news py-8 bg-gray-50">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h2 class="title-xxlarge mb-6">{{ $title }}</h2>
            </div>
        </div>
        
        @foreach($items as $item)
        <div class="row">
            <div class="col-12">
                <div class="card card-bg shadow-sm">
                    <div class="row g-0">
                        <div class="col-md-8">
                            <div class="card-body p-6">
                                @if(isset($item['date']))
                                <p class="text-sm text-gray-600 mb-2">{{ $item['date'] }}</p>
                                @endif
                                <h3 class="h4 font-bold mb-3">
                                    <a href="{{ $item['url'] ?? '#' }}" class="text-decoration-none text-dark hover:text-primary">
                                        {{ $item['title'] }}
                                    </a>
                                </h3>
                                @if(isset($item['description']))
                                <p class="text-gray-600 mb-4">{{ $item['description'] }}</p>
                                @endif
                                @if(isset($item['link_text']))
                                <a href="{{ $item['url'] ?? '#' }}" class="text-primary hover:text-primary-dark font-medium inline-flex items-center gap-1">
                                    {{ $item['link_text'] }}
                                    <x-filament::icon icon="heroicon-m-arrow-right" class="w-4 h-4" />
                                </a>
                                @endif
                            </div>
                        </div>
                        @if(isset($item['image']))
                        <div class="col-md-4">
                            <img src="{{ $item['image'] }}" class="img-fluid rounded-end h-100 object-cover" alt="{{ $item['title'] }}" />
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
