{{--
/**
 * Sectors/Specializations Block - Theme Two
 * Two side-by-side cards with gradient headers and list items
 */
--}}

<section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        @if(isset($title))
            <div class="text-center mb-14">
                <h2 class="text-3xl lg:text-4xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
                @if(isset($description))
                    <p class="text-lg text-gray-600 max-w-2xl mx-auto">{{ $description }}</p>
                @endif
            </div>
        @endif

        @if(isset($sectors) && is_array($sectors))
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                @foreach($sectors as $index => $sector)
                    @php
                        $gradients = [
                            'from-emerald-600 to-teal-700',
                            'from-[#1e3a5f] to-[#2d5a8e]',
                            'from-indigo-600 to-purple-700',
                            'from-orange-500 to-red-600',
                        ];
                        $gradient = $sector['gradient'] ?? $gradients[$index % count($gradients)];
                    @endphp
                    <div class="bg-white rounded-2xl overflow-hidden shadow-sm border border-gray-100 hover:shadow-xl transition-all duration-300">
                        {{-- Gradient Header --}}
                        <div class="bg-gradient-to-r {{ $gradient }} p-6 lg:p-8">
                            @if(isset($sector['title']))
                                <h3 class="text-2xl font-bold text-white mb-1">{{ $sector['title'] }}</h3>
                            @endif
                            @if(isset($sector['subtitle']))
                                <p class="text-white/70 text-sm">{{ $sector['subtitle'] }}</p>
                            @endif
                        </div>

                        {{-- List Items --}}
                        @if(isset($sector['items']) && is_array($sector['items']))
                            <div class="p-6 lg:p-8 space-y-4">
                                @foreach($sector['items'] as $item)
                                    <div class="flex items-start gap-3 group">
                                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center mt-0.5 group-hover:bg-emerald-500 group-hover:text-white transition-colors duration-200">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/></svg>
                                        </div>
                                        <div>
                                            @if(isset($item['title']))
                                                <div class="font-semibold text-gray-900">{{ $item['title'] }}</div>
                                            @endif
                                            @if(isset($item['description']))
                                                <div class="text-sm text-gray-500 mt-0.5">{{ $item['description'] }}</div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</section>
