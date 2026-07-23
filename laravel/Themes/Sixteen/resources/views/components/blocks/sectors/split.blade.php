@props(['data' => []])
@php
    $title = $data['title'] ?? '';
    $subtitle = $data['subtitle'] ?? '';
    $sectors = $data['sectors'] ?? [];
    $sectionId = $data['id'] ?? '';
@endphp
<section @if($sectionId) id="{{ $sectionId }}" @endif class="sectors-section py-5">
    <div class="container">
        @if ($title)
            <h2 class="text-center fw-bold mb-3">{{ $title }}</h2>
        @endif
        @if ($subtitle)
            <p class="text-center text-muted mb-5">{{ $subtitle }}</p>
        @endif
        @if (count($sectors) > 0)
            @foreach ($sectors as $index => $sector)
                <div class="row align-items-center mb-5 g-5 @if($index % 2 !== 0) flex-row-reverse @endif">
                    @if (isset($sector['image']))
                        <div class="col-lg-6">
                            <img src="{{ $sector['image'] }}" alt="" class="img-fluid rounded shadow" loading="lazy">
                        </div>
                    @endif
                    <div class="col-lg-6">
                        <h3 class="fw-bold mb-2">{{ $sector['title'] ?? '' }}</h3>
                        @if (isset($sector['subtitle']))
                            <p class="text-primary fw-semibold mb-3">{{ $sector['subtitle'] }}</p>
                        @endif
                        <p>{{ $sector['description'] ?? '' }}</p>
                        @if (isset($sector['use_cases']) && count($sector['use_cases']) > 0)
                            <ul class="list-unstyled mt-3">
                                @foreach ($sector['use_cases'] as $case)
                                    <li class="mb-2 d-flex align-items-start gap-2">
                                        <span class="text-primary mt-1">&#10003;</span>
                                        <span>{{ $case }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            @endforeach
        @endif
    </div>
</section>
