@props([
    'title' => '',
    'subtitle' => '',
    'testimonials' => [],
    'id' => 'testimonianze',
])

<section id="{{ $id }}" class="py-20 bg-gradient-to-br from-brand-blue via-brand-blue/90 to-brand-blue/80 text-white scroll-mt-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">{{ $title }}</h2>
            <p class="text-xl text-white/70 max-w-3xl mx-auto">{{ $subtitle }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            @foreach ($testimonials as $testimonial)
            <div class="bg-white/10 backdrop-blur-md p-8 rounded-2xl border border-white/20 hover:bg-white/15 transition-all duration-300">
                <div class="flex items-start gap-6">
                    @if(!empty($testimonial['avatar']))
                        <img src="{{ $testimonial['avatar'] }}" alt="{{ $testimonial['name'] ?? '' }}"
                             class="w-16 h-16 rounded-full object-cover border-2 border-brand-orange shadow-lg"
                             loading="lazy">
                    @else
                        <div class="w-16 h-16 rounded-full bg-brand-orange flex items-center justify-center text-white font-bold text-xl shadow-lg">
                            {{ isset($testimonial['name']) ? mb_substr($testimonial['name'], 0, 1) : '?' }}
                        </div>
                    @endif
                    <div class="flex-1">
                        <h3 class="text-xl font-bold mb-1">{{ $testimonial['name'] ?? '' }}</h3>
                        <p class="text-brand-orange-dark font-medium mb-4">{{ $testimonial['role'] ?? '' }}</p>
                        <p class="text-white/90 italic leading-relaxed">"{{ $testimonial['quote'] ?? $testimonial['content'] ?? '' }}"</p>
                        @if(!empty($testimonial['date']))
                            <p class="text-white/40 text-sm mt-4">{{ $testimonial['date'] }}</p>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>