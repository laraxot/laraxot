{{--
    Content Block - Bootstrap Italia Style
    Simple content block with title and HTML content
--}}
@props(['data' => []])

@php
    $title = $data['title'] ?? '';
    $content = $data['content'] ?? '';
@endphp

<div class="container py-5">
    @if($title)
    <h2 class="title-xxlarge mb-4">{{ $title }}</h2>
    @endif

    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="cmp-content">
                {!! $content !!}
            </div>
        </div>
    </div>
</div>
