@props(['data' => []])
@php
    $title = $data['title'] ?? '';
    $subtitle = $data['subtitle'] ?? '';
    $intro = $data['intro'] ?? '';
    $checklist = $data['checklist'] ?? [];
    $sectionId = $data['id'] ?? '';
@endphp
<section @if($sectionId) id="{{ $sectionId }}" @endif class="what-we-do-section py-5 bg-light">
    <div class="container">
        @if ($title)
            <h2 class="text-center fw-bold mb-3">{{ $title }}</h2>
        @endif
        @if ($subtitle)
            <p class="text-center text-muted mb-4">{{ $subtitle }}</p>
        @endif
        @if ($intro)
            <p class="text-center mb-5 mx-auto" style="max-width: 700px;">{{ $intro }}</p>
        @endif
        @if (count($checklist) > 0)
            <div class="row g-4">
                @foreach ($checklist as $item)
                    <div class="col-md-6 col-lg-4">
                        <div class="d-flex align-items-start gap-3 p-3 h-100">
@if (isset($item['icon']))
                                <div class="flex-shrink-0">
                                    <x-dynamic-component :component="$item['icon']" class="w-8 h-8 text-primary" />
                                </div>
                            @endif
                            <div>
                                <h5 class="fw-semibold mb-1">{{ $item['title'] ?? '' }}</h5>
                                <p class="text-muted small mb-0">{{ $item['description'] ?? '' }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
