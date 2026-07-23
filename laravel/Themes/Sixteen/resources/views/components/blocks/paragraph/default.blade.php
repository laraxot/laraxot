@props([
    'content' => '',
    'class' => 'py-8 bg-white',
])

@if($content)
<section class="{{ $class }}">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        {!! $content !!}
    </div>
</section>
@endif
