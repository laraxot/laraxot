@props(['data' => []])

@php
    $title = $data['title'] ?? '';
    $items = $data['items'] ?? [];
@endphp

@if(!empty($items))
<div class="container mt-4 mb-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            @if($title)
            <h3 class="title-medium-2-semi-bold mb-3">{{ $title }}</h3>
            @endif
            <nav aria-label="Navigatione secondaria">
                <ul class="list-unstyled mb-0">
                    @foreach($items as $item)
                    <li class="mb-2">
                        <a
                            href="{{ $item['url'] ?? '#' }}"
                            class="d-flex align-items-center gap-3 text-decoration-none p-3 rounded {{ !empty($item['active']) ? 'bg-primary bg-opacity-10 text-primary fw-semibold' : 'text-gray-800 hover-bg-gray-100' }} transition-colors"
                            @if(!empty($item['active'])) aria-current="page" @endif
                        >
                            @if(!empty($item['icon']))
                            <span class="d-flex align-items-center justify-content-center" style="width:24px;height:24px;">
                                <x-filament::icon :icon="$item['icon']" class="w-5 h-5" />
                            </span>
                            @endif
                            <span>{{ $item['label'] }}</span>
                            @if(!empty($item['active']))
                            <span class="ms-auto text-primary">
                                <svg class="icon icon-sm" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M9 18l6-6-6-6" />
                                </svg>
                            </span>
                            @endif
                        </a>
                    </li>
                    @endforeach
                </ul>
            </nav>
        </div>
    </div>
</div>
@endif
