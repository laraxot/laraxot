@props(['data' => []])
@php
    $title = $data['title'] ?? '';
    $subtitle = $data['subtitle'] ?? '';
    $cards = $data['cards'] ?? [];
    $sectionId = $data['id'] ?? '';
@endphp
<section @if($sectionId) id="{{ $sectionId }}" @endif class="why-critical-section py-5 bg-light">
    <div class="container">
        @if ($title)
            <h2 class="text-center fw-bold mb-3">{{ $title }}</h2>
        @endif
        @if ($subtitle)
            <p class="text-center text-muted mb-5">{{ $subtitle }}</p>
        @endif
        @if (count($cards) > 0)
            <div class="row g-4">
                @foreach ($cards as $card)
                    <div class="col-md-6 col-lg-3">
                        <div class="card h-100 border-0 shadow-sm text-center p-4">
@if (isset($card['icon']))
                                <div class="card-icon mb-3">
                                    <x-dynamic-component :component="$card['icon']" class="w-12 h-12 text-primary mx-auto" />
                                </div>
                            @endif
                            <h5 class="card-title fw-semibold">{{ $card['title'] ?? '' }}</h5>
                            <p class="card-text text-muted small">{{ $card['description'] ?? '' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
