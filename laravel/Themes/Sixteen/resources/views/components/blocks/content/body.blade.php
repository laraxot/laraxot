@props([
    'body_title' => '',
    'body_text' => '',
])

<section class="content-body py-5">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-10">
                @if($body_title)
                    <h2 class="mb-4">
                        {{ $body_title }}
                    </h2>
                @endif

                @if($body_text)
                    <div class="richtext-wrapper font-serif text-muted">
                        {!! $body_text !!}
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
