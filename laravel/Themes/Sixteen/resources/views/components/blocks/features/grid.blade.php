{{--
    Features Grid Block

    @var string $title
    @var string $description
    @var array $features [{icon, title, description, url, color}]
--}}

@if(isset($features) && is_array($features) && count($features) > 0)
<section class="py-16 bg-white" id="features">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(isset($title) && $title)
            <div class="text-center mb-16">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
                @if(isset($description) && $description)
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">{{ $description }}</p>
                @endif
            </div>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($features as $feature)
                <div class="group bg-white rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 p-8 border border-gray-100">
                    @if(isset($feature['icon']))
                        <div class="w-16 h-16 bg-blue-100 rounded-lg flex items-center justify-center mb-6 group-hover:bg-blue-200 transition-colors duration-300">
                            <x-filament::icon :icon="$feature['icon']" class="w-8 h-8 text-blue-600" />
                        </div>
                    @endif
                    <h3 class="text-xl font-semibold text-gray-900 mb-4">{{ $feature['title'] ?? '' }}</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed">{{ $feature['description'] ?? '' }}</p>
                    @if(isset($feature['url']))
                        <a href="{{ $feature['url'] }}" class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                            {{ $feature['link_text'] ?? 'Scopri di più' }}
                            <x-filament::icon icon="heroicon-o-arrow-right" class="w-4 h-4 ml-2" />
                        </a>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
@endif
