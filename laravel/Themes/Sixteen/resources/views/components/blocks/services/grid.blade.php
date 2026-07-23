@props([
    'title' => '',
    'subtitle' => '',
    'services' => [],
    'id' => 'servizi',
])

@php
    $iconColors = ['text-brand-blue', 'text-brand-green', 'text-brand-orange'];
    $borderColors = ['border-t-brand-blue', 'border-t-brand-green', 'border-t-brand-orange'];
    $bgColors = ['bg-brand-blue/10', 'bg-brand-green/10', 'bg-brand-orange/10'];
    $textColors = ['text-brand-blue', 'text-brand-green', 'text-brand-orange'];
@endphp

<section id="{{ $id }}" class="py-20 bg-white scroll-mt-20">
    <div class="container mx-auto px-4">
        <div class="text-center mb-16">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4">{{ $title }}</h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">{{ $subtitle }}</p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach ($services as $i => $service)
            @php $ci = $i % 3; @endphp
            <div class="group bg-white border border-gray-100 {{ $borderColors[$ci] }} border-t-4 rounded-xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 hover:-translate-y-1">
                {{-- Image or Icon --}}
                @if(isset($service['image']) && !empty($service['image']))
                <div class="aspect-w-16 aspect-h-9 bg-gradient-to-br from-{{ $bgColors[$ci] }} to-gray-100">
                    <img src="{{ $service['image'] }}" alt="{{ $service['title'] ?? '' }}" class="w-full h-48 object-cover" loading="lazy">
                </div>
                @else
                <div class="w-full h-48 {{ $bgColors[$ci] }} flex items-center justify-center">
                    @if(!empty($service['icon']))
                        @php
                            // Sanitize icon name and check if component exists
                            $iconName = str_replace('heroicon-o-', '', $service['icon']);
                            $componentName = 'heroicon-o-' . $iconName;
                            // Valid Heroicons components
                            $validIcons = ['bolt', 'lightning-bolt', 'check-circle', 'light-bulb', 'heart', 'user-group', 'shield-check', 'academic-cap', 'fire', 'star', 'home', 'information-circle'];
                        @endphp
                        @if(in_array($iconName, $validIcons))
                            <x-dynamic-component :component="$componentName" class="w-16 h-16 {{ $textColors[$ci] }}" />
                        @else
                            <!-- Fallback icon for invalid heroicon components -->
                            <svg class="w-16 h-16 {{ $textColors[$ci] }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                        @endif
                    @else
                        <svg class="w-16 h-16 {{ $textColors[$ci] }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    @endif
                </div>
                @endif

                <div class="p-8">
                    <h3 class="text-xl font-bold text-gray-900 mb-3">{{ $service['title'] ?? '' }}</h3>
                    <p class="text-gray-600 mb-6 leading-relaxed text-sm">{{ $service['description'] ?? '' }}</p>

                    <a href="{{ $service['url'] ?? '#' }}"
                       class="inline-flex items-center {{ $textColors[$ci] }} font-semibold text-sm hover:underline group-hover:translate-x-1 transform transition-all duration-300">
                        {{ $service['cta'] ?? 'Scopri di più' }}
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>