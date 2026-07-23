@props([
    'title' => '',
    'subtitle' => '',
    'testimonials' => [],
    'id' => 'testimonials',
])

<section id="{{ $id }}" class="py-20 bg-gray-50 scroll-mt-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-14">
            @if($title)
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
            @endif
            @if($subtitle)
                <p class="text-lg text-gray-600 max-w-2xl mx-auto">{{ $subtitle }}</p>
            @endif
        </div>

        @if(!empty($testimonials))
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-5xl mx-auto">
                @foreach($testimonials as $testimonial)
                    <div class="relative bg-white rounded-2xl p-8 border border-gray-100 shadow-sm hover:shadow-lg transition-all duration-300">
                        <div class="absolute top-6 right-8 text-emerald-100">
                            <svg class="w-12 h-12" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10H14.017zM0 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151C7.546 6.068 5.983 8.789 5.983 11h4v10H0z"/></svg>
                        </div>

                        <div class="flex items-center gap-4 mb-4">
                            @if(!empty($testimonial['image']))
                                <img src="{{ $testimonial['image'] }}" alt="{{ $testimonial['name'] ?? '' }}"
                                     class="w-14 h-14 rounded-full object-cover ring-2 ring-gray-100"
                                     onerror="this.style.display='none'">
                            @else
                                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-emerald-400 to-teal-500 flex items-center justify-center text-white font-bold text-lg ring-2 ring-gray-100">
                                    {{ isset($testimonial['name']) ? mb_substr($testimonial['name'], 0, 1) : '?' }}
                                </div>
                            @endif
                            <div>
                                @if(!empty($testimonial['name']))
                                    <div class="font-bold text-gray-900">{{ $testimonial['name'] }}</div>
                                @endif
                                @if(!empty($testimonial['role']) || !empty($testimonial['location']))
                                    <div class="text-sm text-gray-500">
                                        {{ $testimonial['role'] ?? '' }}{{ !empty($testimonial['role']) && !empty($testimonial['location']) ? ', ' : '' }}{{ $testimonial['location'] ?? '' }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if(!empty($testimonial['content']))
                            <p class="text-gray-700 leading-relaxed italic mb-4">"{{ $testimonial['content'] }}"</p>
                        @endif

                        @if(!empty($testimonial['date']))
                            <div class="text-xs text-gray-400 mt-4">{{ $testimonial['date'] }}</div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
