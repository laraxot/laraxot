@props([
    'title' => '',
    'content' => '',
    'image' => '',
    'reverse' => false
])

<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center {{ $reverse ? 'lg:grid-flow-col-dense' : '' }}">
            {{-- Content --}}
            <div class="{{ $reverse ? 'lg:col-start-2' : '' }}">
                @if($title)
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">{{ $title }}</h2>
                @endif
                
                <div class="prose prose-lg text-gray-600 max-w-none">
                    {!! $content !!}
                </div>
            </div>
            
            {{-- Image --}}
            @if($image)
                <div class="{{ $reverse ? 'lg:col-start-1' : '' }}">
                    <div class="relative overflow-hidden rounded-2xl shadow-lg">
                        <img src="{{ $image }}" 
                             alt="{{ $title }}" 
                             class="w-full h-80 lg:h-96 object-cover hover:scale-105 transition-transform duration-500"
                             loading="lazy">
                    </div>
                </div>
            @endif
        </div>
    </div>
</section>