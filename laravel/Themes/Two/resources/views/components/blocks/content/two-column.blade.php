@props([
    'title' => '',
    'content' => '',
    'image' => '',
    'features' => [],
])

<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        {{-- Section Title --}}
        @if(!empty($title))
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            {{-- Image Column --}}
            @if(!empty($image))
                <div class="order-2 lg:order-1">
                    <div class="relative">
                        <img
                            src="{{ $image }}"
                            alt="{{ $title }}"
                            class="w-full h-auto rounded-xl shadow-lg"
                            loading="lazy"
                        >
                        {{-- Decorative elements --}}
                        <div class="absolute -bottom-6 -right-6 w-24 h-24 bg-blue-100 rounded-full -z-10"></div>
                        <div class="absolute -top-6 -left-6 w-16 h-16 bg-green-100 rounded-full -z-10"></div>
                    </div>
                </div>
            @endif

            {{-- Content Column --}}
            <div class="order-1 lg:order-2">
                @if(!empty($content))
                    <div class="prose prose-lg max-w-none mb-8">
                        <p class="text-gray-700 leading-relaxed">{{ $content }}</p>
                    </div>
                @endif

                {{-- Features List --}}
                @if(!empty($features))
                    <div class="space-y-6">
                        @foreach($features as $feature)
                            <div class="flex items-start space-x-4">
                                <div class="flex-shrink-0 w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                                    @if(!empty($feature['icon']))
                                        <x-filament::icon
                                            :icon="$feature['icon']"
                                            class="h-6 w-6 text-blue-600"
                                        />
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    @endif
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-1">
                                        {{ $feature['title'] ?? '' }}
                                    </h3>
                                    <p class="text-gray-600 text-sm">
                                        {{ $feature['description'] ?? '' }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
