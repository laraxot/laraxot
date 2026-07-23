{{-- News Content Component --}}
@props([
    'content' => '',
    'image' => '',
    'image_alt' => 'News image',
])

<div class="news-content py-8">
    <div class="row">
        <div class="col-12 col-lg-8 mx-auto">
            @if($image)
            <figure class="figure img-fluid mb-4">
                <img src="{{ $image }}" alt="{{ $image_alt }}" class="img-fluid rounded shadow-sm w-100" />
            </figure>
            @endif
            
            <div class="content-text">
                {!! $content !!}
            </div>
        </div>
    </div>
</div>
