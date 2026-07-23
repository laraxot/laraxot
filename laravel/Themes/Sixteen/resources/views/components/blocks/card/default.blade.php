{{--
    Card Block - Bootstrap Italia Style
    Single card with title and content
--}}
@props(['data' => []])

@php
    $title = $data['title'] ?? '';
    $content = $data['content'] ?? '';
    $url = $data['url'] ?? '#';
@endphp

<div class="container py-5">
    <div class="row">
        <div class="col-12 col-lg-8">
            <div class="cmp-card-simple card-wrapper pb-0 rounded border border-light">
                <div class="card shadow-sm rounded">
                    <div class="card-body">
                        @if($url && $url !== '#')
                        <a href="{{ $url }}" class="text-decoration-none">
                            <h3 class="card-title t-primary title-xlarge">{{ $title }}</h3>
                        </a>
                        @else
                        <h3 class="card-title t-primary title-xlarge">{{ $title }}</h3>
                        @endif
                        @if($content)
                        <div class="text-secondary mb-0 description">
                            {!! $content !!}
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
