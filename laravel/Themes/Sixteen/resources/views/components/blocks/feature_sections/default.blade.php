@props([
    'title' => '',
    'sections' => [],
])

@if(!empty($sections))
<section class="py-12 bg-white">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        @if($title)
            <h2 class="text-3xl font-bold text-gray-900 mb-8 text-center">{{ $title }}</h2>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-{{ min(count($sections), 3) }} gap-8">
            @foreach($sections as $section)
                <div class="text-center p-6">
                    @if(!empty($section['icon']))
                        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <x-filament::icon icon="heroicon-o-building-office" class="w-8 h-8 text-blue-600" />
                        </div>
                    @endif
                    <h3 class="text-xl font-semibold text-gray-900 mb-2">{{ $section['title'] ?? '' }}</h3>
                    <p class="text-gray-600">{{ $section['description'] ?? '' }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
