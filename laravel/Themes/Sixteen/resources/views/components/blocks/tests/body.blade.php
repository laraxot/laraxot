@props([
    'body_title' => '',
    'body_text' => '',
    'content' => '',
])

<section class="py-8 bg-white">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($body_title)
            <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ $body_title }}</h2>
        @endif
        @if($body_text)
            <div class="text-gray-700 leading-relaxed">{!! $body_text !!}</div>
        @endif
        @if($content)
            <div class="text-gray-700 leading-relaxed">{!! $content !!}</div>
        @endif
    </div>
</section>
