@props(['data' => []])

{{-- Cards Grid - Bootstrap Italia Exact Replica --}}
@php
    $title = $data['title'] ?? '';
    $cards = $data['cards'] ?? [];
    $description = $data['description'] ?? '';
@endphp

<section class="py-5">
    <div class="container">
        @if($title)
        <h2 class="mb-3">{{ $title }}</h2>
        @endif
        
        @if($description)
        <p class="mb-4">{{ $description }}</p>
        @endif
        
        <div class="row g-4">
            @foreach($cards as $card)
            <div class="col-12 col-md-4">
                <div class="card card-teaser shadow p-4 rounded border border-light h-100">
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="{{ $card['url'] ?? '#' }}" class="text-decoration-none">
                                {{ $card['title'] ?? '' }}
                            </a>
                        </h5>
                        
                        @if($card['description'] ?? false)
                        <p class="card-text text-muted">{{ $card['description'] }}</p>
                        @endif
                        
                        @if($card['link_text'] ?? false)
                        <a href="{{ $card['url'] ?? '#' }}" class="btn btn-outline-primary btn-sm mt-2">
                            {{ $card['link_text'] }}
                            <svg class="icon icon-sm">
                                <use href="#it-arrow-right"></use>
                            </svg>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
