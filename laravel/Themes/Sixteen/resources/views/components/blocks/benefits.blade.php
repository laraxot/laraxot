@props([
    'title' => 'Perché Sceglierci',
    'subtitle' => 'I vantaggi che fanno la differenza',
    'benefits' => []
])

<section class="py-20 bg-white">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
            @if($subtitle)
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">{{ $subtitle }}</p>
            @endif
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($benefits as $index => $benefit)
                <div class="group bg-white border border-gray-100 rounded-xl p-8 shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                    <div class="w-14 h-14 bg-gradient-to-br from-brand-blue/10 to-brand-blue/5 rounded-xl flex items-center justify-center mb-6 group-hover:from-brand-blue/20 group-hover:to-brand-blue/10 transition-all duration-300">
                        @if(isset($benefit['icon']))
                            {!! $benefit['icon'] !!}
                        @else
                            <svg class="w-7 h-7 text-brand-blue" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        @endif
                    </div>

                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $benefit['title'] }}</h3>
                    <p class="text-gray-600 leading-relaxed">{{ $benefit['description'] }}</p>

                    @if(isset($benefit['features']) && is_array($benefit['features']))
                        <ul class="mt-4 space-y-2">
                            @foreach($benefit['features'] as $feature)
                                <li class="flex items-center text-sm text-gray-600">
                                    <svg class="w-4 h-4 text-brand-green mr-2 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    {{ $feature }}
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            @endforeach
        </div>

        @if(isset($cta))
            <div class="text-center mt-12">
                <a href="{{ $cta['url'] }}" 
                   class="inline-flex items-center justify-center font-semibold rounded-lg bg-brand-blue hover:bg-brand-blue/90 text-white text-lg px-8 py-4 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1">
                    {{ $cta['label'] }}
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        @endif
    </div>
</section>