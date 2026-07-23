@props([
    'title' => '',
    'methods' => [],
])

@php
    $iconMap = [
        'phone' => '<path stroke-linecap="round" stroke-linejoin="round" d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z"/>',
        'email' => '<path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>',
        'location' => '<path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z"/>',
    ];
    $colorMap = [
        'emerald' => 'bg-emerald-50 text-emerald-600',
        'teal' => 'bg-teal-50 text-teal-600',
        'cyan' => 'bg-cyan-50 text-cyan-600',
        'blue' => 'bg-blue-50 text-blue-600',
    ];
@endphp

<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        @if(!empty($title))
            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 mb-10 text-center">{{ $title }}</h2>
        @endif

        <div class="grid grid-cols-1 md:grid-cols-{{ count($methods) }} gap-8 max-w-5xl mx-auto">
            @foreach($methods as $method)
                @php
                    $icon = $iconMap[$method['icon'] ?? 'phone'] ?? $iconMap['phone'];
                    $color = $colorMap[$method['color'] ?? 'blue'] ?? $colorMap['blue'];
                @endphp
                <div class="text-center p-8 rounded-xl border border-gray-100 hover:shadow-lg transition-shadow">
                    <div class="w-16 h-16 mx-auto mb-4 rounded-full {{ $color }} flex items-center justify-center">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">{!! $icon !!}</svg>
                    </div>
                    <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $method['title'] ?? '' }}</h3>
                    @if(!empty($method['description']))
                        <p class="text-gray-500 text-sm mb-3">{{ $method['description'] }}</p>
                    @endif
                    @if(!empty($method['value']))
                        @if(!empty($method['url']))
                            <a href="{{ $method['url'] }}" class="text-[#1E5A96] font-semibold hover:underline">{{ $method['value'] }}</a>
                        @else
                            <p class="text-gray-900 font-medium">{{ $method['value'] }}</p>
                        @endif
                    @endif
                    @if(!empty($method['hours']))
                        <p class="text-xs text-gray-400 mt-2">{{ $method['hours'] }}</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</section>
