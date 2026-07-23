@props([
    'services' => [],
])

<section class="py-20 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($services as $service)
                <div class="group bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-300 hover:-translate-y-2">
                    {{-- Image --}}
                    <div class="relative h-48 overflow-hidden">
                        <img src="{{ $service['image'] ?? 'https://images.unsplash.com/photo-1629909615957-be38d48fbbe6?w=600' }}"
                             alt="{{ $service['title'] ?? '' }}"
                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        <div class="absolute inset-0 bg-gradient-to-t from-[#1E5A96]/80 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 p-3 bg-white rounded-lg">
                            <svg class="w-8 h-8 text-[#2D8659]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                            </svg>
                        </div>
                    </div>
                    {{-- Content --}}
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $service['title'] ?? '' }}</h3>
                        <p class="text-gray-600 mb-4 leading-relaxed">{{ $service['description'] ?? '' }}</p>
                        <a href="{{ $service['url'] ?? '#' }}" class="inline-flex items-center text-[#1E5A96] hover:text-[#2D8659] font-semibold transition-colors group/btn">
                            Scopri di più
                            <svg class="ml-2 w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path d="M5 12h14" /><path d="m12 5 7 7-7 7" /></svg>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>
