@props(['data' => []])

{{-- Links List - Bootstrap Italia Exact Replica --}}
@php
    $title = $data['title'] ?? '';
    $links = $data['links'] ?? [];
    $description = $data['description'] ?? '';
@endphp

<section class="py-5 bg-it-gray-50">
    <div class="container">
        @if($title)
        <h2 class="mb-3">{{ $title }}</h2>
        @endif
        
        @if($description)
        <p class="mb-4">{{ $description }}</p>
        @endif
        
        <div class="link-list-wrapper">
            <ul class="link-list">
                @foreach($links as $link)
                <li>
                    <a href="{{ $link['url'] ?? '#' }}" @if($link['active'] ?? false) aria-current="page" @endif>
                        <span>{{ $link['label'] ?? '' }}</span>
                        
                        @if($link['description'] ?? false)
                        <p class="small text-muted mb-0">{{ $link['description'] }}</p>
                        @endif
                        
                        @if($link['badge'] ?? false)
                        <span class="badge badge-pill badge-outline-primary ms-2">{{ $link['badge'] }}</span>
                        @endif
                    </a>
                </li>
                @endforeach
            </ul>
        </div>
    </div>
</section>
